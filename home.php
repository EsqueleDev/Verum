<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/userFunctions.php';
    include 'PhpShits/connectionsUsersFuncs.php';
    include 'PhpShits/algoritimoMACHO.php';
    if (!isset($_COOKIE['UserId'])) {
        header("Location: index.php");
        exit;
    }
    $me = getUserInfo($conn, $_COOKIE['UserId']);
    function quebrarPalavrasGrandes($texto, $limite = 30) {
        return preg_replace('/(\S{'.$limite.'})/u', '$1<wbr>', $texto);
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Feed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php">
    <link rel="manifest" href="manifest.json" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="Page_Icon.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#1a1522" />
    <meta name="theme-color" content="#1a1522" />
</head>
<body>
<div class="hideMobile SideBarPc">
    <a href='#'><div class='SideBarContent hideMobile'><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M226.67-186.67h140v-246.66h226.66v246.66h140v-380L480-756.67l-253.33 190v380ZM160-120v-480l320-240 320 240v480H526.67v-246.67h-93.34V-120H160Zm320-352Z"/></svg> <h3>Home</h3></div></a>
    <a href='#'><div class='SideBarContent hideMobile'><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M792-120.67 532.67-380q-30 25.33-69.67 39.67Q423.33-326 378.67-326q-108.34 0-183.5-75.17Q120-476.33 120-583.33t75.17-182.17q75.16-75.17 182.83-75.17 107 0 181.83 75.17 74.84 75.17 74.84 182.17 0 43.33-14 83-14 39.66-40.67 73l260 258.66-48 48Zm-414-272q79 0 134.5-55.83T568-583.33q0-79-55.5-134.84Q457-774 378-774q-79.67 0-135.5 55.83-55.83 55.84-55.83 134.84T242.5-448.5q55.83 55.83 135.5 55.83Z"/></svg><h3>Buscar</h3></div></a>
    <a href='#'><div class='SideBarContent hideMobile'><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M240-399.33h315.33V-466H240v66.67ZM240-526h480v-66.67H240V-526Zm0-126.67h480v-66.66H240v66.66ZM80-80v-733.33q0-27 19.83-46.84Q119.67-880 146.67-880h666.66q27 0 46.84 19.83Q880-840.33 880-813.33v506.66q0 27-19.83 46.84Q840.33-240 813.33-240H240L80-80Zm131.33-226.67h602v-506.66H146.67v575l64.66-68.34Zm-64.66 0v-506.66 506.66Z"/></svg><h3>Conversas</h3></div></a>
    <a href='#'><div class='SideBarContent hideMobile'><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="m382-80-18.67-126.67q-17-6.33-34.83-16.66-17.83-10.34-32.17-21.67L178-192.33 79.33-365l106.34-78.67q-1.67-8.33-2-18.16-.34-9.84-.34-18.17 0-8.33.34-18.17.33-9.83 2-18.16L79.33-595 178-767.67 296.33-715q14.34-11.33 32.34-21.67 18-10.33 34.66-16L382-880h196l18.67 126.67q17 6.33 35.16 16.33 18.17 10 31.84 22L782-767.67 880.67-595l-106.34 77.33q1.67 9 2 18.84.34 9.83.34 18.83 0 9-.34 18.5Q776-452 774-443l106.33 78-98.66 172.67-118-52.67q-14.34 11.33-32 22-17.67 10.67-35 16.33L578-80H382Zm55.33-66.67h85l14-110q32.34-8 60.84-24.5T649-321l103.67 44.33 39.66-70.66L701-415q4.33-16 6.67-32.17Q710-463.33 710-480q0-16.67-2-32.83-2-16.17-7-32.17l91.33-67.67-39.66-70.66L649-638.67q-22.67-25-50.83-41.83-28.17-16.83-61.84-22.83l-13.66-110h-85l-14 110q-33 7.33-61.5 23.83T311-639l-103.67-44.33-39.66 70.66L259-545.33Q254.67-529 252.33-513 250-497 250-480q0 16.67 2.33 32.67 2.34 16 6.67 32.33l-91.33 67.67 39.66 70.66L311-321.33q23.33 23.66 51.83 40.16 28.5 16.5 60.84 24.5l13.66 110Zm43.34-200q55.33 0 94.33-39T614-480q0-55.33-39-94.33t-94.33-39q-55.67 0-94.5 39-38.84 39-38.84 94.33t38.84 94.33q38.83 39 94.5 39ZM480-480Z"/></svg><h3>Configurações</h3></div></a>
    <a href='#'><div class='SideBarContent hideMobile'><img src='<?= $me['profilePic'] ?>' style='max-width: 36px; height: 36px; border-radius: 50%;'><h3><?= $me['username'] ?></h3></div></a>
</div>
<div class="app-container">

    <!-- HEADER -->
    <header class="app-header hidePC">
        <button class="icon-btn" onclick="showSideBar()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></button>
        <span class="app-title">Verum</span>
        <a href='profile.php?id=<?= $me['id'] ?>'><button class="icon-btn"><div class="avatar" style="background: url(<?= $me['profilePic'] ?>);  background-repeat: no-repeat;  background-size: cover; width: 24px; height: 24px;"></div></button></a>
    </header>

    <!-- FEED -->
    <main class="feed">

        <!-- POST -->
        <?php $posts = algoritmoGeralSite($conn, $me['id'], 1, 3); ?>
        <?php foreach($posts as $post): ?>
            <article class="post-card">
                <a href='profile.php?id=<?= $post['userId'] ?>'><div class="post-header" style='margin-bottom: 12px;'>
                    <div class="avatar" style="background: url(<?= htmlspecialchars($post['user']['profilePic'] ?? '') ?>); background-repeat: no-repeat; background-size: cover;"></div>
                    <div class="post-user">
                        <strong><?= htmlspecialchars($post['user']['username'] ?? 'Usuário') ?></strong>
                        <span><?= htmlspecialchars($post['postTime'] ?? '') ?></span>
                    </div>
                </div></a>

                <span class="post-text">
                    <h2><?= htmlspecialchars($post['titulo'] ?? '') ?></h2><br>
                    <?php if($post['tipo'] == 'texto'): ?>
                        <?= nl2br(quebrarPalavrasGrandes(htmlspecialchars($post['conteudo'] ?? ''))) ?>
                    <?php endif; ?>
                </span>
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
                <hr>
            </article>
        <?php endforeach ?>
        <!-- SUGESTÕES -->
        <?php $randUsers = getLastUsersToEnterWebsite($conn, 1) ?>
            <section class="suggestions-section" style="padding: 15px;">
                <h3>Pessoas Para Conhecer:</h3>

                <div class="suggestions">
                    <?php foreach($randUsers as $rUser): ?>     
                        <a href="profile.php?id=<?= $rUser['id'] ?>"><div class="suggestion-card">
                            <div class="suggest-avatar avatar-a" style="background: url(<?= $rUser['profilePic'] ?>);  background-repeat: no-repeat;  background-size: cover;"></div>
                            <span><?= $rUser['username'] ?></span>
                        </div></a>
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
                Quando reiniciar a pagina podera ver uma dica de coisas novas sobre Verum, mas por enquanto nada
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
    <a href='newPost'><button class="new-post-button hidePC"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="var(--div)"><path d="m490-527 37 37 217-217-37-37-217 217ZM200-200h37l233-233-37-37-233 233v37Zm355-205L405-555l167-167-29-29-219 219-56-56 218-219q24-24 56.5-24t56.5 24l29 29 50-50q12-12 28.5-12t28.5 12l93 93q12 12 12 28.5T828-678L555-405ZM270-120H120v-150l285-285 150 150-285 285Z"/></svg></button></a>

    <!-- BOTTOM NAV -->
    <?php include 'nav-bar-bottom.php'; ?>

</div>
<div class="hideMobile">
    <?php include('sideBar.php'); ?>
</div>
<script>
    let currentPage = 3; // Start from page 1
    let hasMoreVerum = true;
    let hasMoreReddit = true;
    let recentSubreddits = [];
    let usedPostIds = new Set();
    let isLoadingPosts = false;
    let isLoadingReddit = false;
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
            contentHTML = breakLongWords(post.conteudo);
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

    function breakLongWords(text, limit = 40){
        return text.replace(new RegExp(`(\\S{${limit}})`, "g"), "$1\u200B");
    }

    function createRedditPostHTML(post) {
        const profilePic = '';
        const username = post.data.author || 'Usuário do Reddit';
    
        let image = '';
    
        if (post.data.preview && post.data.preview.images) {
            image = `<img src="${post.data.preview.images[0].source.url}" style="max-width:100%">`;
        }
    
        return `
            <hr>
            <article class="post-card">
                <a href="${post.data.url}">
                    <div class="post-header">
                        <div class="avatar" style="background:url(https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTE9W4qKeBxK3fL5-W4X70x2IwLYATQeCwt1A&s); background-size:cover;"></div>
                        <div class="post-user">
                            <strong>${username}</strong>
                            <span>Oque esta acontecendo no Reddit agora</span>
                        </div>
                    </div>
                
    
                <p class="post-text">
                    <h2>${post.data.title || ''}</h2>
                    ${renderText(post.data.selftext || '')}
                </p>
    
                ${image}
            </a></article>
        `;
    }

    // Load more posts function
    async function loadMorePosts() {
    
        if (isLoadingPosts) return;
    
        isLoadingPosts = true;
        loadMoreBtn.style.display = 'none';
        loadingIndicator.style.display = 'flex';
    
        try {
    
            if (hasMoreVerum) {
    
                const response = await fetch(`get_posts.php?page=${currentPage}&limit=4`);
                const data = await response.json();
    
                if (data.success && data.posts.length > 0) {
    
                    const loadMoreContainer = document.getElementById('load-more-container');
    
                    data.posts.forEach(post => {
                        const postHTML = createPostHTML(post);
                        loadMoreContainer.insertAdjacentHTML('beforebegin', postHTML);
                    });
    
                    currentPage++;
                    hasMoreVerum = data.hasMore;
    
                    // depois dos posts do Verum, carrega Reddit
                    await loadRedditPosts(3);
    
                } else {
    
                    hasMoreVerum = false;
    
                    // se não houver mais Verum → só Reddit
                    await loadRedditPosts(4);
                }
    
            } else {
    
                // apenas Reddit
                await loadRedditPosts(4);
    
            }
    
        } catch (error) {
    
            console.error('Error loading posts:', error);
            loadMoreBtn.style.display = 'block';
    
        } finally {
    
            isLoadingPosts = false;
            loadingIndicator.style.display = 'none';
    
            if (hasMoreVerum || hasMoreReddit) {
                loadMoreBtn.style.display = 'block';
            } else {
                document.getElementById('load-more-container').style.display = 'none';
            }
        }
    }
    
    function renderText(text) {
    
        // negrito **texto**
        text = text.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>");
    
        // links [texto](url)
        text = text.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank">$1</a>');
    
        // listas "- item"
        text = text.replace(/^- (.*)$/gm, "<li>$1</li>");
    
        // transformar blocos de li em ul
        text = text.replace(/(<li>.*<\/li>)/gs, "<ul>$1</ul>");
    
        // quebra de linha
        text = text.replace(/\n/g, "<br>");
    
        return text;
    }
    
    let redditAfter = null;

    async function loadRedditPosts(qtd = 1){
    
        if (isLoadingReddit || !hasMoreReddit) return;
    
        isLoadingReddit = true;
    
        let added = 0;
        let attempts = 0;
        const MAX_ATTEMPTS = qtd * 10;
    
        try {
    
            while (added < qtd && attempts < MAX_ATTEMPTS){
    
                attempts++;
    
                let url = "reddit_cache.php";
    
                if (redditAfter){
                    url += "?after=" + redditAfter;
                }
    
                const response = await fetch(url);
                const data = await response.json();
    
                if(!data || !data.data){
                    hasMoreReddit = false;
                    break;
                }
    
                const posts = data.data.children;
    
                if(!posts || posts.length === 0){
                    hasMoreReddit = false;
                    break;
                }
    
                const post = posts[0];
                const subreddit = post.data.subreddit;
                const postId = post.data.id;
    
                // atualizar cursor
                redditAfter = data.data.after;
    
                if(!redditAfter){
                    hasMoreReddit = false;
                }
    
                // ignorar nsfw
                if(post.data.over_18) continue;
    
                // evitar post repetido
                if(usedPostIds.has(postId)) continue;
    
                // evitar subreddit repetido
                if(recentSubreddits.includes(subreddit)) continue;
    
                usedPostIds.add(postId);
    
                recentSubreddits.push(subreddit);
    
                if(recentSubreddits.length > 3){
                    recentSubreddits.shift();
                }
    
                const postHTML = createRedditPostHTML(post);
    
                document
                    .getElementById('load-more-container')
                    .insertAdjacentHTML('beforebegin', postHTML);
    
                added++;
            }
    
            // fallback caso nenhuma regra permita adicionar
            if(added === 0 && attempts >= MAX_ATTEMPTS){
    
                console.log("Fallback Reddit");
    
                let url = "reddit_cache.php";
    
                const response = await fetch(url);
                const data = await response.json();
    
                const post = data.data.children[0];
    
                if(post){
                    const postHTML = createRedditPostHTML(post);
    
                    document
                        .getElementById('load-more-container')
                        .insertAdjacentHTML('beforebegin', postHTML);
                }
            }
    
        } catch (error){
    
            console.error('Error loading Reddit:', error);
    
        } finally {
    
            isLoadingReddit = false;
        }
    }

    // Add click event to load more button
    loadMoreBtn.addEventListener('click', loadMorePosts);

    let scrollCooldown = false;

    window.addEventListener('scroll', () => {
    
        if (scrollCooldown || isLoadingPosts) return;
    
        const scrollPosition = window.innerHeight + window.scrollY;
        const pageHeight = document.documentElement.scrollHeight;
    
        if (pageHeight - scrollPosition < 300) {
    
            scrollCooldown = true;
    
            loadMorePosts().finally(() => {
                setTimeout(() => {
                    scrollCooldown = false;
                }, 500);
            });
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
<!-- macumba pra notificações baitolass yay -->
<!-- sistema de notificações desabilitado para testes<script src="push-notifications.js"></script>
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

    
    // Then check every 30 seconds in the background
    notificationCheckInterval = setInterval(() => {
        checkForNotifications();
    
    }, 30000);
}

async function checkForNotifications() {
    try {
        const userId = getCookie('UserId');
        if (!userId) return;
        
        // Only show browser notifications on first check (lastCheckTime === 0)
        // Subsequent checks just track without showing duplicate notifications
        const isFirstCheck = lastCheckTime === 0;
        
        const response = await fetch(`check-friend-requests.php?userId=${userId}&lastCheck=${lastCheckTime}`);
        const data = await response.json();
        
        // Check if there are actual new requests in the JSON
        if (data.success && data.newRequests && data.newRequests.length > 0) {
            // Only show notification on first check to avoid duplicates
            if (isFirstCheck) {
                data.newRequests.forEach(request => {
                    showNotification('Novo Pedido de Amizade', `${request.username} enviou um pedido de amizade`);
                });
            }
            lastCheckTime = data.lastCheck;
        } else if (data.success) {
            // Update lastCheck even if no new requests
            lastCheckTime = data.lastCheck;
        }
    } catch (error) {
        console.error('Error checking notifications:', error);
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
    
        notificationCheckInterval = setInterval(() => {
            checkForNotifications();
        
        }, 30000);
    }
});
</script> -->
<div class='SideBarConteiner' id='SideBar'>
    <?php include('sideBar.php'); ?>
    <div class="closeSideBar" onclick='closeSideBar()'>
        
    </div>
</div>
<script>
    function showSideBar(){
        document.getElementById('SideBar').style.display = 'grid';
    }
    
    function closeSideBar(){
        document.getElementById('SideBar').style.display = 'none';
    }
</script>
</body>
</html>
