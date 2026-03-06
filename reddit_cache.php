<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'PhpShits/conn.php';
include 'PhpShits/userFunctions.php';

header('Content-Type: application/json');

$userId = $_COOKIE['UserId'] ?? 0;
$after = $_GET['after'] ?? '';

$likes = getUserLikes($conn, $userId);

$subs = [];

/* pega subs dos likes */
foreach ($likes as $like) {

    if(empty($like["subreddit"])) continue;

    $sub = trim($like["subreddit"]);
    $sub = str_replace("r/", "", $sub);
    $sub = str_replace("/", "", $sub);

    if(!empty($sub)){
        $subs[] = $sub;
    }
}

/* fallback se usuário não tiver likes suficientes */
$fallbackSubs = [
"popular",
"technology",
"movies",
"gaming",
"music",
"programming",
"pixelart",
"drawing",
"art",
"memes"
];

/* mistura tudo */
$subs = array_unique(array_merge($subs, $fallbackSubs));

shuffle($subs);

$posts = [];
$usedSub = null;
$afterToken = null;

/* tenta até 5 subreddits diferentes */
foreach(array_slice($subs,0,5) as $sub){

    $url = "https://www.reddit.com/r/$sub/hot.json?limit=5";

    if($after){
        $url .= "&after=" . urlencode($after);
    }

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'VerumApp/1.0');

    $response = curl_exec($ch);

    curl_close($ch);

    if(!$response) continue;

    $data = json_decode($response,true);

    if(!isset($data["data"]["children"])) continue;

    foreach($data["data"]["children"] as $post){
    
        $p = $post["data"];
    
        if($p["over_18"]) continue;
    
        if(empty($p["title"])) continue;
    
        /* remove posts fixados */
        if($p["stickied"]) continue;
    
        /* remove automoderator */
        if($p["author"] == "AutoModerator") continue;
    
        /* remove posts só texto */
        if($p["is_self"]) continue;
    
        /* remove posts sem mídia */
        if(empty($p["url"])) continue;
    
        $posts[] = $post;
    
        if(count($posts) >= 3){
            break;
        }
    }

    if(count($posts) > 0){

        $usedSub = $sub;
        $afterToken = $data["data"]["after"] ?? null;

        break;
    }

}

/* resposta final */

echo json_encode([
    "subreddit"=>$usedSub,
    "after"=>$afterToken,
    "posts"=>$posts
]);