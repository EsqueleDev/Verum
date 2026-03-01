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

    function getUserLikes($conn, $userId){
        $likesMap = [
            1 => 'Arte Digital', 2 => 'Fotografias', 3 => 'Comics', 4 => 'OCS', 5 => 'Fanarts', 6 => 'Pixel Art',
            7 => 'Webcomic', 8 => 'Naruto', 9 => 'Jujutsu Kaisen', 10 => 'Jojo', 11 => 'My Hero Academy', 12 => 'Bungou Stray Dogs',
            13 => 'Engraçadinhos', 14 => 'shitpost', 15 => 'Humor Acido',
            16 => 'Percy Jackson', 17 => 'Book Worm', 18 => 'Mangas', 19 => 'Romances', 20 => 'Biografia', 21 => 'Poesias',
            22 => 'Minecraft', 23 => 'FNAF', 24 => 'Sims', 25 => 'DND', 26 => 'LOL', 27 => 'Indie',
            28 => 'Emo', 29 => 'Rap', 30 => 'Kpop', 31 => 'Indie Music', 32 => 'Vocaloid', 33 => 'Pop',
            34 => 'Arcane', 35 => 'The Own House', 36 => 'TADC', 37 => 'Supernatural', 38 => 'The Office', 39 => 'Animações'
        ];
        
        $stmt = $conn->prepare("SELECT likes_id FROM users_likes WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $likes = [];
        while ($row = $result->fetch_assoc()) {
            if (isset($likesMap[$row['likes_id']])) {
                $likes[] = $likesMap[$row['likes_id']];
            }
        }
        return $likes;
    }

    function getLastUsersToEnterWebsite($conn, $page){
        $page  = max(1, (int)$page);
        $offset = ($page * 6) - 6;
        $stmt = $conn->prepare("SELECT id, username, profilePic FROM user ORDER BY id DESC LIMIT 3 OFFSET ?");
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