<?php
session_start();
ob_start();
include "../Data/database.php";

// Khởi tạo biến lưu trạng thái thông báo
$error_message = "";

// Kiểm tra kết nối trước khi sử dụng
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login']) && $_POST['login']) {
    // Lấy dữ liệu từ form
    $user = $_POST['Mail'];
    $pass = $_POST['Pass'];

    // Escape user input to prevent SQL injection
    $user = mysqli_real_escape_string($connection, $user);
    $pass = mysqli_real_escape_string($connection, $pass);

    // Sử dụng prepared statement để ngăn chặn SQL injection
    $query = "SELECT * FROM tbl_acc WHERE Mail = ? AND Pass = ?";
    $stmt = $connection->prepare($query);

    // Bind parameters
    $stmt->bind_param("ss", $user, $pass);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Kiểm tra xem có dòng dữ liệu trả về hay không
    if ($result->fetch_assoc()) {
        // Xác thực thành công
        // Lưu thông tin người dùng vào session
        $_SESSION['user'] = $user;
        // $_SESSION['role'] = ...; // Bạn có thể lưu Role vào session nếu cần

        // Chuyển hướng đến trang Home.php
        header("Location: ../Home/Home.php");
        exit(); // Đảm bảo không có mã PHP tiếp theo được thực thi sau header
    } else {
        // Không có dữ liệu trả về, xác thực thất bại
        $error_message = "Tài khoản hoặc mật khẩu không đúng. Vui lòng thử lại.";
    }

    // Đóng prepared statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Login.css">
    <link rel="stylesheet" href="http://localhost/DALN/fontawesome-free-6.4.2-web/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <!-- Thêm mã JavaScript để hiển thị thông báo lỗi -->
    <script>
        $(document).ready(function(){
            <?php
            // Kiểm tra nếu có thông báo lỗi, thì hiển thị khung thông báo
            if (!empty($error_message)) {
                echo "alert('$error_message');";
            }
            ?>
        });
    </script>
</head>
<body>
    <div class="wrapper wrapper-login">
        <div class="content-login">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="Login">
                <div class="logo-Phenikaa-w justify-content-center d-flex">
                    <img src="http://localhost/DALN/img/logo-Phenikaa-w.png" class="img-logo-w">
                </div>
                <!-- content -->
                <div class="content-form-login position-relative">
                    <h2 class="title-h2">Đăng nhập</h2>
                    <div class="form-item position-relative mb-20">
                        <label for="username" class="form-label"><i class="fa-regular fa-user"></i></label>
                        <input name="Mail" type="text" value="" id="Mail" class="form-control" aria-describedby="Nhập tài khoản hoặc email" placeholder="Nhập tài khoản hoặc email">
                    </div>
                    <div class="form-item position-relative mb-20">
                        <label for="password" class="form-label"><i class="fa-solid fa-key"></i></label>
                        <input name="Pass" value="" id="Pass" type="password" class="form-control" placeholder="Nhập mật khẩu">
                    </div>
                    <input type="submit" name="login" value="Đăng nhập" id="login" class="btn btn-primary btn-login">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
