<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/albumFuncs.php';
    $me = $_COOKIE['UserId'];
    $albumId = $_GET['albumid'];
    
    $imAreADM = checkIfImAreTheADM($conn, $me, $albumId);
    
    $pics = getAlbumPics($conn, $albumId);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Album</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=2">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">Fotos</span>
        <span></span>
    </header>
    
    <div class='pics'>
        <?php if($imAreADM): ?>
            <div class='pic'>
                <a href='addPic.php'><img src='https://placehold.co/400?text=Adicionar'></a>
            </div>
        <?php endif; ?>
        <?php foreach($pics as $pic): ?>
            <div class='pic'>
                <img src='<?= $pic['url'] ?>' onclick="openImageViwer('<?= $pic['url'] ?>')">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="image-full" id="image-full">
    <div class="imagem-top" onclick="closeImageViwer();">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
    </div>
    <div class="imagemContainer">
        <img id="imagemVisu" loading="lazy" >
    </div>
    <div class="imagem-bottom">
        <center onclick="zoomInImageViwer()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Zm-40-60v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z"/></svg></center>
        <center><span style="cursor: block;"></span></center>
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