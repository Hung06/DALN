<?php
include "../Data/database.php";

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Initialize message variable
$message = "";

// URL hiện tại
$current_url = $_SERVER['REQUEST_URI'];

// Kiểm tra xem MSV và action đã được gửi từ client hay không
if (isset($_GET['MSV']) && isset($_GET['action'])) {
    // Lấy giá trị MSV và action từ client
    $MSV = $_GET['MSV'];
    $action = $_GET['action'];

    // Kiểm tra hành động và thực thi tương ứng
    if ($action === 'reject') {
        // Cập nhật trạng thái của MSV tương ứng thành "Từ chối"
        $sql_delete_member = "DELETE FROM clubmembers WHERE MSV = ?";
        $result = $db->deletePrepared($sql_delete_member, [$MSV]);
        
        if ($result) {
            $message = "Thực hiện hành động từ chối thành công";
            echo "<script>alert('$message');history.back();</script>";
        } else {
            $message = "Thực hiện hành động từ chối không thành công";
        }
    } elseif ($action === 'approve') {
        // Cập nhật trạng thái của MSV tương ứng thành "Thành công"
        $sql_update_member_status = "UPDATE clubmembers SET Status = 'Thành công' WHERE MSV = ?";
        $result = $db->updatePrepared($sql_update_member_status, [$MSV]);
        
        if ($result) {
            $message = "Thực hiện hành động duyệt thành công";
            echo "<script>alert('$message');history.back();</script>";
        } else {
            $message = "Thực hiện hành động duyệt không thành công";
        }
    } elseif ($action === 'delete') {
        // Xóa hàng có MSV tương ứng
        $sql_delete_member = "DELETE FROM clubmembers WHERE MSV = ?";
        $result = $db->deletePrepared($sql_delete_member, [$MSV]);
        
        if ($result) {
            $message = "Thực hiện hành động xóa thành công";
            // Chuyển hướng trở lại trang trước đó sau khi xóa thành công
            echo "<script>alert('$message');window.history.go(-1);</script>";
            exit(); // Dừng script
        } else {
            $message = "Thực hiện hành động xóa không thành công";
        }
    }
}

// Hiển thị thông báo
echo "<script>alert('$message');</script>";
?>
