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