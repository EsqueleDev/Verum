<?php
    include 'PhpShits/conn.php';
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        setcookie("UserId", "", time() - 3600, "/"); 
        echo "<script>window.location.href = 'login.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deslogar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn"></button>
        <span class="app-title">Deslogar?</span>
        <span></span>
    </header><br>
    <center>
        <div style='width: 80%;'>
            <h3>Tem certeza que quer deslogar?</h3><br>
            <form method='POST'>
                <button class='btn btn-primary'>Sim, deslogue e me leve ao inicio.</button><br><br>
                <input name='postId' value='<?= $postId ?>' type='hidden'>
            </form>
            <a href='home.php'><button class='btn btn-secondary'>Não, volte a pagina inical.</button><br></a>
        </div>
    </center>
</div>
</body>
</html>