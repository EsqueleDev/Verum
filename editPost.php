<?php
include 'PhpShits/conn.php';
include 'PhpShits/algoritimoBoiola.php';

$postId = $_GET['postId'] ?? null;

if(!$postId){
    die("Post não encontrado");
}

$post = getOnePost($conn, $postId);
$post_error = null;
$post_processed = false;
$postTipo = $post['tipo'] ?? 'texto';
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = trim($_POST['post_title'] ?? '');
    $content = trim($_POST['post_content_text'] ?? '');
    $tags = $_POST['tags'] ?? '';
    $tipo = $_POST['post_type'] ?? 'texto';

    $media = $post['mediaFile'];

    // limpar tags
    $tags_array = explode(',', $tags);
    $tags_array = array_map('trim', $tags_array);
    $tags_array = array_map('strtolower', $tags_array);
    $tags_array = array_unique($tags_array);
    $tags = implode(',', $tags_array);

    // upload de imagem
    if($tipo === "imagem" && isset($_FILES['post_content_image']) && $_FILES['post_content_image']['error'] === UPLOAD_ERR_OK){

        $ext = pathinfo($_FILES['post_content_image']['name'], PATHINFO_EXTENSION);
        $name = uniqid('img_').".".$ext;
        $dest = "uploads/images/".$name;

        move_uploaded_file($_FILES['post_content_image']['tmp_name'], $dest);

        $media = $dest;
    }

    // upload de video
    if($tipo === "video" && isset($_FILES['post_content_video']) && $_FILES['post_content_video']['error'] === UPLOAD_ERR_OK){

        $ext = pathinfo($_FILES['post_content_video']['name'], PATHINFO_EXTENSION);
        $name = uniqid('vid_').".".$ext;
        $dest = "uploads/videos/".$name;

        move_uploaded_file($_FILES['post_content_video']['tmp_name'], $dest);

        $media = $dest;
    }

    $stmt = $conn->prepare("UPDATE post SET titulo=?, conteudo=?, tipo=?, mediaFile=?, tagPost=? WHERE id=?");
    $stmt->bind_param("sssssi", $title, $content, $tipo, $media, $tags, $postId);

    if($stmt->execute()){
        $post_processed = true;
    }else{
        $post_error = "Erro ao atualizar post.";
    }

}

$postTags = [];

if(!empty($post['tagPost'])){
    $postTags = explode(',', $post['tagPost']);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Editar Post</title>
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
        <span class="app-title">Editar Post</span>
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
        <input type="hidden" name="post_type" id="post_type" value="<?= $postTipo ?>">

        <!-- TÍTULO -->
        <input
            type="text"
            name="post_title"
            class="post-title-input"
            value='<?= $post['titulo'] ?>'
            placeholder="Titulo do Post (Clique para digitar)"
        >

        <!-- TIPO -->
        <div class="post-type">
            <span>Criar um post de:</span>
            <div class="post-type-tabs">
                <button class="post-tab" data-tab="texto" id="texto-button" type="button">Texto</button>
                <button class="post-tab" data-tab="imagem" id='imagem-button' type="button">Imagem</button>
                <button class="post-tab" data-tab="video" id='video-button' type="button">Video</button>
            </div>
        </div>

        <!-- CORPO DO POST -->
        <div class="post-content">

            <!-- TEXTO -->
            <textarea
                name="post_content_text"
                class="post-body post-panel"
                data-panel="texto"
                placeholder="Clique e digite o corpo do post."
                style="width: 100%;"
            ><?= $post['conteudo'] ?></textarea>

            <!-- IMAGEM -->
            <div class="media-box post-panel" data-panel="imagem">
                <div class="media-placeholder" style='display: none;' onclick="document.getElementById('post-content-image').click();">
                    <span class="media-plus">+</span>
                    <p>Clique para selecionar UMA foto.</p>
                </div>
                <input type="file" accept="image/*" hidden name="post_content_image" id="post-content-image">
                <?php if($post['tipo'] === 'imagem' && $post['mediaFile']): ?>
                <script>document.getElementById('imagem-button').click();</script>
                <div class="image-preview" id="image-preview" style="display:block;">
                    <img src="<?= $post['mediaFile'] ?>" id="preview-img" style='max-width: 100%;'>
                </div>
                <?php endif; ?>
            </div>

            <!-- VIDEO -->
            <div class="media-box post-panel" data-panel="video">
                <div class="media-placeholder" style='display: none;' onclick="document.getElementById('post-content-video').click();">
                    <span class="media-plus">+</span>
                    <p>Clique para selecionar UM vídeo.</p>
                </div>
                <input type="file" accept="video/*" hidden name="post_content_video" id="post-content-video">
                <?php if($post['tipo'] === 'video' && $post['mediaFile']): ?>
                <div class="video-preview" id="video-preview" style="display:block;">
                    <video controls>
                        <source src="<?= $post['mediaFile'] ?>">
                    </video>
                </div>
                <?php endif; ?>
            </div>
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
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
<script src="create-post.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    
        const tipo = document.getElementById("post_type").value;
    
        const tab = document.querySelector(`[data-tab="${tipo}"]`);
        const panel = document.querySelector(`[data-panel="${tipo}"]`);
    
        if(tab) tab.classList.add("active");
        if(panel) panel.classList.add("active");
    
    });
    let tags = <?= json_encode($postTags) ?>;
    
    const input = document.getElementById("tag-input");
    const container = document.getElementById("tags-container");
    const hidden = document.getElementById("tags-hidden");
    
    tags.forEach(tag => createTag(tag));
    
    hidden.value = tags.join(",");
    
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
