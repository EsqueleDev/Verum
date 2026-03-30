<?php
    include 'PhpShits/conn.php';
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $postId = $_POST['postId'];
        $stmt = $conn->prepare("UPDATE `post` SET `postApagado` = '1' WHERE `post`.`id` = ?;");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        
        echo "<script>window.location.href = 'home.php';</script>";
    }
    else{
        $postId = $_GET['postId'];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deletar Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn"></button>
        <span class="app-title">Deletar o Post?</span>
        <span></span>
    </header><br>
    <center>
        <div style='width: 80%;'>
            <h3>Tem certeza de que quer apagar este post?</h3><br>
            <form method='POST'>
                <button class='btn btn-primary'>Sim, apagar este post.</button><br><br>
                <input name='postId' value='<?= $postId ?>' type='hidden'>
            </form>
            <a href='home.php'><button class='btn btn-secondary'>Não, manter post no ar</button><br></a>
            <p>Lembre sempre que a exclusão estara sujeita os termos de serviço.</p>
        </div>
    </center>
</div>
</body>
</html>