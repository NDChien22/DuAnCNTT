<?php
session_start();
include_once 'fnCSDL.php';
include_once 'get_tour.php';
// Kiểm tra đăng nhập
$isLoggedIn = isset($_SESSION['user_email']);

$displayedTour = array_slice($tours, 0, 6);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Trang chủ</title>
</head>

<body class="p-1">
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="index.php">CTTravel</a>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Trang chủ</a>
                        </li>
                        <?php if ($isLoggedIn): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="tour_history.php">Lịch sử đặt tour</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="support.php">Liên hệ hỗ trợ</a>
                            </li>
                        <?php else: ?>
                            <!-- Nếu chưa đăng nhập, chuyển hướng đến form đăng nhập -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Lịch sử đặt tour</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Liên hệ hỗ trợ</a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <!-- User Login/Logout -->
                    <form class="d-flex" role="login">
                        <?php if ($isLoggedIn): ?>
                            <p class="d-flex justify-content-center m-2">
                                <a href="profile.php" class="link-offset-2 link-underline link-underline-opacity-0"><?php echo $_SESSION['username']; ?></a>
                            </p>
                            <a href="logout.php" class="btn btn-outline-danger">Đăng xuất</a>
                        <?php else: ?>
                            <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <!-- Modal Login Form -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Đăng nhập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Login Form -->
                    <div id="loginForm">
                        <form id="loginFormElement" action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div id="error-message" class="text-danger mt-2"></div>
                            </div>
                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                            <div class="mt-3">
                                <a href="#" class="text-decoration-none" onclick="showForgotPassword()">Quên mật khẩu?</a>
                            </div>
                            <div class="mt-3">
                                <p class="mb-0">Chưa có tài khoản? <a href="#" class="text-decoration-none" onclick="showRegisterForm()">Đăng ký ngay</a></p>
                            </div>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div id="registerForm" style="display: none;">
                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label for="regUsername" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="regUsername" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="regEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="regEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="regPhone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="regPhone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="regPassword" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="regPassword" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Đăng ký</button>
                            <div class="mt-3">
                                <p class="mb-0">Đã có tài khoản? <a href="#" class="text-decoration-none" onclick="showLoginForm()">Đăng nhập ngay</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function showRegisterForm() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
        }

        function showLoginForm() {
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        }

        document.getElementById('loginFormElement').onsubmit = function(event) {
            event.preventDefault(); // Ngăn chặn form gửi dữ liệu

            var xhr = new XMLHttpRequest();
            var formData = new FormData(this);

            xhr.open('POST', 'login.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Nếu đăng nhập thành công, reload lại trang hiện tại
                        window.location.reload();
                    } else {
                        // Hiển thị lỗi và focus vào username
                        document.getElementById('error-message').textContent = response.message;
                        document.getElementById('username').focus();
                    }
                }
            };
            xhr.send(formData);
        };
    </script>





    <!-- Main Content -->
    <div class="top-content d-flex justify-content-center align-items-center mt-3">
        <form class="d-flex justify-content-center" method="GET" action="tour_list.php">
            <input class="form-control me-2" type="text" name="search" placeholder="Tìm kiếm" aria-label="Search">
            <button class="btn btn-success" type="submit">Tìm kiếm</button>
        </form>

    </div>

    <div class="container">
        <div class="content mt-4">
            <h2 class="text-center mb-4">Các tour nổi bật</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($displayedTour as $tour): ?>
                    <div class="col-md-4 mb-4">
                        <div class="tour-card card">
                            <img src="<?php echo htmlspecialchars($tour['url_img']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>" class="card-img-top img-fluid">
                            <div class="tour-details">
                                <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                                <p>Thời gian: <?php echo htmlspecialchars($tour['duration']); ?> ngày <?php echo htmlspecialchars($tour['duration'] - 1); ?> đêm </p>
                                <p>Giá: <?php echo htmlspecialchars(number_format($tour['price'], 0, ',', '.')); ?> VNĐ</p>
                                <a href="tour_details.php?id=<?php echo htmlspecialchars($tour['tour_id']); ?>" class="btn btn-primary">Xem Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Link to Tour List -->
            <div class="d-flex justify-content-start mt-4">
                <a href="tour_list.php" class="btn btn-outline-primary btn-lg">Xem Tất Cả Các Tour</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black text-center text-light mt-5">
        <p class="mb-0 p-3">Copyright © 2022 My Website. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>