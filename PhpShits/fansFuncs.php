<?php
    function checkIfUserFollows($conn, $UserId, $ProfileId){
        $stmt = $conn->prepare("SELECT * FROM usuario_seguidor_perfil WHERE (howFollows = ? AND page = ?);");
        $stmt->bind_param("ii", $UserId, $ProfileId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return true;
        } else {
            return false;
        }
    }
    
    function startFollowPage($conn, $UserId, $ProfileId){
        if(!checkIfUserFollows($conn, $UserId, $ProfileId)){
            $stmt = $conn->prepare("INSERT INTO usuario_seguidor_perfil (howFollows, page) VALUES (?, ?)");
            $stmt->bind_param("ii", $UserId, $ProfileId);
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
    
    function stopFollowPage($conn, $UserId, $ProfileId){
        if(!checkIfUserFollows($conn, $UserId, $ProfileId)){
            $stmt = $conn->prepare("DELETE FROM `usuario_seguidor_perfil` WHERE howFollows = ?, page = ?;");
            $stmt->bind_param("ii", $UserId, $ProfileId);
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
?>