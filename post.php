<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'PhpShits/conn.php';
    include 'PhpShits/postFunctions.php';
    include 'PhpShits/userFunctions.php';
    include 'PhpShits/funcsTags.php';
    
    $me = getUserInfo($conn, $_COOKIE['UserId']);
    function quebrarPalavrasGrandes($texto, $limite = 30) {
        return preg_replace('/(\S{'.$limite.'})/u', '$1 -<wbr>', $texto);
    }
    
    $post = loadOnePost($conn, $_GET['id']);
    $Author = getUserInfo($conn, $post['userId']);
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $UserId = $me['id'];
        $PostId = $_GET['id'];
        $Text = $_POST['Text'];
        insertAnCommentary($conn, $UserId, $PostId, $Text);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">Post</span>
        <span></span>
    </header>
    <article class="post-card">
        <div class="post-header" style='margin-bottom: 12px;'><a href='profile.php?id=<?= $post['userId'] ?>'>
            <div class="avatar" style="background: url(<?= htmlspecialchars($Author['profilePic']) ?>); background-repeat: no-repeat; background-size: cover;"></div>
            <div class="post-user">
                <strong><?= htmlspecialchars($Author['username']) ?></strong>
                <span><?= htmlspecialchars($post['postTime'] ?? '') ?></span>
            </div></a>
            <a href='postOptions.php?postid=<?= $post['id'] ?>&isMine=<?= $isMine ?>' class='post-options-button'>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="var(--on-background)"><path d="M240-400q-33 0-56.5-23.5T160-480q0-33 23.5-56.5T240-560q33 0 56.5 23.5T320-480q0 33-23.5 56.5T240-400Zm240 0q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm240 0q-33 0-56.5-23.5T640-480q0-33 23.5-56.5T720-560q33 0 56.5 23.5T800-480q0 33-23.5 56.5T720-400Z"/></svg>
            </a>
        </div>

        <p class="post-text">
            <h2><?= quebrarPalavrasGrandes(htmlspecialchars($post['titulo'] ?? '')) ?></h2><br>
            <?php if($post['tipo'] == 'texto'): ?>
                <?= nl2br(quebrarPalavrasGrandes(htmlspecialchars($post['conteudo'] ?? ''))) ?>
            <?php endif; ?>
        </p>
        <?php if($post['tipo'] == 'imagem'): ?>
            <img src="<?= htmlspecialchars($post['mediaFile'] ?? '') ?>" onclick="openImageViwer('<?= htmlspecialchars($post['mediaFile'] ?? '') ?>');" loading="lazy" >
        <?php endif; ?>
        <?php if($post['tipo'] == 'video'): ?>
            <video width="320" height="240" controls>
                <source src="<?= htmlspecialchars($post['mediaFile'] ?? '') ?>">
                Your browser does not support the video tag.
            </video>
        <?php endif; ?>
        <br>
        <div class='tag-box'>
        <?php
        $tags = explode(',', $post['tagPost']);
        
        if(!empty($tags) && getTagName($conn, $tags[0], "PT_BR") != null){
            foreach($tags as $tagId){
                $tagName = getTagName($conn, (int)$tagId, "PT_BR");
                echo "<span class='tag'>#$tagName</span>";
            }
        }
        ?>
        </div>
        <br>
        <br>
    </article> 
    <!-- seção de comentarios -->
    <hr>
    <center style='padding: 12px;'><p>Comentarios:</p></center>
    <center><form method='POST'>
        <textarea
            name="Text"
            class="post-body post-panel active"
            data-panel="texto"
            placeholder="Clique e Digite um Comentario."
            style="width: 90%;"
        ></textarea><br>
        <button class='btn btn-primary' style='width: 90%;'>Publicar</button>
    </form></center>
    <br><br>
    <?php $comments = getCommentsFromPost($conn, $post['id']); ?>
    <?php foreach ($comments as $comment): ?>
        <?php $Author = getUserInfo($conn, $comment['UserId']); ?>
        <hr>
        <div class='comment'>
            <article class="post-card">
                <div class="post-header" style='margin-bottom: 12px;'><a href='profile.php?id=<?= $comment['UserId'] ?>'>
                    <div class="avatar" style="background: url(<?= htmlspecialchars($Author['profilePic']) ?>); background-repeat: no-repeat; background-size: cover;"></div>
                    <div class="post-user">
                        <strong><?= htmlspecialchars($Author['username']) ?></strong>
                    </div></a>
                </div>
                <span class='post-text'>
                    <?= $comment['Texto'] ?>
                </span>
            </article>
        </div>
    <?php endforeach; ?>
</div>
<!-- visualizador de imagem -->
<div class="image-full" id="image-full">
    <div class="imagem-top" onclick="closeImageViwer();">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
    </div>
    <div class="imagemContainer">
        <img id="imagemVisu" loading="lazy" >
    </div>
    <div class="imagem-bottom">
        <center onclick="zoomInImageViwer()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Zm-40-60v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z"/></svg></center>
        <center><a href='errorPage.php?id=1'><span style="cursor: block;">Ver os Comentarios</span></a></center>
        <center onclick="zoomOutImageViwer()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400ZM280-540v-80h200v80H280Z"/></svg></center>
    </div>
</div>
<script>
const visualizador = document.getElementById("image-full");
const imagemVisu = document.getElementById("imagemVisu");

let zoomImage = 1;
let isDragging = false;
let startX = 0, startY = 0;
let currentX = 0, currentY = 0;

function openImageViwer(imageSrc){
    visualizador.style.display = 'grid';
    zoomImage = 1;
    imagemVisu.src = imageSrc;
    resetPosition();
    applyTransform();
}

function closeImageViwer(){
    visualizador.style.display = 'none';
    imagemVisu.src = '';
    resetPosition();
}

function zoomInImageViwer(){
    zoomImage += 0.5;
    applyTransform();
}

function zoomOutImageViwer(){
    zoomImage = Math.max(0.5, zoomImage - 0.5);
    applyTransform();
}

function applyTransform(){
    imagemVisu.style.transform =
        `translate(-50%, -50%) translate(${currentX}px, ${currentY}px) scale(${zoomImage})`;
}

function resetPosition(){
    currentX = 0;
    currentY = 0;
}

/* DRAG */
function startDrag(e){
    isDragging = true;
    imagemVisu.classList.add("dragging");
    const p = e.touches ? e.touches[0] : e;
    startX = p.clientX - currentX;
    startY = p.clientY - currentY;
}

function dragMove(e){
    if (!isDragging) return;
    const p = e.touches ? e.touches[0] : e;
    currentX = p.clientX - startX;
    currentY = p.clientY - startY;
    applyTransform();
}

function endDrag(){
    isDragging = false;
    imagemVisu.classList.remove("dragging");
}

imagemVisu.addEventListener("mousedown", startDrag);
imagemVisu.addEventListener("mousemove", dragMove);
imagemVisu.addEventListener("mouseup", endDrag);
imagemVisu.addEventListener("mouseleave", endDrag);

imagemVisu.addEventListener("touchstart", startDrag, { passive: false });
imagemVisu.addEventListener("touchmove", dragMove, { passive: false });
imagemVisu.addEventListener("touchend", endDrag);
</script>
</body>
</html>