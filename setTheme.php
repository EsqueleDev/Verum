<?php
    header('Content-Type: application/json');
    include 'PhpShits/conn.php';
    
    $allowedThemes = ['Purple', 'Orange', 'Yellow', 'Ciano', 'Blue', 'Green', 'Red', 'Pink'];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $theme = $_POST['theme'] ?? '';
        $userId = $_COOKIE['UserId'] ?? null;
        
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            exit;
        }
        
        if (!in_array($theme, $allowedThemes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid theme']);
            exit;
        }
        
        // Check if theme column exists, if not add it
        $checkColumn = $conn->query("SHOW COLUMNS FROM user LIKE 'theme'");
        if ($checkColumn->num_rows === 0) {
            $conn->query("ALTER TABLE user ADD COLUMN theme VARCHAR(20) DEFAULT 'Purple'");
        }
        
        // Update theme in database
        $stmt = $conn->prepare("UPDATE user SET theme = ? WHERE id = ?");
        $stmt->bind_param("si", $theme, $userId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Theme updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update theme']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
    
    $conn->close();
?>
