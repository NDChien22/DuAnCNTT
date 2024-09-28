<?php
session_start();
include_once 'fnCSDL.php';

// Thiết lập múi giờ mặc định
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Retrieve POST data
$tour_id = isset($_POST['tour_id']) ? intval($_POST['tour_id']) : 0;
$departure_date = isset($_POST['departure_date']) ? $_POST['departure_date'] : '';
$total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
$payment_method = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
$payment_date = date('Y-m-d H:i:s');

try {
    // Connect to database
    $conn = ConnectDB();
    $conn->beginTransaction();

    // Insert booking 
    $insertBooking = $conn->prepare("INSERT INTO bookings (user_id, tour_id, booking_date, departure_date, quantity, total_price,booking_status) VALUES (?, ?, ?, ?, ?, ?,'pending')");
    $insertBooking->execute([$_SESSION['user_id'], $tour_id, $payment_date, $departure_date, $quantity, $total_price]);
    $booking_id = $conn->lastInsertId(); // Lấy booking_id

    // Insert customer 
    $insertCustomer = $conn->prepare("INSERT INTO customers (first_name, last_name, id_card_number, phone, booking_id) VALUES (?, ?, ?, ?,'$booking_id')");
    $insertCustomerTour = $conn->prepare("INSERT INTO customertours (customer_id, tour_id, departure_date) VALUES (?, ?, ?)");

    foreach ($_POST['customers'] as $customer) {
        $insertCustomer->execute([$customer['first_name'], $customer['last_name'], $customer['id_card_number'], $customer['phone']]);
        $customer_id = $conn->lastInsertId();

        // liên kết customer tới bảng tour và booking
        $insertCustomerTour->execute([$customer_id, $tour_id, $departure_date]);
    }

    // Insert payment 
    $insertPayment = $conn->prepare("INSERT INTO payments (booking_id, amount, payment_method, payment_date) VALUES (?, ?, ?, ?)");
    $insertPayment->execute([$booking_id, $total_price, $payment_method, $payment_date]);

    // Commit transaction
    $conn->commit();
    header('Location: confirmation.php'); //Thông báo đặt tour thành công
} catch (Exception $e) {
    $conn->rollBack();
    echo 'Failed: ' . $e->getMessage();
}
