<?php
/**
 * Send Push Notification Endpoint
 * Can be called to send push notifications to a user
 */

header('Content-Type: application/json');
include 'PhpShits/conn.php';
include 'PhpShits/pushNotificationFuncs.php';

$userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;
$title = isset($_GET['title']) ? $_GET['title'] : 'Verum';
$body = isset($_GET['body']) ? $_GET['body'] : 'Nova notificação';
$url = isset($_GET['url']) ? $_GET['url'] : '/inbox';

if (!$userId) {
    echo json_encode(['success' => false, 'error' => 'No userId provided']);
    exit;
}

initPushNotifications($conn);

$result = sendPushNotification($conn, $userId, $title, $body, '', $url);

echo json_encode([
    'success' => $result,
    'message' => $result ? 'Push notification sent' : 'No subscriptions found',
    'debug' => ['userId' => $userId, 'title' => $title, 'body' => $body]
]);

$conn->close();
