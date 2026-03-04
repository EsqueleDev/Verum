<?php
/**
 * Push Notification Functions for Verum Social Network
 * Handles push subscription management and notification sending
 */

// Create the push_subscriptions table if it doesn't exist
function createPushSubscriptionsTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS push_subscriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        endpoint VARCHAR(500) NOT NULL,
        keys_p256dh VARCHAR(255) NOT NULL,
        keys_auth VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_endpoint (user_id, endpoint(255))
    )";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    }
    return false;
}

// Save a push subscription
function savePushSubscription($conn, $userId, $endpoint, $keys) {
    // Check if subscription already exists
    $stmt = $conn->prepare("SELECT id FROM push_subscriptions WHERE user_id = ? AND endpoint = ?");
    $stmt->bind_param("is", $userId, $endpoint);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing subscription
        $stmt = $conn->prepare("UPDATE push_subscriptions SET keys_p256dh = ?, keys_auth = ? WHERE user_id = ? AND endpoint = ?");
        $stmt->bind_param("ssss", $keys['p256dh'], $keys['auth'], $userId, $endpoint);
    } else {
        // Insert new subscription
        $stmt = $conn->prepare("INSERT INTO push_subscriptions (user_id, endpoint, keys_p256dh, keys_auth) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $endpoint, $keys['p256dh'], $keys['auth']);
    }
    
    return $stmt->execute();
}

// Get all push subscriptions for a user
function getPushSubscriptions($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM push_subscriptions WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $subscriptions = [];
    while ($row = $result->fetch_assoc()) {
        $subscriptions[] = $row;
    }
    return $subscriptions;
}

// Delete a push subscription
function deletePushSubscription($conn, $userId, $endpoint) {
    $stmt = $conn->prepare("DELETE FROM push_subscriptions WHERE user_id = ? AND endpoint = ?");
    $stmt->bind_param("IS", $userId, $endpoint);
    return $stmt->execute();
}

// Send push notification to a user
function sendPushNotification($conn, $userId, $title, $body, $icon = '', $url = '') {
    $subscriptions = getPushSubscriptions($conn, $userId);
    
    if (empty($subscriptions)) {
        return false;
    }
    
    $results = [];
    foreach ($subscriptions as $subscription) {
        $result = sendWebPush($subscription['endpoint'], [
            'p256dh' => $subscription['keys_p256dh'],
            'auth' => $subscription['keys_auth']
        ], [
            'title' => $title,
            'body' => $body,
            'icon' => $icon,
            'url' => $url,
            'tag' => 'friend-request',
            'requireInteraction' => true
        ]);
        $results[] = $result;
    }
    
    return in_array(true, $results);
}

// Send Web Push using cURL
function sendWebPush($endpoint, $keys, $data) {
    // For VAPID-protected push, you would need:
    // 1. VAPID private key
    // 2. VAPID public key (already shared with the client)
    // 3. VAPID subject (usually mailto: or URL)
    // 
    // Since this is a simplified implementation without VAPID,
    // we'll use a basic approach that works for testing.
    // In production, you'd use a library like web-push-php
    
    $payload = json_encode([
        'notification' => [
            'title' => $data['title'],
            'body' => $data['body'],
            'icon' => $data['icon'] ?? '/icon.png',
            'badge' => '/icon.png',
            'tag' => $data['tag'] ?? 'notification',
            'data' => [
                'url' => $data['url'] ?? '/inbox'
            ],
            'requireInteraction' => $data['requireInteraction'] ?? false,
            'vibrate' => [100, 50, 100],
            'actions' => [
                ['action' => 'view', 'title' => 'Ver'],
                ['action' => 'close', 'title' => 'Fechar']
            ]
        ]
    ]);
    
    // Simple push (no encryption - works with some push services)
    // For proper implementation, use a library like minishlink/web-push
    return simplePush($endpoint, $payload);
}

// Simple push notification sender (for basic push services)
function simplePush($endpoint, $payload) {
    $ch = curl_init($endpoint);
    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'TTL: 86400',
        'Authorization: vapid t=' . generateVapidToken()
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode >= 200 && $httpCode < 300;
}

// Generate a simple VAPID token (for demo purposes)
// In production, use proper VAPID keys
function generateVapidToken() {
    // This is a placeholder - in production, use proper VAPID authentication
    // You would need to generate real VAPID keys and store them securely
    return 'demo_token_replace_with_real_vapid_key';
}

// Check if user has push notification permission
function getNotificationPermissionStatus() {
    // This is handled on the client side via JavaScript
    return null;
}

// Initialize the push subscriptions table
function initPushNotifications($conn) {
    createPushSubscriptionsTable($conn);
}
