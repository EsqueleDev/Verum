<?php
    header('Content-Type: application/json');
    
    include 'PhpShits/conn.php';
    include 'PhpShits/userFunctions.php';
    include 'PhpShits/algoritimoBoiola.php';
    include 'PhpShits/algoritimoMACHO.php';

    // Get the page number from the request
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
    
    // Validate user is logged in
    if (!isset($_COOKIE['UserId'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }
    
    $userId = $_COOKIE['UserId'];
    
    // Fetch posts using the existing algorithm
    $posts = algoritmoGeralSite($conn, $userId, $page, $limit);
    
    // Get user info for each post
    $postsWithUsers = [];
    foreach ($posts as $post) {
        $user = getUserInfo($conn, $post['userId']);
        $post['user'] = [
            'username' => $user['username'],
            'profilePic' => $user['profilePic']
        ];
        $postsWithUsers[] = $post;
    }
    
    echo json_encode([
        'success' => true,
        'posts' => $postsWithUsers,
        'hasMore' => count($posts) === $limit,
        'page' => $page
    ]);
    
    $conn->close();
?>
