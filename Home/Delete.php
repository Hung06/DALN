<?php
include "../Data/database.php";

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Kiểm tra xem ClubID đã được gửi đến từ client hay không
if (isset($_GET['clubID'])) {
    // Xác định ClubID của dữ liệu cần xóa
    $clubID = $_GET['clubID'];

    // Tạo câu lệnh SQL để xóa dữ liệu
    $sql = "DELETE FROM clubs WHERE ClubID = $clubID";

    // Thực hiện câu lệnh SQL
    $result = $db->delete($sql);

        // Chuyển hướng trình duyệt để làm mới trang web sau khi xóa thành công
    echo "<script>window.location.href = 'http://localhost/DALN/Home/Home.php';</script>";
    $message = "Thêm thành công";
}
?>
