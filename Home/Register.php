<?php
// Include file database.php
include "../Data/database.php";

// Start the session
session_start();

// Kiểm tra xem đã đăng nhập hay chưa
if (!isset($_SESSION['MSV'])) {
    // Nếu chưa đăng nhập, chuyển hướng người dùng đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng script
}

$message = "";

// Kiểm tra xem ClubID có được truyền qua tham số không
if(isset($_GET['ClubID'])) {
    // Lấy giá trị ClubID từ tham số truyền vào
    $clubID = $_GET['ClubID'];

    // Lấy MSV của người dùng hiện tại từ session
    $MSV = $_SESSION['MSV'];

    // Instantiate the Database class
    $db = new Database();

    // Kiểm tra xem người dùng đã đăng ký tham gia câu lạc bộ này chưa
    $query_check_registration = "SELECT * FROM clubmembers WHERE ClubID = $clubID AND MSV = '$MSV'";
    $result_check_registration = $db->select($query_check_registration);

    if ($result_check_registration && $result_check_registration->num_rows > 0) {
        $message = "Bạn đã đăng ký tham gia câu lạc bộ này trước đó.";
    } else {
        // Nếu chưa đăng ký, thêm thông tin đăng ký vào bảng clubmembers với trạng thái "Chờ duyệt"
        $query_register = "INSERT INTO clubmembers (MSV, ClubID, Status) VALUES ('$MSV', $clubID, 'Chờ duyệt')";
        $result_register = $db->insert($query_register);

        if ($result_register) {
            $message = "Đăng ký tham gia câu lạc bộ thành công. Đang chờ duyệt.";
        } else {
            $message = "Đã xảy ra lỗi trong quá trình đăng ký. Vui lòng thử lại sau.";
        }
    }
} else {
    $message = "Lỗi: Không có ClubID được cung cấp.";
}

// Hiển thị thông báo
echo "<script>alert('$message');</script>";

// Chuyển hướng người dùng đến trang Home.php
echo "<script>window.location = 'http://localhost/DALN/Home/Home.php';</script>";
exit(); // Dừng script
?>
