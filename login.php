<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "PhpShits/conn.php";
    include "securityPenis.php";

    $username = $_POST['username'];
    $password = $_POST['password']; // senha em texto puro

    $stmt = $conn->prepare("SELECT id, userAuthId, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            echo "<script>window.location.href = 'home';</script>";
            $userAuthId = $row['userAuthId'];
            $id = $row['id'];
            setcookie("UserId", $id, time()+60*60*24*365);
            
            echo "<script>localStorage.setItem('userAuthId', '$userAuthId');</script>";
        } else {
            echo "<script>window.location.href = 'userErrors.php?error=incorrectpassword';</script>";
        }
    } else {
        echo "<script>window.location.href = 'userErrors.php?error=nousername';</script>";
    }

    $stmt->close();
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
            <h1>Bem Vindo<br>de volta</h1>
            <p>Digite o usuario e senha</p>
        </div>

        <div class="form-group">
            <label>Nome de usuário</label>
            <input type="text" name="username" placeholder="Example">
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
