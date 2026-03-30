<?php
    function getArtistDraws($conn, $ArtistID){
        $stmt = $conn->prepare("SELECT * FROM artista_desenho WHERE UserId = $ArtistID");
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $draw = [];
            $draw['url'] = $row['URL_DESENHO'];
            $draw['titulo'] = $row['Titulo']; // útil pra depois
    
            $draws[] = $draw;
        }
    
        return $draws;
    }
?>