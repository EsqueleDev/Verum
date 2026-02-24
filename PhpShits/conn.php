<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "verum";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo "<script>window.location.href = 'serverfallback.php?error=serverConnection';</script>";
    }
?>