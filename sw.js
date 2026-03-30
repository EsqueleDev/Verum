// Service Worker for Verum - Handles push events from server only
// Note: Polling for notifications is handled by home.php JavaScript

self.addEventListener('install', event => {
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    self.clients.claim();
});

// Clique na notificação
self.addEventListener('notificationclick', event => {
    event.notification.close();

    event.waitUntil(
        clients.openWindow('/inbox.php')
    );
});

// Handle push events (for server-sent push notifications only)
// This receives notifications pushed from the server, not polling for them
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : {};
    
    const title = data.title || 'Verum';
    const options = {
        body: data.body || 'Nova notificação',
        icon: data.icon || '/Default_Profile_Pics/1.png',
        badge: '/icon.png',
        tag: data.tag || 'verum-notification',
        requireInteraction: data.requireInteraction || false,
        data: data.url || '/inbox.php'
    };
    
    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

const CACHE_NAME = 'verum-cache-v3';
const OFFLINE_PAGE = '/errorPage.php?code=5';

// =========================
// INSTALL
// =========================
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll([
                OFFLINE_PAGE
            ]);
        })
    );
    self.skipWaiting();
});

const BLOCKED_PAGES = [
    '/login.php',
    '/settings',
    '/newDraw.php',
    '/newPost',
    '/createAlbum.php'
];

// =========================
// ACTIVATE
// =========================
self.addEventListener('activate', e => {
    clients.claim();
});

// =========================
// FETCH
// =========================
self.addEventListener('fetch', event => {
    const req = event.request;
    const url = new URL(req.url);

    // 🔹 API
    if (url.pathname.includes('/api/')) {
        event.respondWith(handleAPI(req));
        return;
    }

    // 🔹 CSS (qualquer .css)
    if (url.pathname.endsWith('.css')) {
        event.respondWith(handleStyle(req));
        return;
    }

    // 🔹 colors.php
    if (url.pathname.includes('colors.php')) {
        event.respondWith(handleColors(req));
        return;
    }

    // 🔹 IMAGENS
    if (req.destination === 'image') {
        event.respondWith(handleImages(req));
        return;
    }

    // 🔹 HTML
    if (req.headers.get('accept')?.includes('text/html')) {
        event.respondWith(handleHTML(req));
        return;
    }

    // 🔹 fallback
    event.respondWith(
        caches.match(req).then(res => res || fetch(req))
    );
});


// =========================
// 🔥 HTML + BACKUP
// =========================
async function handleHTML(request) {
    const cache = await caches.open(CACHE_NAME);
    const url = new URL(request.url);

    const cacheKey = request.url;

    try {
        const networkResponse = await fetch(request);

        const clone1 = networkResponse.clone();
        const clone2 = networkResponse.clone();

        // 🔹 salva versão atual (pode sobrescrever, ok)
        await cache.put(cacheKey, clone1);

        // 🔥 BACKUP ÚNICO (NUNCA sobrescreve)
        const uniqueId = Date.now() + '-' + Math.random().toString(36).slice(2);

        const backupKey = cacheKey + '&backup=' + uniqueId;

        await cache.put(backupKey, clone2);

        return networkResponse;

    } catch (err) {

        // 🔴 tenta versão principal
        const cached = await cache.match(cacheKey);
        if (cached) return cached;

        // 🔥 pega QUALQUER backup dessa URL
        const keys = await cache.keys();

        const backups = keys.filter(r =>
            r.url.startsWith(cacheKey + '&backup=')
        );

        // 🔥 ordena pelo mais recente
        backups.sort((a, b) => b.url.localeCompare(a.url));

        if (backups.length > 0) {
            return await cache.match(backups[0]);
        }

        return caches.match(OFFLINE_PAGE);
    }
}

// =========================
// 🎨 CSS (fixo, ignora ?v)
// =========================
async function handleStyle(request) {
    const cache = await caches.open(CACHE_NAME);
    const url = new URL(request.url);

    const cacheKey = url.pathname;

    try {
        const response = await fetch(request);

        await cache.put(cacheKey, response.clone());

        return response;

    } catch {
        const cached = await cache.match(cacheKey);
        return cached || new Response('', {
            headers: { 'text/css': 'Content-Type' }
        });
    }
}


// =========================
// 🎨 colors.php
// =========================
async function handleColors(request) {
    const cache = await caches.open(CACHE_NAME);
    const cacheKey = '/colors.php';

    try {
        const response = await fetch(request);

        await cache.put(cacheKey, response.clone());

        return response;

    } catch {
        return await cache.match(cacheKey);
    }
}


// =========================
// 🖼️ IMAGENS (CORRIGIDO)
// =========================
async function handleImages(request) {
    const cache = await caches.open(CACHE_NAME);

    try {
        // 🟢 sempre tenta rede primeiro
        const response = await fetch(request);

        // 🔥 salva no cache (mas sem bloquear)
        cache.put(request, response.clone());

        return response;

    } catch {
        // 🔴 offline → usa cache
        const cached = await cache.match(request);
        return cached || new Response('', { status: 204 });
    }
}


// =========================
// 🔹 API
// =========================
async function handleAPI(request) {
    try {
        const response = await fetch(request);
        const clone = response.clone();

        const data = await clone.json();

        await saveToIDB(request.url, data);

        return response;

    } catch {
        const data = await getFromIDB(request.url);

        if (data) {
            return new Response(JSON.stringify(data), {
                headers: { 'Content-Type': 'application/json' }
            });
        }

        return new Response(JSON.stringify({ error: 'offline' }), {
            status: 503
        });
    }
}


// =========================
// 🧠 IndexedDB
// =========================
let db;

const requestDB = indexedDB.open('verumDB', 1);

requestDB.onupgradeneeded = e => {
    db = e.target.result;
    db.createObjectStore('api', { keyPath: 'url' });
};

requestDB.onsuccess = e => {
    db = e.target.result;
};

function saveToIDB(url, data) {
    return new Promise(resolve => {
        const tx = db.transaction('api', 'readwrite');
        tx.objectStore('api').put({ url, data });
        tx.oncomplete = resolve;
    });
}

function getFromIDB(url) {
    return new Promise(resolve => {
        const tx = db.transaction('api', 'readonly');
        const req = tx.objectStore('api').get(url);

        req.onsuccess = () => resolve(req.result?.data);
        req.onerror = () => resolve(null);
    });
}