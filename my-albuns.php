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
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>
<span class='hideMobile'></span>
<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">Seus Albuns</span>
        <span></span>
    </header>
    
    <div class='albuns-pics'>
        <a href='createAlbum.php'><div class='album'>
            <span class='album-cover' style='background-image: url(https://placehold.co/400?text=Novo); background-repeat: no-repeat; background-size: cover;'></span><br>
            <center><h2>Criar</h2></center>
        </div></a>
        <?php foreach($MeusAlbuns as $album): ?>
            <a href='albumViewer.php?albumid=<?= $album['Id'] ?>'><div class='album'>
                <span class='album-cover' style='background-image: url(<?= getLastPictureInAlbum($conn, $album['Id']) ?>); background-repeat: no-repeat; background-size: cover;'></span><br>
                <center><h2><?=quebrarPalavrasGrandes($album['Titulo'], 8) ?></h2></center>
            </div></a>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>