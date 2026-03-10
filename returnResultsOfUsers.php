<?php
header('Content-Type: application/json; charset=utf-8');

// returnResultsOfUsers.php
include("PhpShits/conn.php");
include("PhpShits/userFunctions.php");

$parameter = $_GET['parameter'] ?? '';

$search = "%" . $parameter . "%";

$stmt = $conn->prepare("SELECT id, username, profilePic FROM users WHERE username LIKE ? LIMIT 3");
$stmt->bind_param("s", $search);
$stmt->execute();

$result = $stmt->get_result();

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>