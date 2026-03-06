<?php
    function getUserInfo($conn, $userId, $userAuthId = null){
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            $user['id'] = $userId;
            $user['username'] = $row['username'];
            $user['biography'] = $row['biography'];
            $user['profilePic'] = $row['profilePic'];
            $user['livros'] = $row['livros'];
            $user['musicas'] = $row['musicas'];
            $user['filmes'] = $row['filmes'];
            if(!is_null($userAuthId) && $userAuthId == $row['userAuthId']){
                $user['email'] = $row['email'];
            }
            return $user;
        } else {
            return null;
        }
    }

    function getUserLikes($conn, $userId, $lingua = "PT_BR"){
    
        $linguasPermitidas = ["PT_BR", "EN", "ES"];
    
        if(!in_array($lingua, $linguasPermitidas)){
            $lingua = "PT_BR";
        }
    
        $colNome = "nome_" . $lingua;
        $colSub = "subRedditRelacionado" . $lingua;
    
        $sql = "
            SELECT l.$colNome AS nome, l.$colSub AS subreddit
            FROM users_likes ul
            JOIN likes l ON ul.likes_id = l.id
            WHERE ul.user_id = ?
        ";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $likes = [];
    
        while ($row = $result->fetch_assoc()) {
            $likes[] = [
                "nome" => $row["nome"],
                "subreddit" => $row["subreddit"]
            ];
        }
    
        return $likes;
    }

    function getLastUsersToEnterWebsite($conn, $page){
        $page  = max(1, (int)$page);
        $offset = ($page * 6) - 6;
        $stmt = $conn->prepare("SELECT id, username, profilePic FROM users ORDER BY id DESC LIMIT 3 OFFSET ?");
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