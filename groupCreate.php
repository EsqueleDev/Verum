<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        require_once 'PhpShits/conn.php';
    
        if(isset($_FILES['groupPic']) && $_FILES['groupPic']['error'] === 0){
    
            $uploadDir = "uploads/groups/";
            
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0755, true);
            }
    
            $fileTmp  = $_FILES['groupPic']['tmp_name'];
            $fileName = uniqid() . "_" . basename($_FILES['groupPic']['name']);
            $filePath = $uploadDir . $fileName;
    
            // Verifica se é imagem mesmo
            $allowedTypes = ['image/jpeg','image/png','image/gif','image/webp'];
            $fileType = mime_content_type($fileTmp);
    
            if(in_array($fileType, $allowedTypes)){
    
                if(move_uploaded_file($fileTmp, $filePath)){
    
                    $stmt = $conn->prepare("INSERT INTO grupos (nome, descricao, imagemUrl, oculto, admID) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssi",
                        $_POST['nome'],
                        $_POST['descricao'],
                        $filePath,
                        $_POST['privacidade'],
                        $_COOKIE['UserId']
                    );
    
                    if ($stmt->execute()) {
                        $grupo = $conn->insert_id;
                        echo "<script>window.location.href = 'grupo?id=$grupo';</script>";
                    }
    
                    $stmt->close();
                }
    
            } else {
                echo "Arquivo não é uma imagem válida.";
            }
    
        } else {
            echo "Erro no upload da imagem.";
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
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <form class="form-box" method="post" id="register-form" enctype="multipart/form-data">
        <div class="form-header">
            <h1>Crie um<br>grupo</h1>
            <p>Uma comunidade para você e seus amigos</p>
        </div>

        <div class="form-group">
            <label>Foto do grupo</label>
            <div class="profile-pic-container">
                <div class="avatar-preview imageNotSelected" id="avatarPreview" style="background: url(); background-repeat: no-repeat; background-size: cover;" onclick="document.getElementById('profileImage').click()"></div>
                <input type="file" name="groupPic" id="profileImage" accept="image/*" onchange="previewProfileImage(this)">
            </div>
        </div>

        <div class="form-group">
            <label>Nome de grupo</label>
            <input type="text" name="nome" placeholder="Grupo legal">
        </div>
        
        <div class="form-group">
            <label>Descrição de grupo</label>
            <textarea rows='4' name='descricao' placeholder="Uma otima descrição"></textarea>
        </div>
        
        <div class="form-group">
            <label>Privacidade do Grupo</label>
            <select id="priv" name="privacidade">
              <option value="0">Publico</option>
              <option value="1">Fechado</option>
            </select>
        </div>

    </form>

    <div class="form-footer">
        <button class="btn btn-secondary" onclick='window.history.back()'>Voltar</button>
        <button class="btn btn-primary" onclick="document.getElementById('register-form').submit()">Criar</button>
    </div>
    
    <script>
    function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('avatarPreview').style.background = 'url(' + e.target.result + ')';
                document.getElementById('avatarPreview').style.backgroundRepeat = 'no-repeat';
                document.getElementById('avatarPreview').style.backgroundSize = 'cover';
                document.getElementById('avatarPreview').classList.remove("imageNotSelected");
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>
</html>
