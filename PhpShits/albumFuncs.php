<?php
function getUserAlbuns($conn, $UserId){
    $stmt = $conn->prepare("SELECT * FROM albuns WHERE user_id = $UserId");
    $stmt->execute();
    $result = $stmt->get_result();

    $albuns = [];

    while ($row = $result->fetch_assoc()) {
        $album = [];
        $album['Titulo'] = $row['album_name'];
        $album['Id'] = $row['id']; // útil pra depois

        $albuns[] = $album;
    }

    return $albuns;
}

function getLastPictureInAlbum($conn, $AlbumId){
    $stmt = $conn->prepare("SELECT * FROM albunsFoto WHERE album_id = ? ORDER BY id DESC;");
    $stmt->bind_param("i", $AlbumId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['url'];
    }
    else{
        return null;
    }
}
?>