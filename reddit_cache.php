<?php
header('Content-Type: application/json; charset=utf-8');

$subreddit = $_GET['sub'] ?? 'ArtistasFamintos';

$cacheDir = "cache";
$cacheFile = $cacheDir."/reddit_".$subreddit.".json";
$cacheTime = 120;

if(!is_dir($cacheDir)){
    mkdir($cacheDir, 0777, true);
}

function quebrarPalavrasGrandes($texto, $limite = 30) {
    return preg_replace('/(\S{'.$limite.'})/u', '$1 -<wbr>', $texto);
}

if(file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime){
    echo file_get_contents($cacheFile);
    exit;
}

$afterKey = $_COOKIE['lastRedditPostOpened_'.$subreddit] ?? '';

$curl = curl_init();

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'User-Agent: VerumBot/1.0'
]);

$tentativas = 0;

while($tentativas < 10){

    $tentativas++;

    $url = "https://www.reddit.com/r/$subreddit/.json?limit=1&raw_json=1&after=$afterKey";
    curl_setopt($curl, CURLOPT_URL, $url);

    $data = curl_exec($curl);

    if($data === false){
        echo json_encode(["error"=>curl_error($curl)]);
        exit;
    }

    $data = json_decode($data, true);

    if(empty($data['data']['children'])){
        echo json_encode(["posts"=>[]]);
        exit;
    }

    $post = $data['data']['children'][0]['data'];

    $afterKey = $data['data']['after'];

    if($post['is_robot_indexable'] && !$post['over_18'] && !$post['is_video']){

        setcookie("lastRedditPostOpened_$subreddit", $afterKey, time()+3600);

        $redditPost = [
            "posts" => [[
                "titulo" => $post['title'],
                "conteudo" => quebrarPalavrasGrandes($post['selftext_html']) ?? '',
                "url" => $post['url'],
                "imagemAnexo" => $post['preview']['images'][0]['source']['url'],
                "author" => $post['author']
            ]]
        ];

        $json = json_encode($redditPost);

        file_put_contents($cacheFile, $json);

        echo $json;
        exit;
    }
}

curl_close($curl);

echo json_encode(["posts"=>[]]);
?>