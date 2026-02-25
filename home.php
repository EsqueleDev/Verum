<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/userFunctions.php';
    include 'PhpShits/connectionsUsersFuncs.php';
    include 'PhpShits/algoritimoMACHO.php';

    $me = getUserInfo($conn, $_COOKIE['UserId']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Feed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php">
</head>
<body>

<div class="app-container">

    <!-- HEADER -->
    <header class="app-header">
        <button class="icon-btn"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></button>
        <span class="app-title">Verum</span>
        <a href='profile.php?id=<?= $me['id'] ?>'><button class="icon-btn"><div class="avatar" style="background: url(<?= $me['profilePic'] ?>);  background-repeat: no-repeat;  background-size: cover; width: 24px; height: 24px;"></div></button></a>
    </header>

    <!-- FEED -->
    <main class="feed">

        <!-- POST -->
        <?php $posts = algoritmoGeralSite($conn, $me['id'], 1, 3); ?>
        <?php foreach($posts as $post): ?>
            <article class="post-card">
                <a href='profile.php?id=<?= $post['userId'] ?>'><div class="post-header">
                    <div class="avatar" style="background: url(<?= htmlspecialchars($post['user']['profilePic'] ?? '') ?>); background-repeat: no-repeat; background-size: cover;"></div>
                    <div class="post-user">
                        <strong><?= htmlspecialchars($post['user']['username'] ?? 'Usuário') ?></strong>
                        <span><?= htmlspecialchars($post['postTime'] ?? '') ?></span>
                    </div>
                </div></a>

                <p class="post-text">
                    <h2><?= htmlspecialchars($post['titulo'] ?? '') ?></h2>
                    <?php if($post['tipo'] == 'texto'): ?>
                        <?= nl2br(htmlspecialchars($post['conteudo'] ?? '')) ?>
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
                <hr>
            </article>
        <?php endforeach ?>
        <!-- SUGESTÕES -->
        <?php $randUsers = getLastUsersToEnterWebsite($conn, 1) ?>
            <section class="suggestions-section" style="padding: 15px;">
                <h3>Pessoas Para Conhecer:</h3>

                <div class="suggestions">
                    <?php foreach($randUsers as $rUser): ?>
                        <div class="suggestion-card">
                            <div class="suggest-avatar avatar-a" style="background: url(<?= $rUser['profilePic'] ?>);  background-repeat: no-repeat;  background-size: cover;"></div>
                            <span><?= $rUser['username'] ?></span>
                        </div>
                    <?php endforeach ?>
                </div>
            </section>
        <hr>
        <?php $posts = algoritmoGeralSite($conn, $me['id'], 2, 3); ?>
        <?php foreach($posts as $post): ?>
            <article class="post-card">
                <a href='profile.php?id=<?= $post['userId'] ?>'><div class="post-header">
                    <div class="avatar" style="background: url(<?= htmlspecialchars($post['user']['profilePic'] ?? '') ?>); background-repeat: no-repeat; background-size: cover;"></div>
                    <div class="post-user">
                        <strong><?= htmlspecialchars($post['user']['username'] ?? 'Usuário') ?></strong>
                        <span><?= htmlspecialchars($post['postTime'] ?? '') ?></span>
                    </div>
                </div></a>

                <p class="post-text">
                    <h2><?= htmlspecialchars($post['titulo'] ?? '') ?></h2>
                    <?php if($post['tipo'] == 'texto'): ?>
                        <?= nl2br(htmlspecialchars($post['conteudo'] ?? '')) ?>
                    <?php endif; ?>
                </p>
                <?php if($post['tipo'] == 'imagem'): ?>
                    <img src="<?= htmlspecialchars($post['mediaFile'] ?? '') ?>">
                <?php endif; ?>
                <?php if($post['tipo'] == 'video'): ?>
                    <video width="320" height="240" controls>
                        <source src="<?= htmlspecialchars($post['mediaFile'] ?? '') ?>">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
            </article>
            <hr>
        <?php endforeach ?>
        <article class="post-card">
            <p class="post-text">
                <h2>Dica do Verum:</h2>
                Por enquanto estamos em Beta, não temos oque ensinar.
            </p>
        </article>
        
        <!-- Load More Button -->
        <div id="load-more-container">
            <button id="load-more-btn" class="load-more-btn">Carregar Mais</button>
            <div id="loading-indicator" class="loading-indicator" style="display: none;">
                <div class="spinner"></div>
                <span>Carregando...</span>
            </div>
        </div>

        <br><br><br><br><br><br>
    </main>
    <?php if(checkNewRequests($conn, $me['id'])): ?>
        <a href="inbox.php"><button class="new-notification-button"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="var(--div)"><path d="M480-80q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Zm0-420ZM160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v13q-11 22-16 45t-4 47q-10-2-19.5-3.5T480-720q-66 0-113 47t-47 113v280h320v-257q18 8 38.5 12.5T720-520v240h80v80H160Zm475-435q-35-35-35-85t35-85q35-35 85-35t85 35q35 35 35 85t-35 85q-35 35-85 35t-85-35Z"/></svg></button></a>
    <?php endif; ?>
    <a href='newPost'><button class="new-post-button"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="var(--div)"><path d="m490-527 37 37 217-217-37-37-217 217ZM200-200h37l233-233-37-37-233 233v37Zm355-205L405-555l167-167-29-29-219 219-56-56 218-219q24-24 56.5-24t56.5 24l29 29 50-50q12-12 28.5-12t28.5 12l93 93q12 12 12 28.5T828-678L555-405ZM270-120H120v-150l285-285 150 150-285 285Z"/></svg></button></a>

    <!-- BOTTOM NAV -->
    <?php include 'nav-bar-bottom.php'; ?>

</div>

<script>
    let currentPage = 3; // Start from page 1
    let isLoading = false;
    let hasMore = true;

    const loadMoreBtn = document.getElementById('load-more-btn');
    const loadingIndicator = document.getElementById('loading-indicator');
    const feed = document.querySelector('.feed');

    // Function to create HTML for a post
    function createPostHTML(post) {
        let mediaHTML = '';
        if (post.tipo === 'imagem' && post.mediaFile) {
            mediaHTML = `<img src="${post.mediaFile}">`;
        } else if (post.tipo === 'video' && post.mediaFile) {
            mediaHTML = `<video width="320" height="240" controls>
                <source src="${post.mediaFile}">
                Your browser does not support the video tag.
            </video>`;
        }

        let contentHTML = '';
        if (post.tipo === 'texto' && post.conteudo) {
            contentHTML = post.conteudo;
        }

        const profilePic = post.user?.profilePic || '';
        const username = post.user?.username || 'Usuário';

        return `
            <hr>
            <article class="post-card">
                <a href='profile.php?id=${post.userId}'><div class="post-header">
                    <div class="avatar" style="background: url(${profilePic}); background-repeat: no-repeat; background-size: cover;"></div>
                    <div class="post-user">
                        <strong>${username}</strong>
                        <span>${post.postTime || ''}</span>
                    </div>
                </div></a>
                <p class="post-text">
                    <h2>${post.titulo || ''}</h2>
                    ${contentHTML}
                </p>
                ${mediaHTML}
            </article>
            
        `;
    }

    // Load more posts function
    async function loadMorePosts() {
        if (isLoading || !hasMore) return;

        isLoading = true;
        loadMoreBtn.style.display = 'none';
        loadingIndicator.style.display = 'flex';

        try {
            const response = await fetch(`get_posts.php?page=${currentPage}&limit=3`);
            const data = await response.json();

            if (data.success && data.posts.length > 0) {
                // Insert posts before the load more container
                const loadMoreContainer = document.getElementById('load-more-container');
                
                data.posts.forEach(post => {
                    const postHTML = createPostHTML(post);
                    loadMoreContainer.insertAdjacentHTML('beforebegin', postHTML);
                });

                hasMore = data.hasMore;
                currentPage++;

                if (hasMore) {
                    loadMoreBtn.style.display = 'block';
                } else {
                    loadMoreContainer.style.display = 'none';
                }
            } else {
                hasMore = false;
                document.getElementById('load-more-container').style.display = 'none';
            }
        } catch (error) {
            console.error('Error loading posts:', error);
            loadMoreBtn.style.display = 'block';
        } finally {
            isLoading = false;
            loadingIndicator.style.display = 'none';
        }
    }

    // Add click event to load more button
    loadMoreBtn.addEventListener('click', loadMorePosts);

    // Optional: Infinite scroll - load more when near bottom
    window.addEventListener('scroll', () => {
        if (isLoading || !hasMore) return;
        
        const scrollPosition = window.innerHeight + window.scrollY;
        const pageHeight = document.documentElement.scrollHeight;
        
        // Load more when user is 200px from bottom
        if (pageHeight - scrollPosition < 200) {
            loadMorePosts();
        }
    });
</script>
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
        <center><span>Ver os Comentarios</span></center>
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
<!-- macumba pra notificações baitolass yay -->
<script src="push-notifications.js"></script>
<script>
// Register service worker for push notifications
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('sw.js')
            .then(reg => {
                console.log('Service Worker registrado:', reg.scope);
            })
            .catch(err => {
                console.error('Erro ao registrar SW:', err);
            });
    });
}

// Request notification permission on page load
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission().then(permission => {
        console.log('Notification permission:', permission);
    });
}

// Background notification checker - runs periodically
let notificationCheckInterval;
let lastCheckTime = 0;
let lastFeaturesCheck = 0;

function startNotificationChecker() {
    // Check immediately on page load
    checkForNotifications();
    checkForNewFeatures();
    
    // Then check every 30 seconds in the background
    notificationCheckInterval = setInterval(() => {
        checkForNotifications();
        checkForNewFeatures();
    }, 30000);
}

async function checkForNotifications() {
    try {
        const userId = getCookie('UserId');
        if (!userId) return;
        
        const response = await fetch(`check-friend-requests.php?userId=${userId}&lastCheck=${lastCheckTime}`);
        const data = await response.json();
        
        // Check if there are actual new requests in the JSON
        if (data.success && data.newRequests && data.newRequests.length > 0) {
            // Show notification only if there are actual new requests
            data.newRequests.forEach(request => {
                showNotification('Novo Pedido de Amizade', `${request.username} enviou um pedido de amizade`);
            });
            lastCheckTime = data.lastCheck;
        }
    } catch (error) {
        console.error('Error checking notifications:', error);
    }
}

async function checkForNewFeatures() {
    try {
        const response = await fetch(`check-new-features.php?lastCheck=${lastFeaturesCheck}`);
        const data = await response.json();
        
        // Check if there are actual new features in the JSON
        if (data.success && data.newFeatures && data.newFeatures.length > 0) {
            // Show notification only if there are actual new features
            data.newFeatures.forEach(feature => {
                showNotification(feature.title, feature.message);
            });
            lastFeaturesCheck = data.lastCheck;
        }
    } catch (error) {
        console.error('Error checking new features:', error);
    }
}

function showNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        // Use Service Worker for notification
        navigator.serviceWorker.ready.then(registration => {
            registration.showNotification(title, {
                body: body,
                icon: 'Default_Profile_Pics/1.png',
                badge: 'icon.png',
                tag: 'friend-request',
                requireInteraction: true,
                data: { url: 'inbox.php' }
            });
        }).catch(err => {
            // Fallback to regular notification
            new Notification(title, {
                body: body,
                icon: 'Default_Profile_Pics/1.png'
            });
        });
    }
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

// Start the notification checker when the page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startNotificationChecker);
} else {
    startNotificationChecker();
}

// Stop checking when page is hidden to save resources
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        clearInterval(notificationCheckInterval);
    } else {
        checkForNotifications();
        checkForNewFeatures();
        notificationCheckInterval = setInterval(() => {
            checkForNotifications();
            checkForNewFeatures();
        }, 30000);
    }
});
</script>
</body>
</html>
