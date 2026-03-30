<?php
    header('Content-Type: application/json');
    //O uso desta porra: API/getUserData.php?UserId=1&SecureToken=none
    include "../PhpShits/conn.php";
    include "../PhpShits/userFunctions.php";
    
    //Parametros sexuaisssssss
    $UserId = $_GET['UserId'];
    if($_GET['SecureToken'] != 'none'){
        $SecureToken = $_GET['SecureToken'];
    }
    else{
        $SecureToken = null;
    }
    
    $UserQuery = getUserInfo($conn, $UserId, $SecureToken);
    
    echo json_encode($UserQuery);
?>