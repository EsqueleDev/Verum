<?php
    function algoritmoDeDadosEspecificos($conn, $somethingId, $userOrGroup = 'user'){
        if($userOrGroup == 'user'){
            $stmt = $conn->prepare("
                SELECT *
                FROM post
                WHERE userId = ?
                ORDER BY id DESC
            ");
        }
        else if($userOrGroup == 'group'){
            $stmt = $conn->prepare("
                SELECT *
                FROM post
                WHERE grupoReferencia = ?
                ORDER BY id DESC
            ");
        }
        
        $stmt->bind_param("i", $somethingId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 0) return [];

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }

    function getOnePost($conn, $postId){

        $stmt = $conn->prepare("
            SELECT *
            FROM post
            WHERE id = ?
        ");
        
        $stmt->bind_param("i", $postId);
        $stmt->execute();

        $result = $stmt->get_result();

        return $post;
    }
?>