<?php
    function getUserInfo($conn, $userId, $userAuthId = null){
        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            $user['id'] = $userId;
            $user['username'] = $row['username'];
            $user['biography'] = $row['biography'];
            $user['profilePic'] = $row['profilePic'];
            if(!is_null($userAuthId) && $userAuthId == $row['userAuthId']){
                $user['email'] = $row['email'];
            }
            return $user;
        } else {
            return null;
        }
    }

    function getLastUsersToEnterWebsite($conn, $page){
        $page  = max(1, (int)$page);
        $offset = ($page * 6) - 6;
        $stmt = $conn->prepare("SELECT id, username, profilePic FROM user ORDER BY id DESC LIMIT 6 OFFSET ?");
        $stmt->bind_param("i", $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 0) return [];

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }
?>