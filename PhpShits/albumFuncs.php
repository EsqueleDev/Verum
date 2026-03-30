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

function getAlbumPics($conn, $AlbumId){
    $stmt = $conn->prepare("SELECT * FROM albunsFoto WHERE album_id = $AlbumId");
    $stmt->execute();
    $result = $stmt->get_result();

    $albuns = [];

    while ($row = $result->fetch_assoc()) {
        $pic = [];
        $pic['url'] = $row['url'];
        $pic['Id'] = $row['id']; // útil pra depois

        $pics[] = $pic;
    }

    return $pics;
}

function getLastPictureInAlbum($conn, $AlbumId){
    $stmt = $conn->prepare("SELECT * FROM albunsFoto WHERE album_id = ? ORDER BY id DESC LIMIT 1;");
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

function createAlbum($conn, $AlbumName, $UserId){
    $stmt = $conn->prepare("INSERT INTO albuns (user_id, album_name) VALUES (?, ?)");
    $stmt->bind_param("is", $UserId, $AlbumName);
    $stmt->execute();
    $stmt->close();
}

function getOneAlbumWithName($conn, $AlbumName, $UserId){
    $stmt = $conn->prepare("SELECT * FROM albuns WHERE user_id = ? AND album_name = ? ORDER BY id DESC;");
    $stmt->bind_param("is", $UserId, $AlbumName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    else{
        return null;
    }
}

function addOnePicToAnAlbum($conn, $albumId){
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if(in_array($fileExtension, $allowed)){

            $newFileName = 'album_' . $albumId . '_' . time() . '.' . $fileExtension;

            // CAMINHO FÍSICO
            $uploadDir = __DIR__ . '/../uploads/albuns/';

            // URL
            $urlPath = 'uploads/albuns/';

            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0755, true);
            }

            $dest_path = $uploadDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)){

                $url = $urlPath . $newFileName;

                $stmt = $conn->prepare("INSERT INTO albunsFoto (album_id, url) VALUES (?, ?)");
                $stmt->bind_param("is", $albumId, $url);
                $stmt->execute();

                return true;
            }
        }
    }

    return false;
}

function checkIfImAreTheADM($conn, $UserId, $AlbumId){
    $stmt = $conn->prepare("SELECT * FROM albuns WHERE user_id = ? AND id = ? ORDER BY id DESC;");
    $stmt->bind_param("ii", $UserId, $AlbumId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return true;
    }
    else{
        return false;
    }
}
?>