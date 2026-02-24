<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include "PhpShits/conn.php";
        include "securityPenis.php";

        $userAuthId = generate_secure_string(64);
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $profilePic = "Default_Profile_Pics/" . rand(1, 14) . ".png";
        $sql = "INSERT INTO user (userAuthId, username, email, password, profilePic)
            VALUES ('$userAuthId', '$username', '$email', '$password', '$profilePic')";

        if ($conn->query($sql) === TRUE) {
            setcookie("UserId", $conn->insert_id, time()+60*60*24*365);
            echo "<script>localStorage.setItem('userAuthId', '$userAuthId'); window.location.href = 'userLikes.php';</script>";
        } else {
            echo "<script>window.location.href = 'serverfallback.php?error=accountCreatingError';</script>";
        }

        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php">
    <base href="/Project-Verum/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <form class="form-box" method="post" id="register-form">
        <div class="form-header">
            <h1>Vamos começar o<br>seu cadastro</h1>
            <p>O Verum irá pedir informações básicas</p>
        </div>

        <div class="form-group">
            <label>Nome de usuário</label>
            <input type="text" name="username" placeholder="Example">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name='email' placeholder="example@example.com">
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="password" placeholder="TopSecretPassword1234">
        </div>

    </form>

    <div class="form-footer">
        <button class="btn btn-secondary" onclick='window.history.back()'>✕ Cancel</button>
        <button class="btn btn-primary" onclick="document.getElementById('register-form').submit()">Continue</button>
    </div>


</body>
</html>
