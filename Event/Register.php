<?php
// Include file database.php để sử dụng class Database
include "../Data/database.php";

// Bắt đầu phiên làm việc
session_start();

// Khởi tạo đối tượng Database
$db = new Database();

// Kiểm tra có dữ liệu GET được gửi từ liên kết đăng ký hay không
if (isset($_GET['msv']) && isset($_GET['eventID'])) {
    // Lấy MSV và ID của sự kiện từ dữ liệu GET
    $msv = $_GET['msv'];
    $eventID = $_GET['eventID'];

    // Kiểm tra xem MSV đã đăng ký sự kiện này chưa
    $query_check = "SELECT * FROM eventmembers WHERE MSV = '$msv' AND EventID = '$eventID'";
    $result_check = $db->select($query_check);
        // Nếu truy vấn thành công, kiểm tra số hàng trả về
        if ($result_check->num_rows == 0) {
            // Nếu chưa đăng ký, thêm MSV và ID của sự kiện vào bảng eventmembers
            $query_insert = "INSERT INTO eventmembers (MSV, EventID) VALUES ('$msv', '$eventID')";
            $result_insert = $db->insert($query_insert);

            if ($result_insert) {
                // Nếu thêm thành công, chuyển hướng người dùng đến trang sự kiện và thông báo đăng ký thành công
                $_SESSION['message'] = "Bạn đã đăng ký sự kiện.";
                header("Location: http://localhost/DALN/Event/Event.php");
                exit();
            } else {
                // Nếu có lỗi khi thêm vào cơ sở dữ liệu, thông báo lỗi
                $_SESSION['message'] = "Đã xảy ra lỗi khi đăng ký sự kiện.";
                header("Location: http://localhost/DALN/Event/Event.php");
                exit();
            }
        } else {
            // Nếu MSV đã đăng ký sự kiện này, thông báo cho người dùng
            $_SESSION['message'] = "Bạn đã đăng ký sự kiện này trước đó.";
            header("Location: http://localhost/DALN/Event/Event.php");
            exit();
        }
    } else {
        // Nếu có lỗi trong quá trình truy vấn, thông báo lỗi
        $_SESSION['message'] = "Có lỗi khi kiểm tra đăng ký sự kiện.";
        header("Location: http://localhost/DALN/Event/Event.php");
        exit();
    } 
?>
