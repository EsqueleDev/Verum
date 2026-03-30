<?php
    include('PhpShits/conn.php');
    $errorCode = $_GET['code'];
    
    $stmt = $conn->prepare("SELECT * FROM errors WHERE code = ?;");
    $stmt->bind_param("i", $errorCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $error = $row;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Error Code: <?= $errorCode ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>
<span class='hideMobile'></span>
<div class="app-container">
    <header class="app-header">
        <button class="icon-btn"></button>
        <span class="app-title"><?= $error['Titulo'] ?></span>
        <span></span>
    </header><br><br>
    <span width='70%'>
        <center><h3><?= $error['Descrição'] ?></h3><br>
        <?= $error['Complemento'] ?></center>
    </span>
</div>
</body>
</html>