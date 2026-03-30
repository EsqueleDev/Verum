<?php
    function loadOnePost($conn, $PostId){
        $stmt = $conn->prepare("SELECT * FROM post WHERE id = ?;");
        $stmt->bind_param("i", $PostId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            return $row;
        }
        else{
            return null;
        }
    }
    
    function getCommentsFromPost($conn, $PostId){
        $stmt = $conn->prepare("SELECT * FROM post_comentarios WHERE PostId = $PostId");
        $stmt->execute();
        $result = $stmt->get_result();
    
        $comments = [];
    
        while ($row = $result->fetch_assoc()) {
            $comment = [];
            $comment['UserId'] = $row['UserId'];
            $comment['Texto'] = $row['Texto']; // útil pra depois
    
            $comments[] = $comment;
        }
    
        return $comments;
    }
    
    function insertAnCommentary($conn, $UserId, $PostId, $Text){
        $stmt = $conn->prepare("INSERT INTO post_comentarios (UserId, PostId, Texto) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $UserId, $PostId, $Text);
        $stmt->execute();
        $stmt->close();
    }
?>