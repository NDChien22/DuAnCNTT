<?php
session_start();
include 'fnCSDL.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kết nối CSDL và kiểm tra người dùng
    $conn = ConnectDB();
    $sql = "SELECT * FROM users WHERE username = ? AND password = ? AND user_type = 'user'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $password]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['username'] = $user['username'];

        // Cập nhật trạng thái booking
        $sql = "UPDATE bookings 
                SET booking_status = 'complete' 
                WHERE booking_status = 'pending' 
                AND departure_date <= NOW()";
        $conn->exec($sql);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng']);
    }
    exit();
}
