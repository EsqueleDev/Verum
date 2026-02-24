<?php
    if(isset($_COOKIE['UserId'])){
        echo "<script>window.location.href = 'home';</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Welcome</title>
    <base href="/Mobile_Verum/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php">
</head>
<body>

<div class="app-container">
    <div class="center-screen">
        <div class="welcome-box">
            <h1>Bem Vindo(a) ao<br>Verum</h1>

            <div class="button-group">
                <a href='login.php'><button class="btn btn-secondary">Fazer Login</button></a>
                <a href='signup'><button class="btn btn-primary">Criar Uma Conta</button></a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
