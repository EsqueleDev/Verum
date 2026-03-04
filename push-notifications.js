// Push Notifications for Verum - Handles existing subscriptions

const VAPID_PUBLIC_KEY = 'BEl62iUYgUivxIkv69yViEuiBIa-Ib9-SkvMeAtA3LFgDzkrxZJjSgSnfckjBJuBkr3qBUYIHBQFLXYp5Nksh8U';

if ('PushManager' in window) {
    navigator.serviceWorker.register('sw.js')
        .then(registration => {
            console.log('Service Worker registered');
            return registration.pushManager.getSubscription();
        })
        .then(subscription => {
            if (subscription) {
                // Check if existing subscription matches our VAPID key
                const existingKey = subscription.options && subscription.options.applicationServerKey;
                if (existingKey) {
                    console.log('Existing subscription found, reusing');
                    sendSubscriptionToServer(subscription);
                } else {
                    // Different key - unsubscribe and resubscribe
                    console.log('Different VAPID key, resubscribing');
                    return subscription.unsubscribe().then(() => subscribeToPush());
                }
            } else {
                // No subscription, create new one
                return subscribeToPush();
            }
        })
        .catch(err => console.error('Push setup failed:', err));
}

function subscribeToPush() {
    return navigator.serviceWorker.ready
        .then(registration => {
            const key = urlBase64ToUint8Array(VAPID_PUBLIC_KEY);
            return registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: key
            });
        })
        .then(subscription => {
            console.log('Push subscribed');
            sendSubscriptionToServer(subscription);
        })
        .catch(err => console.error('Push subscribe failed:', err));
}

function sendSubscriptionToServer(subscription) {
    const endpoint = subscription.endpoint;
    const keys = {
        p256dh: arrayBufferToBase64(subscription.getKey('p256dh')),
        auth: arrayBufferToBase64(subscription.getKey('auth'))
    };
    
    const formData = new FormData();
    formData.append('endpoint', endpoint);
    formData.append('p256dh', keys.p256dh);
    formData.append('auth', keys.auth);
    formData.append('userId', getCookie('UserId'));
    
    fetch('save-push-subscription.php', {
        method: 'POST',
        body: formData
    }).then(r => r.json()).then(d => console.log('Subscription saved')).catch(e => console.error(e));
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) outputArray[i] = rawData.charCodeAt(i);
    return outputArray;
}

function arrayBufferToBase64(buffer) {
    let binary = '';
    const bytes = new Uint8Array(buffer);
    for (let i = 0; i < bytes.byteLength; i++) binary += String.fromCharCode(bytes[i]);
    return window.btoa(binary);
}
