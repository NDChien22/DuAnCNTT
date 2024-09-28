<?php
session_start();
include 'fnCSDL.php';

// Kiểm tra nếu người dùng đã gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Kết nối CSDL
    $conn = ConnectDB();

    // Kiểm tra xem người dùng đã tồn tại chưa
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $email]);

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Người dùng đã tồn tại');</script>";
        header('Location:index.php');
        exit();
    } else {
        // Thêm người dùng mới vào CSDL
        $sql = "INSERT INTO users (username, email, phone, password, user_type) VALUES (?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$username, $email, $phone, $password])) {
            echo "<script>alert('Đăng ký thành công');</script>";
            header('Location: index.php'); // Chuyển hướng đến trang chính
            exit();
        } else {
            echo "<script>alert('Có lỗi xảy ra. Vui lòng thử lại');</script>";
        }
    }
}
