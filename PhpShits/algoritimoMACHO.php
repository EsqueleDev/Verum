<?php
    function algoritmoGeralSite($conn, $userId, $page, $limit){
        $page  = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page * $limit) - $limit;

        $stmt = $conn->prepare("
            SELECT *
            FROM post
            ORDER BY id DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 0) return [];

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            // Get user info for each post
            $userStmt = $conn->prepare("SELECT username, profilePic FROM user WHERE id = ?");
            $userStmt->bind_param("i", $row['userId']);
            $userStmt->execute();
            $userResult = $userStmt->get_result();
            $user = $userResult->fetch_assoc();
            $userStmt->close();
            
            // Add user data to post
            $row['user'] = [
                'username' => $user['username'] ?? 'Usuário',
                'profilePic' => $user['profilePic'] ?? ''
            ];
            $posts[] = $row;
        }

        return $posts;
    }

?>