<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/algoritimoBoiola.php';
    include 'PhpShits/userFunctions.php';
    include 'PhpShits/connectionsUsersFuncs.php';

    $me = getUserInfo($conn, isset($_COOKIE['UserId']) ? $_COOKIE['UserId'] : 0);
    $Profile = getUserInfo($conn, isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : ($me ? $me['id'] : 0));
    
    function quebrarPalavrasGrandes($texto, $limite = 30) {
        return preg_replace('/(\S{'.$limite.'})/u', '$1<wbr>', $texto);
    }
    
    if(isset($_GET['request']) && $_GET['request'] == 'sended'){
        sendAFriendRequest($conn, $me['id'], $Profile['id']);
        echo "<script>window.location.href = document.referrer;</script>";
    }

    else if(isset($_GET['request']) && $_GET['request'] == 'accept'){
        changeFriendRequestStatus($conn, $Profile['id'], $me['id'], 'accepted');
        echo "<script>window.location.href = document.referrer;</script>";
    }

    else if(isset($_GET['request']) && $_GET['request'] == 'reject'){
        changeFriendRequestStatus($conn, $Profile['id'], $me['id'], 'rejected');
        echo "<script>window.location.href = document.referrer;</script>";
    }
    
    else if(isset($_GET['request']) && $_GET['request'] == 'retry'){
        retryFriendRequestStatus($conn, $Profile['id'], $me['id']);
        echo "<script>window.location.href = document.referrer;</script>";
    }
    
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?i=<?= rand(1, 1000) ?>">
    <link rel="stylesheet" href="colors.php">
</head>
<body>
<div class='hideMobile'></div>
<div class="app-container">

    <!-- HEADER -->
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back();"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg></button>
        <span class="app-title">Perfil</span>
        <button style="cursor: block;" class="icon-btn"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg></button>
    </header>

    <!-- CONTEÚDO -->
    <div class="feed">

        <!-- PERFIL -->
        <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
            <div class="user-mini-info">
                <div class="avatar" style="width:90px; height:90px; background: url(<?= htmlspecialchars($Profile['profilePic'] ?? '') ?>); background-repeat: no-repeat; background-size: cover;"></div>
                <span>
                    <strong><?= htmlspecialchars($Profile['username']) ?></strong><br>
                    <span style="font-size:13px; color:var(--on-button);">
                        <?= htmlspecialchars($Profile['biography']) ?>
                    </span>
                </span>
            </div>
            
            <!-- BOTÕES -->
            <?php if($me['id'] != $Profile['id']): ?>
                <?php
                $connection = getUserConnectionInfo($conn, $me['id'], $Profile['id']);

                $status = $connection['status'] ?? null;
                $didISend = $connection['didISendThisRequest'] ?? false;
                ?>

                <?php if (is_null($connection)): ?>

                    <!-- NÃO EXISTE RELAÇÃO -->
                    <a href="profile.php?id=<?= $Profile['id'] ?>&request=sended">
                        <button class="btn btn-secondary">Enviar Pedido</button>
                    </a>

                <?php elseif ($status === 'pendend' && $didISend): ?>

                    <!-- PEDIDO ENVIADO POR MIM -->
                    <button class="btn btn-secondary" disabled>Pedido Enviado</button>

                <?php elseif ($status === 'rejected' && !$didISend): ?>

                    <!-- PEDIDO ENVIADO POR MIM -->
                    <div class="button-group">
                        <button class="btn btn-secondary" disabled>Bloquear?</button>
                        <a href='profile.php?id=<?= $Profile['id'] ?>&request=retry'><button class="btn btn-primary" >Dar outra chance</button></a>
                    </div>

                <?php elseif (($status === 'rejected' || $status === 'blocked') && $didISend): ?>

                    <!-- PEDIDO ENVIADO POR MIM -->
                    <button class="btn btn-primary" disabled>Não incomode esta pessoa</button>

                
                <?php elseif ($status === 'pendend' && !$didISend): ?>

                    <!-- PEDIDO RECEBIDO -->
                    <center><div class="button-group">
                        <a href="profile.php?id=<?= $Profile['id'] ?>&request=reject">
                            <button class="btn btn-secondary">Recusar</button>
                        </a>
                        <a href="profile.php?id=<?= $Profile['id'] ?>&request=accept">
                            <button class="btn btn-primary">Aceitar</button>
                        </a>
                    </div></center>
                    <!-- aqui futuramente: aceitar / recusar -->

                <?php elseif ($status === 'accepted'): ?>

                    <!-- AMIZADE ACEITA -->
                    <button class="btn btn-primary">
                        Mandar Mensagem
                    </button>

                <?php endif; ?>
            <?php endif; ?>

            <!-- AÇÕES -->
            <div class="profile-tabs">
                <button class="icon-btn button-profile-selected" style="padding: 8px;" onclick="switchTab('posts')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg></button>
                <button class="icon-btn" style="padding: 8px;" onclick="switchTab('albuns')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"/></svg></button>
                <button class="icon-btn" style="padding: 8px;" onclick="switchTab('saved')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM247-527q-47-47-47-113t47-113q47-47 113-47t113 47q47 47 47 113t-47 113q-47 47-113 47t-113-47Zm466 0q-47 47-113 47-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113q0 66-47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm296.5-343.5Q440-607 440-640t-23.5-56.5Q393-720 360-720t-56.5 23.5Q280-673 280-640t23.5 56.5Q327-560 360-560t56.5-23.5ZM360-240Zm0-400Z"/></svg></button>
                <button class="icon-btn" style="padding: 8px;" onclick="switchTab('about')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M367-527q-47-47-47-113t47-113q47-47 113-47t113 47q47 47 47 113t-47 113q-47 47-113 47t-113-47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm296.5-343.5Q560-607 560-640t-23.5-56.5Q513-720 480-720t-56.5 23.5Q400-673 400-640t23.5 56.5Q447-560 480-560t56.5-23.5ZM480-640Zm0 400Z"/></svg></button>
            </div>
        </div>

        <!-- TABS -->
        <div id="tab-posts" class="tab-content active">
            <center>
                <h2 style="font-size:24px; font-weight:500;">Posts:</h2>
            </center><br>

            <!-- POST -->
            <hr>
            <?php $posts = algoritmoDeDadosEspecificos($conn, $Profile['id']) ?>
            <?php foreach($posts as $post): ?>
                <br>
                <article class="post-card">
                    <div class="post-header">
                        <div class="avatar" style="background: url(<?= htmlspecialchars($Profile['profilePic'] ?? '') ?>); background-repeat: no-repeat; background-size: cover;"></div>
                        <div class="post-user">
                            <strong><?= htmlspecialchars($Profile['username']) ?></strong>
                            <span><?= htmlspecialchars($post['postTime'] ?? '') ?></span>
                        </div>
                    </div>

                    <p class="post-text">
                        <h2><?= htmlspecialchars($post['titulo'] ?? '') ?></h2>
                        <?php if($post['tipo'] == 'texto'): ?>
                            <?= nl2br(quebrarPalavrasGrandes(htmlspecialchars($post['conteudo'] ?? ''))) ?>
                        <?php endif; ?>
                    </p>
                    <?php if($post['tipo'] == 'imagem'): ?>
                        <img src="<?= htmlspecialchars($post['mediaFile'] ?? '') ?>" onclick="openImageViwer('<?= htmlspecialchars($post['mediaFile'] ?? '') ?>');">
                    <?php endif; ?>
                    <?php if($post['tipo'] == 'video'): ?>
                        <video width="320" height="240" controls>
                            <source src="<?= htmlspecialchars($post['mediaFile'] ?? '') ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                    <br>
                    <?php
                        $tags = explode(',', $post['tagPost']);
                        if($tags[0] != 0){
                            foreach($tags as $tag){
                                echo "<span class='tag'>#$tag</span>&nbsp;";
                            }
                        }
                    ?>
                    <?php
                        if($post['userId'] == $me['id']){
                            echo "<a href='editPost.php?postId=" . $post['id'] . "'><span class='edit'>Opções do post</span></a>";
                        }
                    ?>
                </article>
                <br>
                <hr>
            <?php endforeach ?>
        </div>

        <div id="tab-albuns" class="tab-content">
            <center>
                <h2 style="font-size:24px; font-weight:500;">Albums:</h2>
            </center><br>
            <hr>
            <!-- Albums content will be added later -->
        </div>

        <div id="tab-saved" class="tab-content">
            <center>
                <h2 style="font-size:24px; font-weight:500;">Amigos:</h2>
            </center><br>
            <hr>
            <?php $userFriends = getAllFriendFromUser($conn, $Profile['id']) ?>
            <div class="suggestions" style="padding: 12px;">    
                <?php foreach($userFriends as $friend): ?>
                    <?php $friend = getUserInfo($conn, $friend['friend_id']) ?>
                    <a href="profile.php?id=<?= $friend['id'] ?>"><div class="suggestion-card">
                        <div class="suggest-avatar avatar-a" style="background: url(<?= $friend['profilePic'] ?>);  background-repeat: no-repeat;  background-size: cover;"></div>
                        <span><?= $friend['username'] ?></span>
                    </div></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="tab-about" class="tab-content">
            <center>
                <h2 style="font-size:24px; font-weight:500;">Sobre:</h2>
            </center><br>
            <hr>
            <div class="two-options" id="about-options">
                <div class="a-option" onclick="showAboutSection('gostos')">
                    <span class="title">Gostos Pessoais</span>
                    <svg class="arrow" xmlns="http://www.w3.org/2000/svg"
                        height="24px"
                        viewBox="0 -960 960 960"
                        width="24px"
                        fill="#FFFFFF">
                        <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                    </svg>
                </div>
                <div class="a-option" onclick="showAboutSection('spotify')">
                    <span class="title">Informações do Spotify</span>
                    <svg class="arrow" xmlns="http://www.w3.org/2000/svg"
                        height="24px"
                        viewBox="0 -960 960 960"
                        width="24px"
                        fill="#FFFFFF">
                        <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Gostos Pessoais Section -->
            <div id="about-gostos" class="about-detail" style="display: none;">
                <div class="about-header">
                    <button class="icon-btn" onclick="hideAboutSection()">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                            <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
                        </svg>
                    </button>
                    <h2 style="font-size:20px; font-weight:500;">Gostos Pessoais</h2>
                </div>
                <hr>
                <?php $userLikes = getUserLikes($conn, $Profile['id']); ?>
                
                    <div style="padding: 12px;">
                        <h4>Tags Seguidas:</h4>
                    </div>
                    <div class="likes-container">
                        <?php if (count($userLikes) > 0): ?> 
                            <?php foreach($userLikes as $like): ?>
                                <span class="like-chip"><?= htmlspecialchars($like['nome']) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="padding: 16px; text-align: center; color: var(--on-surface);">
                                Este usuário ainda não selecionou seus gostos pessoais.
                            </p>
                        <?php endif; ?>
                    </div>
            </div>
            
            <!-- Informações do Spotify Section -->
            <div id="about-spotify" class="about-detail" style="display: none;">
                <div class="about-header">
                    <button class="icon-btn" onclick="hideAboutSection()">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                            <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
                        </svg>
                    </button>
                    <h2 style="font-size:20px; font-weight:500;">Informações do Spotify</h2>
                </div>
                <hr>
                <p style="padding: 16px; text-align: center; color: var(--on-surface);">
                    Em breve: integração com Spotify
                </p>
            </div>
        </div>

        <script>
        // Tab order for swipe navigation
        const tabOrder = ['posts', 'albuns', 'saved', 'about'];
        let currentTabIndex = 0;
        
        // Initialize current tab index
        tabOrder.forEach((tab, index) => {
            const tabEl = document.getElementById('tab-' + tab);
            if (tabEl && tabEl.style.display !== 'none') {
                currentTabIndex = index;
            }
        });
        
        // Swipe detection variables
        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50;
        
        // Touch event handlers for swipe
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
        
        function handleSwipe() {
            const swipeDistance = touchEndX - touchStartX;
            
            if (Math.abs(swipeDistance) < minSwipeDistance) return;
            
            if (swipeDistance > 0 && currentTabIndex > 0) {
                // Swipe right - go to previous tab
                switchTab(tabOrder[currentTabIndex - 1], 'right');
                currentTabIndex--;
            } else if (swipeDistance < 0 && currentTabIndex < tabOrder.length - 1) {
                // Swipe left - go to next tab
                switchTab(tabOrder[currentTabIndex + 1], 'left');
                currentTabIndex++;
            }
        }
        
        function switchTab(tabName, direction) {
            hideAboutSection();
            // Determine animation direction if not specified
            const currentIndex = currentTabIndex;
            const newIndex = tabOrder.indexOf(tabName);
            const animDirection = newIndex > currentIndex ? 'left' : 'right';
            
            // Get the current visible tab and apply exit animation
            const allTabs = document.querySelectorAll('.tab-content');
            allTabs.forEach(function(tab) {
                if (tab.style.display !== 'none') {
                    tab.classList.remove('active');
                    tab.style.display = 'none';
                }
            });
            
            // Show selected tab with animation
            const selectedTab = document.getElementById('tab-' + tabName);
            if (selectedTab) {
                selectedTab.style.display = 'block';
                selectedTab.classList.add('active');
                // Reset animation
                selectedTab.style.animation = 'none';
                selectedTab.offsetHeight; /* trigger reflow */
                selectedTab.style.animation = 'fadeSlideIn 0.3s ease-out forwards';
            }
            
            // Update current tab index
            currentTabIndex = newIndex;
            
            // Update button styles
            var buttons = document.querySelectorAll('.profile-tabs .icon-btn');
            buttons.forEach(function(btn) {
                btn.classList.remove('button-profile-selected');
            });
            
            // Find and highlight the correct button
            const tabIndex = tabOrder.indexOf(tabName);
            if (buttons[tabIndex]) {
                buttons[tabIndex].classList.add('button-profile-selected');
            }
        }
        
        // About section navigation
        function showAboutSection(section) {
            const aboutOptions = document.getElementById('about-options');
            const gostosSection = document.getElementById('about-gostos');
            const spotifySection = document.getElementById('about-spotify');
            
            // Hide the options menu
            if (aboutOptions) {
                aboutOptions.style.display = 'none';
            }
            
            // Show the appropriate section
            if (section === 'gostos' && gostosSection) {
                gostosSection.style.display = 'block';
                gostosSection.style.animation = 'fadeSlideIn 0.3s ease-out forwards';
            } else if (section === 'spotify' && spotifySection) {
                spotifySection.style.display = 'block';
                spotifySection.style.animation = 'fadeSlideIn 0.3s ease-out forwards';
            }
        }
        
        function hideAboutSection() {
            const aboutOptions = document.getElementById('about-options');
            const gostosSection = document.getElementById('about-gostos');
            const spotifySection = document.getElementById('about-spotify');
            
            // Hide all detail sections
            if (gostosSection) gostosSection.style.display = 'none';
            if (spotifySection) spotifySection.style.display = 'none';
            
            // Show the options menu
            if (aboutOptions) {
                aboutOptions.style.display = 'grid';
                aboutOptions.style.animation = 'fadeSlideIn 0.3s ease-out forwards';
            }
        }
        </script>
    </div>
</div>
<!-- visualizador de imagem -->
<div class="image-full" id="image-full">
    <div class="imagem-top" onclick="closeImageViwer();">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
    </div>
    <div class="imagemContainer">
        <img id="imagemVisu">
    </div>
    <div class="imagem-bottom">
        <center onclick="zoomInImageViwer()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Zm-40-60v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z"/></svg></center>
        <center><span style="cursor: block;">Ver os Comentarios</span></center>
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