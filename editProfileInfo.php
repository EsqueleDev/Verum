<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/userFunctions.php';

    $me = getUserInfo($conn, $_COOKIE['UserId']);
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST['username'];
        
        $profilePic = $me['profilePic']; // Default to current profile pic
        
        // Handle file upload
        if(isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profileImage']['tmp_name'];
            $fileName = $_FILES['profileImage']['name'];
            $fileSize = $_FILES['profileImage']['size'];
            $fileType = $_FILES['profileImage']['type'];
            
            // Get file extension
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            // Allowed extensions
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
            
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Create new file name
                $newFileName = 'profile_' . $me['id'] . '_' . time() . '.' . $fileExtension;
                
                // Directory where images will be saved
                $uploadFileDir = 'uploads/images/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    $profilePic = $dest_path;
                }
            }
        }

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
    <link rel="stylesheet" href="colors.php">
    <base href="/Project-Verum/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <form class="form-box" method="post" id="register-form" enctype="multipart/form-data">
        <div class="form-header">
            <h1>Edite seu<br>perfil</h1>
            <p>Mude sua foto e seu nome</p>
        </div>

        <div class="form-group">
            <label>Foto de perfil</label>
            <div class="profile-pic-container">
                <div class="avatar-preview" id="avatarPreview" style="background: url(<?= htmlspecialchars($me['profilePic'] ?? '') ?>); background-repeat: no-repeat; background-size: cover;" onclick="document.getElementById('profileImage').click()"></div>
                <input type="file" name="profileImage" id="profileImage" accept="image/*" onchange="previewProfileImage(this)">
            </div>
        </div>

        <div class="form-group">
            <label>Nome de usuário</label>
            <input type="text" name="username" value="<?= $me['username'] ?>">
        </div>

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
