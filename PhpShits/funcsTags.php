<?php
    function createTag($conn, $tag){
        $stmt = $conn->prepare("INSERT INTO likes (nome_PT_BR, nome_ENG) VALUES (?, ?)");
        $stmt->bind_param("ss", $tag, $tag);
        $stmt->execute();
        return $conn->insert_id;
    }
    
    function findTag($conn, $tag){
        $stmt = $conn->prepare("SELECT * FROM likes WHERE nome_PT_BR = ? OR nome_ENG = ?");
        $stmt->bind_param("ss", $tag, $tag);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return createTag($conn, $tag);
        }
    }
    
    function getTagName($conn, $tagId, $language){
        $stmt = $conn->prepare("SELECT * FROM likes WHERE id = ?;");
        $stmt->bind_param("i", $tagId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["nome_$language"];
        } else {
            return 'Oh No, post corrupted.';
        }
    }
?>