<?php
/**
 * API Endpoint: Check for New Friend Requests
 */

header('Content-Type: application/json');
include 'PhpShits/conn.php';
include 'PhpShits/userFunctions.php';
include 'PhpShits/connectionsUsersFuncs.php';

$userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;
$lastCheck = isset($_GET['lastCheck']) ? intval($_GET['lastCheck']) : 0;

if (!$userId) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

// First, check if notification_shown column exists
$columnExists = $conn->query("SHOW COLUMNS FROM user_connections LIKE 'notification_shown'")->num_rows > 0;

if (!$columnExists) {
    // Add the column if it doesn't exist
    $conn->query("ALTER TABLE user_connections ADD COLUMN notification_shown TINYINT(1) DEFAULT 0");
}

// Get pending friend requests that haven't been shown as notification
$stmt = $conn->prepare("
    SELECT uc.id, uc.user1, u.username, u.profilePic, uc.created_at
    FROM user_connections uc
    JOIN users u ON uc.user1 = u.id
    WHERE uc.user2 = ? AND uc.status = 'pendend' AND (uc.notification_shown = 0 OR uc.notification_shown IS NULL)
    ORDER BY uc.id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$newRequests = [];

while ($row = $result->fetch_assoc()) {
    $newRequests[] = [
        'id' => $row['id'],
        'userId' => $row['user1'],
        'username' => $row['username'],
        'profilePic' => $row['profilePic'],
        'timestamp' => strtotime($row['created_at'])
    ];
}

// Mark these requests as shown only if this is NOT a subsequent check (lastCheck > 0 means frontend already notified)
// Only mark as shown on first load (lastCheck == 0) to prevent duplicates
if (count($newRequests) > 0 && $lastCheck == 0) {
    $ids = array_column($newRequests, 'id');
    $idList = implode(',', $ids);
    $conn->query("UPDATE user_connections SET notification_shown = 1 WHERE id IN ($idList)");
    
    // Send push notification only on first load
    include_once 'PhpShits/simplePush.php';
    include_once 'PhpShits/pushNotificationFuncs.php';
    initPushNotifications($conn);
    
    $senderName = $newRequests[0]['username'];
    sendSimplePush(
        $conn, 
        $userId, 
        'Novo Pedido de Amizade', 
        "$senderName enviou um pedido de amizade", 
        '', 
        '/inbox'
    );
}

// For subsequent checks (lastCheck > 0), return empty array to prevent duplicate JS notifications
// The frontend will only show notifications on initial load
$response = [
    'success' => true,
    'newRequests' => $newRequests,
    'count' => count($newRequests),
    'lastCheck' => time()
];

echo json_encode($response);

$conn->close();
