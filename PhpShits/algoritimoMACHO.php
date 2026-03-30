<?php
function algoritmoGeralSite($conn, $userId, $page, $limit){
    $page  = max(1, (int)$page);
    $limit = max(1, (int)$limit);
    $offset = ($page * $limit) - $limit;

    // 🔹 1. Buscar amigos
    $friends = [];
    $stmtFriends = $conn->prepare("
        SELECT user1, user2 
        FROM user_connections 
        WHERE (user1 = ? OR user2 = ?) 
        AND status = 'accepted'
    ");
    $stmtFriends->bind_param("ii", $userId, $userId);
    $stmtFriends->execute();
    $resultFriends = $stmtFriends->get_result();

    while($row = $resultFriends->fetch_assoc()){
        $friends[] = ($row['user1'] == $userId) ? $row['user2'] : $row['user1'];
    }

    // 🔹 2. Buscar tags seguidas (ids)
    $tags = [];
    $tags = getUserLikes($conn, $userId, $lingua = "PT_BR");

    // 🔹 3. Montar condições
    $conditions = [];
    $params = [];
    $types = "";

    // 🔸 meus posts
    $conditions[] = "p.userId = ?";
    $params[] = $userId;
    $types .= "i";

    // 🔸 amigos
    if(!empty($friends)){
        $in = implode(",", array_fill(0, count($friends), "?"));
        $conditions[] = "p.userId IN ($in)";
        foreach($friends as $f){
            $params[] = $f;
            $types .= "i";
        }
    }

    // 🔸 tags (usando FIND_IN_SET)
    if(!empty($tags)){
        $tagParts = [];
        foreach($tags as $tag){
            $tagParts[] = "FIND_IN_SET(?, p.tagPost)";
            $params[] = $tag['id'];
            $types .= "i";
        }
        $conditions[] = "(" . implode(" OR ", $tagParts) . ")";
    }

    $where = implode(" OR ", $conditions);

    // 🔹 4. Query final com JOIN
    $sql = "
        SELECT p.*, u.username, u.profilePic
        FROM post p
        JOIN users u ON u.id = p.userId
        WHERE p.postApagado = 0
        AND ($where)
        ORDER BY p.postTime DESC
        LIMIT ? OFFSET ?
    ";

    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 0) return [];

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $row['user'] = [
            'username' => $row['username'],
            'profilePic' => $row['profilePic']
        ];
        $posts[] = $row;
    }

    return $posts;
}
?>