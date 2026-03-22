<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'PhpShits/conn.php';
    include 'PhpShits/userFunctions.php';
    include 'PhpShits/albumFuncs.php';
    
    $MyId = $_COOKIE['UserId'];
    $MeusAlbuns = getUserAlbuns($conn, $MyId);
    
    function quebrarPalavrasGrandes($texto, $limite = 30) {
        return preg_replace('/(\S{'.$limite.'})/u', '$1 -<wbr>', $texto);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seus Albuns</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php">
</head>
<body>
<span class='hideMobile'></span>
<div class="app-container">
    <header class="app-header">
        <button class="icon-btn"></button>
        <span class="app-title">Seus Albuns</span>
        <span></span>
    </header>
    
    <div class='albuns-pics'>
        <div class='album'>
            <span class='album-cover' style='background-image: url(https://placehold.co/400?text=Novo); background-repeat: no-repeat; background-size: cover;'></span><br>
            <center><h2>Criar</h2></center>
        </div>
        <?php foreach($MeusAlbuns as $album): ?>
            <div class='album'>
                <span class='album-cover' style='background-image: url(<?= getLastPictureInAlbum($conn, $album['Id']) ?>); background-repeat: no-repeat; background-size: cover;'></span><br>
                <center><h2><?=quebrarPalavrasGrandes($album['Titulo'], 6) ?></h2></center>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>