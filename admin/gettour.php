<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tour_booking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['tour_id'])) {
    $tour_id = $_GET['tour_id'];
    $sql = "SELECT * FROM tours WHERE tour_id = '$tour_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $tour = $result->fetch_assoc();
        echo json_encode($tour);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
