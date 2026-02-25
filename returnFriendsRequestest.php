<?php
include 'PhpShits/conn.php';
include 'PhpShits/userFunctions.php';
include 'PhpShits/connectionsUsersFuncs.php';
include 'PhpShits/algoritimoMACHO.php';
$me = getUserInfo($conn, $_COOKIE['UserId']);
// 1. Set the Content-Type header
header('Content-Type: application/json');

if(checkNewRequests($conn, $me['id'])){
    // 2. Create a PHP associative array or object with your data
    $data = [
        0 => [
            'titulo' => 'Você recebeu pedidos de amizade:',
            'mensagem' => 'Venha conferir',
            'url' => ''
        ]
    ];

    // 3. Encode the data into a JSON string and echo it
    echo json_encode($data);
}
?>
