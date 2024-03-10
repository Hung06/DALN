<?php
include "../Data/database.php";

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Initialize message variable
$message = "";

// Kiểm tra xem ClubID và MSV đã được gửi đến từ client hay không
if (isset($_GET['clubID']) && isset($_GET['MSV'])) {
    // Xác định ClubID và MSV của dữ liệu cần xóa
    $clubID = $_GET['clubID'];
    $msv = $_GET['MSV'];

    // Tạo câu lệnh SQL để xóa dữ liệu trong bảng clubmembers có ClubID tương ứng
    $sql_members = "DELETE FROM clubmembers WHERE ClubID = $clubID";

    // Thực hiện câu lệnh SQL xóa dữ liệu trong bảng clubmembers
    $result_members = $db->delete($sql_members);

    // Kiểm tra kết quả xóa dữ liệu trong bảng clubmembers
    if ($result_members) {
        // Nếu xóa thành công dữ liệu trong bảng clubmembers, tiến hành xóa dữ liệu trong bảng clubs
        $sql_club = "DELETE FROM clubs WHERE ClubID = $clubID";

        // Thực hiện câu lệnh SQL xóa dữ liệu trong bảng clubs
        $result_club = $db->delete($sql_club);

        // Kiểm tra kết quả xóa dữ liệu trong bảng clubs
        if ($result_club) {
            $sql_role = "SELECT Role FROM user WHERE MSV = '$msv'";
            $result_role = $db->select($sql_role);
            if ($result_role !== false && $result_role->num_rows > 0) {
                // Lấy dòng dữ liệu đầu tiên từ kết quả truy vấn
                $role_row = $result_role->fetch_assoc();
                
                // Lấy giá trị Role từ dòng dữ liệu
                $role = $role_row['Role'];
                $query_update_role = "UPDATE user 
                                  SET Role = 'sv' 
                                  WHERE MSV = '$msv' 
                                  AND Role != 'admin'";
            $result_update_role = $db->update($query_update_role);
            }
            // Sửa câu lệnh SQL để cập nhật vai trò của MSV
            
            if (!$result_update_role) {
                $message = "Có lỗi khi cập nhật vai trò của MSV";
            }

            // Chuyển hướng trình duyệt để làm mới trang web sau khi xóa thành công
            header("Location: http://localhost/DALN/Home/Home.php");
            exit(); // Kết thúc script sau khi chuyển hướng
        } else {
            // Nếu xóa dữ liệu trong bảng clubs không thành công, thông báo lỗi
            $message = "Xóa dữ liệu trong bảng clubs không thành công";
        }
    } else {
        // Nếu xóa dữ liệu trong bảng clubmembers không thành công, thông báo lỗi
        $message = "Xóa dữ liệu trong bảng clubmembers không thành công";
    }
} else {
    // Nếu không nhận được ClubID hoặc MSV từ client, thông báo lỗi
    $message = "Không nhận được ClubID hoặc MSV từ client";
}

// Hiển thị thông báo
echo "<script>alert('$message');</script>";
?>
