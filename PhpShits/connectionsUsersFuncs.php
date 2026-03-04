<?php
    function getUserConnectionInfo($conn, $userId1, $userId2){
        $stmt = $conn->prepare("SELECT * FROM user_connections WHERE (user1 = ? AND user2 = ?) OR (user2 = ? AND user1 = ?)");
        $stmt->bind_param("iiii", $userId1, $userId2, $userId1, $userId2);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            $connection['user1'] = $row['user1'];
            $connection['user2'] = $row['user2'];
            $connection['didISendThisRequest'] = ($row['user1'] == $userId1);
            $connection['status'] = $row['status'];
            return $connection;
        } else {
            return null;
        }
    }
    function sendAFriendRequest($conn, $howSend, $howReceive){
        if(is_null(getUserConnectionInfo($conn, $howSend, $howReceive))){
            $stmt = $conn->prepare("INSERT INTO user_connections (user1, user2, status) VALUES (?, ?, 'pendend')");
            $stmt->bind_param("ii", $howSend, $howReceive);
            if ($stmt->execute()) {
                // Send push notification to the recipient
                sendFriendRequestNotification($conn, $howSend, $howReceive);
                return true;
            }
            else{
                return false;
            }
            $stmt->close();
        }
        else{
            return false;
        }
    }

    // Send push notification when a friend request is made
    function sendFriendRequestNotification($conn, $senderId, $recipientId) {
        // Get sender info
        $stmt = $conn->prepare("SELECT username, profilePic FROM users WHERE id = ?");
        $stmt->bind_param("i", $senderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $sender = $result->fetch_assoc();
        $stmt->close();

        if ($sender) {
            // Mark notification as not shown (so it can be detected later)
            $stmt = $conn->prepare("UPDATE user_connections SET notification_shown = 0 WHERE user1 = ? AND user2 = ? AND status = 'pendend'");
            $stmt->bind_param("ii", $senderId, $recipientId);
            $stmt->execute();
            $stmt->close();
            
            // Try to send OneSignal push notification
            include_once 'onesignal-push.php';
            $title = 'Novo Pedido de Amizade';
            $message = $sender['username'] . ' enviou um pedido de amizade';
            $url = '/inbox';
            
            sendOneSignalNotification($recipientId, $title, $message, $url);
            
            // Log for debugging
            error_log("Friend request notification triggered: From user $senderId to user $recipientId");
        }
    }
    
    function changeFriendRequestStatus($conn, $howSend, $howReceive, $newStatus){
        $connection = getUserConnectionInfo($conn, $howSend, $howReceive);
        if(!is_null($connection)){
            $stmt = $conn->prepare("UPDATE user_connections SET status = ? WHERE user1 = ? AND user2 = ?");
            $stmt->bind_param("sii", $newStatus, $howSend, $howReceive);
            if ($stmt->execute()) {
                return true;
            }
            else{
                return false;
            }
            $stmt->close();
        }
        else{
            return false;
        }
    }
    
    function retryFriendRequestStatus($conn, $howSend, $howReceive){
        $connection = getUserConnectionInfo($conn, $howSend, $howReceive);
        if(!is_null($connection)){
            $stmt = $conn->prepare("DELETE FROM user_connections WHERE (user1 = ? AND user2 = ?) OR (user2 = ? AND user1 = ?)");
            $stmt->bind_param("iiii", $howSend, $howReceive, $howSend, $howReceive);
            if ($stmt->execute()) {
                return true;
            }
            else{
                return false;
            }
            $stmt->close();
        }
        else{
            return false;
        }
    }

    function getAllFriendRequest($conn, $userId){
        $stmt = $conn->prepare("
            SELECT *
            FROM user_connections
            WHERE user2 = ? AND status = 'pendend'
            ORDER BY id DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 0) return null;

        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }

        return $requests;
    }

    function checkNewRequests($conn, $userId){
        $stmt = $conn->prepare("SELECT * FROM user_connections WHERE user2 = ? AND status = 'pendend'");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            return true;
        } else {
            return false;
        }
    }

    function getAllFriendFromUser($conn, $userId){
        $stmt = $conn->prepare("
            SELECT *
            FROM user_connections
            WHERE (user2 = ? OR user1 = ?) AND status = 'accepted'
            ORDER BY id DESC
        ");
        
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 0) return [];

        $requests = [];

        while ($row = $result->fetch_assoc()) {

            if ($row['user1'] == $userId) {
                $row['friend_id'] = $row['user2'];
            } else {
                $row['friend_id'] = $row['user1'];
            }

            $requests[] = $row;
        }

        return $requests;
    }
?>