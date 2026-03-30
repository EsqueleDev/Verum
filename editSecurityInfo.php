<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/userFunctions.php';

    echo "
        <script>
            function ProcessFormUserSecureToken(){
                const UserSecureToken = document.getElementById('UserSecureToken');
                const UserSecureTokenValue = localStorage.getItem('userAuthId');
                return UserSecureTokenValue;
            }
        </script>
    ";

    $me = getUserInfo($conn, $_COOKIE['UserId']);
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $sql = "UPDATE users SET username ='$username', profilePic ='$profilePic'
                WHERE id = " . $me['id'] .";";

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href = 'profile.php';</script>";
        } else{
            echo "<script>window.location.href = 'serverfallback.php?error=accountCreatingError';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Editar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <form class="form-box" method="post" id="register-form" enctype="multipart/form-data">
        <div class="form-group">
            <label>E-mail:</label>
            <input type="text" name="username" value="<?= $me['email'] ?>">
        </div>
        <input type='hidden' id='UserSecureToken'>
    </form>

    <div class="form-footer">
        <button class="btn btn-secondary" onclick='window.history.back()'>Voltar</button>
        <button class="btn btn-primary" onclick="document.getElementById('register-form').submit()">Salvar</button>
    </div>
    
    <script>
    function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('avatarPreview').style.background = 'url(' + e.target.result + ')';
                document.getElementById('avatarPreview').style.backgroundRepeat = 'no-repeat';
                document.getElementById('avatarPreview').style.backgroundSize = 'cover';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>
</html>
