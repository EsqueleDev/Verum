<?php
/**
 * Save Push Subscription Endpoint
 * Receives push subscription data from client and saves to database
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include 'PhpShits/conn.php';
include 'PhpShits/pushNotificationFuncs.php';

// Initialize push notifications (creates table if not exists)
initPushNotifications($conn);

// Get POST data
$endpoint = isset($_POST['endpoint']) ? $_POST['endpoint'] : '';
$p256dh = isset($_POST['p256dh']) ? $_POST['p256dh'] : '';
$auth = isset($_POST['auth']) ? $_POST['auth'] : '';
$userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;

// Debug: Log received data
error_log("[Push] Received subscription data:");
error_log("  - Endpoint: " . substr($endpoint, 0, 100) . "...");
error_log("  - P256DH: " . substr($p256dh, 0, 50) . "...");
error_log("  - Auth: " . substr($auth, 0, 20) . "...");
error_log("  - UserId: " . $userId);

if (empty($endpoint) || empty($p256dh) || empty($auth) || empty($userId)) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required parameters',
        'debug' => [
            'endpoint' => !empty($endpoint),
            'p256dh' => !empty($p256dh),
            'auth' => !empty($auth),
            'userId' => $userId
        ]
    ]);
    exit;
}

// Save the subscription
$keys = [
    'p256dh' => $p256dh,
    'auth' => $auth
];

$result = savePushSubscription($conn, $userId, $endpoint, $keys);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Push subscription saved successfully',
        'debug' => [
            'userId' => $userId,
            'endpoint_length' => strlen($endpoint)
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save push subscription'
    ]);
}

$conn->close();
