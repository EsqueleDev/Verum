<?php
require 'vendor/autoload.php';
include 'PhpShits/userFunctions.php';
include 'PhpShits/conn.php';
use chillerlan\QRCode\QRCode;

$MyId = $_COOKIE['UserId'];

$data = "profile.php?id=$MyId";

$me = getUserInfo($conn, $MyId);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicone no celular do seu amigo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>
<span class='hideMobile'></span>
<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick='window.history.back();'><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">QR Code</span>
        <span></span>
    </header>
    <br>
    <center>
        <?php echo '<img style="width: 80%; border-radius: 25px;" src="'.(new QRCode)->render($data).'" />'; ?><br>
        <h3 style='width: 80%;'>Escaneie este QR-Code no seu Verum para ver o perfil de <b><?= $me['username'] ?></b></h3>
    </center>
</div>
</body>
</html>