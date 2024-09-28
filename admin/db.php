<?php

// Database configuration
$host = 'localhost';      
$db_name = 'tour_booking';  
$username = 'root';        
$password = '';             

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Set UTF-8 encoding for database connection
$conn->set_charset("utf8");
