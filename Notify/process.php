<?php
include "../Data/database.php";

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Initialize message variable
$message = "";

// Kiểm tra xem ClubID đã được gửi đến từ client hay không
if (isset($_GET['clubID'])) {
    // Xác định ClubID của dữ liệu cần xóa hoặc cần từ chối/duyệt
    $clubID = $_GET['clubID'];

    // Kiểm tra xem người dùng có quyền admin không
    if ($_SESSION['Role'] == 'admin') {
        // Kiểm tra xem người dùng đang thực hiện hành động xóa, từ chối hay duyệt câu lạc bộ
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action === 'reject') {
                // Cập nhật trạng thái của câu lạc bộ thành "Từ chối"
                $sql = "UPDATE clubs SET Status = 'Từ chối' WHERE ClubID = $clubID";
                $result = $db->update($sql);
                if ($result) {
                    // Cập nhật status của tất cả các MSV có ClubID tương ứng thành "Thành công"
                    $query_update_members = "UPDATE clubmembers SET Status = 'Từ chối' WHERE ClubID = $clubID";
                    $result_update_members = $db->update($query_update_members);
                }
            }elseif ($action === 'approve') {
                // Lấy MSV từ bảng clubs
                $sql_get_msv = "SELECT MSV FROM clubs WHERE ClubID = $clubID";
                $msv_result = $db->select($sql_get_msv);
            
                if ($msv_result !== false && $msv_result->num_rows > 0) {
                    $msv_row = $msv_result->fetch_assoc();
                    $msv = $msv_row['MSV'];
            
                    // Kiểm tra role của sinh viên trong bảng user
                    $sql_get_role = "SELECT Role FROM user WHERE MSV = '$msv'";
                    $role_result = $db->select($sql_get_role);
            
                    if ($role_result !== false && $role_result->num_rows > 0) {
                        $role_row = $role_result->fetch_assoc();
                        $role = $role_row['Role'];
            
                        // Nếu role không phải là 'admin', cập nhật thành 'CLB'
                        if ($role !== 'admin') {
                            $sql_update_user_role = "UPDATE user SET Role = 'CLB' WHERE MSV = '$msv'";
                            $update_result = $db->update($sql_update_user_role);
            
                            if (!$update_result) {
                                // Xử lý lỗi nếu có
                            }
                        }
                    }
                }
                // Cập nhật status của Club thành "Thành công"
                $query_update_club = "UPDATE clubs SET Status = 'Thành công' WHERE ClubID = $clubID";
                $result = $db->update($query_update_club);
                if ($query_update_club) {
                    // Cập nhật status của tất cả các MSV có ClubID tương ứng thành "Thành công"
                    $query_update_members = "UPDATE clubmembers SET Status = 'Thành công' WHERE ClubID = $clubID";
                    $result_update_members = $db->update($query_update_members);
                }
            }elseif ($action === 'delete') {
                // Xóa dữ liệu trong bảng clubmembers có ClubID tương ứng
                $sql_members = "DELETE FROM clubmembers WHERE ClubID = $clubID";
                $result_members = $db->delete($sql_members);

                // Xóa dữ liệu trong bảng clubs có ClubID tương ứng
                $sql_club = "DELETE FROM clubs WHERE ClubID = $clubID";
                $result = $db->delete($sql_club);
            }

            // Kiểm tra kết quả của hành động
            if ($result) {
                // Nếu thành công, chuyển hướng trình duyệt để làm mới trang web
                header("Location: http://localhost/DALN/Notify/Notify.php");
                exit();
            } else {
                // Nếu thất bại, thông báo lỗi
                $message = "Thực hiện hành động không thành công";
            }
        } else {
            // Nếu không nhận được hành động từ client, thông báo lỗi
            $message = "Không nhận được hành động từ client";
        }
    } else {
        // Nếu người dùng không phải là admin, chuyển hướng về trang thông báo không có quyền
        header("Location: http://localhost/DALN/Notify/Notify.php");
        exit();
    }
} else {
    // Nếu không nhận được ClubID từ client, thông báo lỗi
    $message = "Không nhận được ClubID từ client";
}

// Hiển thị thông báo
echo "<script>alert('$message');</script>";
?>
