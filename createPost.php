<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'PhpShits/conn.php';
require_once 'PhpShits/userFunctions.php';

// ============================================
// PROCESSAR POST QUANDO SUBMETIDO
// ============================================

$post_processed = false;
$post_error = null;
$post_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ------------------------------------------------
    // 1. DETECTAR TIPO DE POST
    // ------------------------------------------------
    $post_type = $_POST['post_type'] ?? 'texto';
    
    // Validar tipo de post
    $allowed_types = ['texto', 'imagem', 'video'];
    if (!in_array($post_type, $allowed_types)) {
        $post_type = 'texto';
    }
    
    // ------------------------------------------------
    // 2. CAPTURAR DADOS DO POST
    // ------------------------------------------------
    $post_title = trim($_POST['post_title'] ?? '');
    $post_body = '';
    $media_filename = null;
    $media_path = null;
    
    // ------------------------------------------------
    // 3. PROCESSAR CONFORME TIPO DE POST
    // ------------------------------------------------
    $tags = $_POST['tags'] ?? '';
    
    $tags_array = explode(',', $tags);
    $tags_array = array_map('trim', $tags_array);
    $tags_array = array_map('strtolower', $tags_array);
    $tags_array = array_unique($tags_array);
    
    $tags = implode(',', $tags_array);
    if ($post_type === 'texto') {
        // Post de texto - capturar corpo
        $post_body = trim($_POST['post_content_text'] ?? '');
        
    } elseif ($post_type === 'imagem') {
        // Post de imagem - processar upload
        $post_body = trim($_POST['post_content_text'] ?? ''); // opcional caption
        
        if (isset($_FILES['post_content_image']) && $_FILES['post_content_image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = processImageUpload($_FILES['post_content_image']);
            if ($upload_result['success']) {
                $media_filename = $upload_result['filename'];
                $media_path = $upload_result['path'];
            } else {
                $post_error = $upload_result['error'];
            }
        } else {
            $post_error = 'Nenhuma imagem selecionada para post de imagem.';
        }
        
    } elseif ($post_type === 'video') {
        // Post de vídeo - processar upload
        $post_body = trim($_POST['post_content_text'] ?? ''); // opcional caption
        
        if (isset($_FILES['post_content_video']) && $_FILES['post_content_video']['error'] === UPLOAD_ERR_OK) {
            $upload_result = processVideoUpload($_FILES['post_content_video']);
            if ($upload_result['success']) {
                $media_filename = $upload_result['filename'];
                $media_path = $upload_result['path'];
            } else {
                $post_error = $upload_result['error'];
            }
        } else {
            $post_error = 'Nenhum vídeo selecionado para post de vídeo.';
        }
    }
    
    // ------------------------------------------------
    // 4. PREPARAR DADOS PARA SQL (USAR NO SEU CÓDIGO)
    // ------------------------------------------------
    $post_data = [
        'type'          => $post_type,
        'title'         => $post_title,
        'body'          => $post_body,
        'media_filename'=> $media_filename,
        'media_path'    => $media_path,
        'user_id'       => $_COOKIE['UserId'] ?? null,
        'created_at'    => date('Y-m-d H:i:s')
    ];
    
    // ------------------------------------------------
    // 5. EXEMPLO: INSERIR NO BANCO (DESCOMENTE E ADAPTE)
    // ------------------------------------------------
    
    if (!$post_error && $conn) {
        $stmt = $conn->prepare("INSERT INTO post (userId, tipo, titulo, conteudo, mediaFile, tagPost) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", 
            $post_data['user_id'],
            $post_data['type'],
            $post_data['title'],
            $post_data['body'],
            $post_data['media_path'],
            $tags
        );
        if ($stmt->execute()) {
            $post_processed = true;
            // Redirecionar ou mostrar sucesso
        }
        $stmt->close();
    }
    
    
    if (!$post_error && !empty($post_title)) {
        $post_processed = true;
    }
}

// ============================================
// FUNÇÕES DE PROCESSAMENTO DE IMAGEM/VIDEO
// ============================================

function processImageUpload($file) {
    $upload_dir = 'uploads/images/';
    return processMediaUpload($file, $upload_dir, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 10 * 1024 * 1024);
}

function processVideoUpload($file) {
    $upload_dir = 'uploads/videos/';
    return processMediaUpload($file, $upload_dir, ['video/mp4', 'video/webm', 'video/ogg'], 50 * 1024 * 1024);
}

function processMediaUpload($file, $upload_dir, $allowed_types, $max_size) {
    $result = ['success' => false, 'filename' => null, 'path' => null, 'error' => null];
    
    // Validar tamanho
    if ($file['size'] > $max_size) {
        $result['error'] = 'Arquivo muito grande. Tamanho máximo: ' . ($max_size / 1024 / 1024) . 'MB';
        return $result;
    }
    
    // Validar tipo MIME
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);
    if (!in_array($mime_type, $allowed_types)) {
        $result['error'] = 'Tipo de arquivo não permitido.';
        return $result;
    }
    
    // Criar diretório se não existir
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Gerar nome único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('post_') . '_' . time() . '.' . $extension;
    $destination = $upload_dir . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $result['success'] = true;
        $result['filename'] = $filename;
        $result['path'] = $destination;
    } else {
        $result['error'] = 'Erro ao fazer upload do arquivo.';
    }
    
    return $result;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Criar Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php">
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
            <div class="tag-box form-group">
                <input 
                    type="text" 
                    id="tag-input"
                    placeholder="Digite uma tag e aperte ENTER"
                >
            
                <div id="tags-container"></div>
            
                <input type="hidden" name="tags" id="tags-hidden">
            </div>
        </div>
        <br>
    
        <!-- BOTÃO FIXO -->
        <div class="post-footer">
            <button type="submit" class="btn btn-primary">Publicar</button>
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
        }
    
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
