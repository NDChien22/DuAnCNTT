<?php 
// Hàm kết nối với db
function ConnectDB(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tour_booking";

    $conn = NULL;
    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Đặt chế độ lỗi PDO thành ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "<h3>Kết nối CSDL thành công</h3>";
    } catch(PDOException $e){
        die("<p>Lỗi kết nối CSDL: " . $e->getMessage() . "</p>");
    }
    return $conn;
}

// Truy vấn cơ sở dữ liệu với các câu lệnh INSERT, UPDATE, DELETE
function executeQuery($sql, $params = []) {
    try {
        $conn = ConnectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return true;
    } catch (PDOException $e) {
        echo "Lỗi truy vấn: " . $e->getMessage();
        return false;
    }
}

// Lấy dữ liệu từ cơ sở dữ liệu (SELECT)
function getResults($sql, $params = []) {
    try {
        $conn = ConnectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Lỗi truy vấn: " . $e->getMessage();
        return [];
    }
}

// Hàm kiểm tra người dùng (đã có)
function CheckUser($username, $password){
    $conn = ConnectDB();
    $sql = "SELECT * FROM users WHERE username = ?";
    $pdostm = $conn->prepare($sql);
    $pdostm->execute([$username]);

    if($pdostm->rowCount() > 0){
        $user = $pdostm->fetch();
        if (password_verify($password, $user['password'])) {
            return $user;
        } else {
            return NULL;
        }
    } else {
        return NULL;
    }
}
?>
