<?php
    function getGroupInfo($conn, $groupId){
        $stmt = $conn->prepare("SELECT * FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            $group['nome'] = $row['nome'];
            $group['descricao'] = $row['descricao'];
            $group['imagemUrl'] = $row['imagemUrl'];
            $group['oculto'] = $row['oculto'];
            $group['admID'] = $row['admID'];
            
            return $group;
        } else {
            return null;
        }
    }
?>