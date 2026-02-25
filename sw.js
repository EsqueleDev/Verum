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

// Background sync for notifications
let canFetch = true;

// Request notification permission on service worker install
self.addEventListener('install', event => {
    self.skipWaiting();
    // Notification permission request will be handled by the page
});

async function checkNotification() {
    if (!canFetch) return;

    canFetch = false;

    try {
        // Get user ID from storage or skip
        const userId = await getUserIdFromStorage();
        if (!userId) {
            canFetch = true;
            return;
        }

        const response = await fetch('check-friend-requests.php?userId=' + userId + '&lastCheck=0');
        if (!response.ok) {
            canFetch = true;
            return;
        }

        const data = await response.json();

        // Se não tiver notificações, libera nova busca
        if (!data.success || data.count === 0) {
            canFetch = true;
            return;
        }

        // Send notifications for new friend requests
        data.newRequests.forEach(item => {
            sendNotification(item.username + ' enviou um pedido de amizade', 'Clique para ver');
        });

    } catch (erro) {
        console.error("Erro ao buscar JSON:", erro);
        canFetch = true;
    }
}

async function getUserIdFromStorage() {
    // Try to get from IndexedDB or localStorage
    try {
        const cache = await caches.open('verum-cache');
        const response = await cache.match('/userId');
        if (response) {
            const data = await response.json();
            return data.userId;
        }
    } catch (e) {
        // Ignore
    }
    return null;
}

function sendNotification(text, subtext) {
    self.registration.showNotification('Verum - Nova Atividade', {
        body: text,
        icon: '/Default_Profile_Pics/1.png',
        badge: '/icon.png',
        tag: 'friend-request',
        requireInteraction: true,
        data: { url: '/inbox.php' }
    });
}

// Check for notifications periodically when service worker is active
self.addEventListener('activate', event => {
    // Start periodic check every 30 seconds
    setInterval(checkNotification, 30000);
    
    // Also check immediately on activation
    checkNotification();
});

// Handle push events (for server-sent push notifications)
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