<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "PhpShits/conn.php";
    include "PhpShits/albumFuncs.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $UserId = $_COOKIE['UserId'];
        $AlbumName = $_POST['AlbumName'];
        createAlbum($conn, $AlbumName, $UserId);
        
        $newAlbum = getOneAlbumWithName($conn, $AlbumName, $UserId);
        
        addOnePicToAnAlbum($conn, $newAlbum);
        
        echo "<script>window.location.href = 'my-albuns.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Album</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">Criar Album</span>
        <span></span>
    </header>
    <form method="POST" enctype="multipart/form-data"><br>
        <div class="form-group">
            <input name='AlbumName' type='text' placeholder="Nome do Album">
        </div><br>
        <div class="post-content">
            <div class="media-box post-panel active" data-panel="imagem">
                <div class="media-placeholder" onclick="document.getElementById('post-content-image').click(); previewImage(input);">
                    <span class="media-plus">+</span>
                    <p>É preciso adicionar uma foto para criar o album.</p><br>
                </div>
                <input type="file" accept="image/*" hidden name="image" id="post-content-image">
                <div id="image-preview" class="media-preview" style="display: none;">
                    <img id="preview-img" src="" alt="Preview">
                    <button type="button" class="remove-media" onclick="removeImagePreview();">×</button>
                </div>
            </div>
        </div>
        <center><button type="submit" class='btn btn-primary'>Criar</button></center>
    </form>
</div>
<script>
    function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const placeholder = input.parentElement.querySelector('.media-placeholder');
                    const preview = document.getElementById('image-preview');
                    const previewImg = document.getElementById('preview-img');
                    
                    if (placeholder) placeholder.style.display = 'none';
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImagePreview() {
            const input = document.getElementById('post-content-image');
            const placeholder = input.parentElement.querySelector('.media-placeholder');
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            
            input.value = ''; // Clear file input
            previewImg.src = '';
            preview.style.display = 'none';
            if (placeholder) placeholder.style.display = 'flex';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('post-content-image');
            
            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    previewImage(this);
                });
            }
        });
</script>
</body>
</html>