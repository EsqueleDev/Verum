<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'PhpShits/conn.php';
require_once 'PhpShits/userFunctions.php';
require_once 'PhpShits/funcsTags.php';

$post_processed = false;
$post_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =========================
    // USER
    // =========================
    if (!isset($_COOKIE['UserId'])) {
        $post_error = "Usuário não autenticado.";
    }

    $user_id = (int) $_COOKIE['UserId'];

    // =========================
    // TYPE
    // =========================
    $post_type = $_POST['post_type'] ?? 'texto';
    $allowed_types = ['texto', 'imagem', 'video'];

    if (!in_array($post_type, $allowed_types)) {
        $post_type = 'texto';
    }

    // =========================
    // DADOS
    // =========================
    $post_title = trim($_POST['post_title'] ?? '');
    $post_body  = '';
    $media_path = null;

    // =========================
    // TAGS (CORRIGIDO)
    // =========================
    $raw_tags = $_POST['tags'] ?? '';

    $tags_array = array_filter(array_map(function($tag){
        return strtolower(trim($tag));
    }, explode(',', $raw_tags)));

    $tags_id_array = [];
    foreach($tags_array as $tag){
        if(!empty($tag)){
            $id = createTag($conn, $tag);

            if($id){
                $tags_id_array[] = $id;
            }
        }
    }

    $tags = !empty($tags_id_array) ? implode(',', $tags_id_array) : null;

    // =========================
    // CONTEÚDO
    // =========================
    if ($post_type === 'texto') {

        $post_body = trim($_POST['post_content_text'] ?? '');

    } elseif ($post_type === 'imagem') {

        $post_body = trim($_POST['post_content_text'] ?? '');

        if (!empty($_FILES['post_content_image']['name'])) {

            $upload = processImageUpload($_FILES['post_content_image']);

            if ($upload['success']) {
                $media_path = $upload['path'];
            } else {
                $post_error = $upload['error'];
            }

        } else {
            $post_error = "Selecione uma imagem.";
        }

    } elseif ($post_type === 'video') {

        $post_body = trim($_POST['post_content_text'] ?? '');

        if (!empty($_FILES['post_content_video']['name'])) {

            $upload = processVideoUpload($_FILES['post_content_video']);

            if ($upload['success']) {
                $media_path = $upload['path'];
            } else {
                $post_error = $upload['error'];
            }

        } else {
            $post_error = "Selecione um vídeo.";
        }
    }

    // =========================
    // INSERT
    // =========================
    if (!$post_error) {

        $stmt = $conn->prepare("
            INSERT INTO post 
            (userId, tipo, titulo, conteudo, mediaFile, tagPost) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssss",
            $user_id,
            $post_type,
            $post_title,
            $post_body,
            $media_path,
            $tags
        );

        if ($stmt->execute()) {
            $post_processed = true;
        } else {
            $post_error = "Erro SQL: " . $stmt->error;
        }

        $stmt->close();
    }
}

// =========================
// UPLOAD
// =========================

function processImageUpload($file) {
    return processMediaUpload($file, 'uploads/images/', ['image/jpeg','image/png','image/webp'], 10*1024*1024);
}

function processVideoUpload($file) {
    return processMediaUpload($file, 'uploads/videos/', ['video/mp4','video/webm'], 50*1024*1024);
}

function processMediaUpload($file, $dir, $types, $max) {

    $res = ['success'=>false,'path'=>null,'error'=>null];

    if ($file['size'] > $max) {
        $res['error'] = "Arquivo muito grande";
        return $res;
    }

    if (!class_exists('finfo')) {
        $res['error'] = "Fileinfo não ativo";
        return $res;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);

    if (!in_array($mime, $types)) {
        $res['error'] = "Tipo inválido";
        return $res;
    }

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid() . '.' . $ext;
    $path = $dir . $name;

    if (move_uploaded_file($file['tmp_name'], $path)) {
        $res['success'] = true;
        $res['path'] = $path;
    } else {
        $res['error'] = "Erro upload";
    }

    return $res;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Criar Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
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

        function previewVideo(input) {
            if (input.files && input.files[0]) {
                const video = document.getElementById('preview-video');
                const placeholder = input.parentElement.querySelector('.media-placeholder');
                const preview = document.getElementById('video-preview');
                
                const url = URL.createObjectURL(input.files[0]);
                video.src = url;
                
                if (placeholder) placeholder.style.display = 'none';
                preview.style.display = 'block';
            }
        }

        function removeVideoPreview() {
            const input = document.getElementById('post-content-video');
            const placeholder = input.parentElement.querySelector('.media-placeholder');
            const preview = document.getElementById('video-preview');
            const video = document.getElementById('preview-video');
            
            input.value = ''; // Clear file input
            URL.revokeObjectURL(video.src);
            video.src = '';
            preview.style.display = 'none';
            if (placeholder) placeholder.style.display = 'flex';
        }

        // ============================================
        // ADD EVENT LISTENERS FOR PREVIEW
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('post-content-image');
            const videoInput = document.getElementById('post-content-video');
            
            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    previewImage(this);
                });
            }
            
            if (videoInput) {
                videoInput.addEventListener('change', function() {
                    previewVideo(this);
                });
            }
        });
    </script>
</head>
<body>
<div class='hideMobile'></div>
<div class="app-container">

    <!-- HEADER -->
    <header class="app-header post-header-bar">
        <button class="icon-btn" onclick="window.history.back();"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg></button>
        <span class="app-title">Criar Post</span>
        <span></span>
    </header>

    <!-- MENSAGENS DE FEEDBACK -->
    <?php if ($post_error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($post_error); ?></div>
    <?php elseif ($post_processed): ?>
        <div class="alert alert-success"><script>window.location.href = 'home';</script></div>
    <?php endif; ?>

    <!-- CONTEÚDO -->
    <form method="POST" class="create-post" enctype="multipart/form-data">
        
        <!-- HIDDEN INPUT FOR POST TYPE -->
        <input type="hidden" name="post_type" id="post_type" value="texto">

        <!-- TÍTULO -->
        <input
            type="text"
            name="post_title"
            class="post-title-input"
            placeholder="Titulo do Post (Clique para digitar)"
        >

        <!-- TIPO -->
        <div class="post-type">
            <span>Criar um post de:</span>
            <div class="post-type-tabs">
                <button class="post-tab active" data-tab="texto" type="button">Texto</button>
                <button class="post-tab" data-tab="imagem" type="button">Imagem</button>
                <button class="post-tab" data-tab="video" type="button">Video</button>
            </div>
        </div>

        <!-- CORPO DO POST -->
        <div class="post-content">

            <!-- TEXTO -->
            <textarea
                name="post_content_text"
                class="post-body post-panel active"
                data-panel="texto"
                placeholder="Clique e digite o corpo do post."
                style="width: 100%;"
            ></textarea>

            <!-- IMAGEM -->
            <div class="media-box post-panel" data-panel="imagem">
                <div class="media-placeholder" onclick="document.getElementById('post-content-image').click(); previewImage(input);">
                    <span class="media-plus">+</span>
                    <p>Clique para selecionar UMA foto.</p>
                </div>
                <input type="file" accept="image/*" hidden name="post_content_image" id="post-content-image">
                <div id="image-preview" class="media-preview" style="display: none;">
                    <img id="preview-img" src="" alt="Preview">
                    <button type="button" class="remove-media" onclick="removeImagePreview();">×</button>
                </div>
            </div>

            <!-- VIDEO -->
            <div class="media-box post-panel" data-panel="video">
                <div class="media-placeholder" onclick="document.getElementById('post-content-video').click();">
                    <span class="media-plus">+</span>
                    <p>Clique para selecionar UM vídeo.</p>
                </div>
                <input type="file" accept="video/*" hidden name="post_content_video" id="post-content-video">
                <div id="video-preview" class="media-preview" style="display: none;">
                    <video id="preview-video" controls></video>
                    <button type="button" class="remove-media" onclick="removeVideoPreview();">×</button>
                </div>
            </div>
            <br>
            <div id="tags-container"></div>
            <br>
            <div class="tag-bosta form-group">
                <span style='display: grid; grid-template-collumns: 2fr 1fr;'>
                    <input 
                        type="text" 
                        id="tag-input"
                        placeholder="Digite uma tag e aperte ENTER"
                        maxlength="11"
                        required
                    >
                    <br>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </span>
                <input type="hidden" name="tags" id="tags-hidden">
            </div>
        </div>
        <br>
    
        <!-- BOTÃO FIXO -->
        <div class="post-footer">
        </div>
    </form>
</div>
<script src="create-post.js"></script>
<script>
    let tags = [];
    
    const input = document.getElementById("tag-input");
    const container = document.getElementById("tags-container");
    const hidden = document.getElementById("tags-hidden");
    
    input.addEventListener("keydown", function(e){
    
        if(e.key === "Enter"){
            e.preventDefault();
    
            let tag = input.value.trim().toLowerCase();
    
            if(tag === "") return;
    
            tag = tag.replace(/\s+/g, "");
    
            if(tags.includes(tag)) return;
    
            tags.push(tag);
    
            createTag(tag);
    
            input.value = "";
    
            hidden.value = tags.join(",");
            
            input.removeAttribute('required');
        }
    
    });
    
    document.querySelector("form").addEventListener("submit", () => {
    
        let tag = input.value.trim().toLowerCase();
    
        if(tag !== ""){
            tag = tag.replace(/\s+/g, "");
    
            if(!tags.includes(tag)){
                tags.push(tag);
                createTag(tag);
            }
        }
    
        hidden.value = tags.join(",");
    });
    
    function createTag(tag){
    
        const div = document.createElement("div");
        div.className = "tag";
        div.innerText = "#"+tag;
    
        const remove = document.createElement("span");
        remove.innerText = " ×";
    
        remove.onclick = function(){
    
            tags = tags.filter(t => t !== tag);
            div.remove();
    
            hidden.value = tags.join(",");
        }
    
        div.appendChild(remove);
        container.appendChild(div);
    }
</script>
</body>
</html>
