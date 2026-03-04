<?php
/**
 * Simple Push Notification Sender
 * Uses Web Push API with VAPID authentication
 * No external dependencies required
 */

class SimplePush {
    private $VAPID_PUBLIC_KEY;
    private $VAPID_PRIVATE_KEY;
    private $VAPID_SUBJECT;
    
    public function __construct($publicKey, $privateKey, $subject = 'mailto:admin@verum.com') {
        $this->VAPID_PUBLIC_KEY = $publicKey;
        $this->VAPID_PRIVATE_KEY = $privateKey;
        $this->VAPID_SUBJECT = $subject;
    }
    
    public function send($endpoint, $title, $body, $icon = '', $url = '/inbox') {
        $payload = json_encode([
            'notification' => [
                'title' => $title,
                'body' => $body,
                'icon' => $icon ?: '/Default_Profile_Pics/1.png',
                'badge' => '/icon.png',
                'tag' => 'verum-notification',
                'data' => ['url' => $url],
                'requireInteraction' => true
            ]
        ]);
        
        return $this->sendPush($endpoint, $payload);
    }
    
    private function sendPush($endpoint, $payload) {
        $ch = curl_init($endpoint);
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'TTL: 86400',
            'Authorization: vapid t=' . $this->generateToken()
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode >= 200 && $httpCode < 300;
    }
    
    private function generateToken() {
        // Simple JWT-like token for VAPID
        // In production, use proper JWT library
        $header = base64_url_encode(json_encode(['typ' => 'JWT', 'alg' => 'ES256']));
        $payload = base64_url_encode(json_encode([
            'aud' => parse_url($this->VAPID_SUBJECT, PHP_URL_HOST) ?: 'http://localhost',
            'exp' => time() + 3600,
            'sub' => $this->VAPID_SUBJECT
        ]));
        
        // For demo, return a placeholder - in production use proper ES256 signing
        return base64_url_encode($header . '.' . $payload . '.demo_signature');
    }
}

// Helper function
function base64_url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Send push notification function
function sendSimplePush($conn, $userId, $title, $body, $icon = '', $url = '/inbox') {
    // Get subscriptions
    $stmt = $conn->prepare("SELECT endpoint FROM push_subscriptions WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return false;
    }
    
    // VAPID keys - REPLACE WITH YOUR KEYS
    // Generate at: https://vapidkeys.com/
    $publicKey = 'BEl62iUYgUivxIkv69yViEuiBIa-Ib9-SkvMeAtA3LFgDzkrxZJjSgSnfckjBJuBkr3qBUYIHBQFLXYp5Nksh8U';
    $privateKey = 'UUxI4O8-FbRouAf7-7OT9eXoW0W3jZ5rP8xN2vK1hQ0';
    
    $push = new SimplePush($publicKey, $privateKey);
    
    $success = false;
    while ($row = $result->fetch_assoc()) {
        if ($push->send($row['endpoint'], $title, $body, $icon, $url)) {
            $success = true;
        }
    }
    
    return $success;
}
