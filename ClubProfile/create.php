<?php
include "../Data/database.php";

// Khởi tạo biến message
$message = "";

// Kiểm tra nếu form đã được gửi đi và có dữ liệu để xử lý
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["createEvent"])) {
    // Lấy dữ liệu từ form
    $eventName = $_POST["EventName"];
    $description = $_POST["Description"];
    $place = $_POST["Place"];
    $date = $_POST["EventDateTime"]; // Thay thế 'Date' bằng 'EventDate'
    $time = $_POST["CreationDateTime"]; // Thay thế 'Time' bằng 'EventTime'
    $clubID = $_POST["ClubID"];
    $status = "Thành công"; // Giá trị mặc định cho cột 'Status'

    // Xử lý ảnh sự kiện
    $eventImage = $_FILES["EventImage"]["name"]; // Thay thế 'Image' bằng 'EventImage'
    $targetDir = '../img/';
    $targetFile = $targetDir . basename($_FILES["EventImage"]["name"]); // Thay thế 'Image' bằng 'EventImage'

    // Di chuyển tập tin được tải lên vào thư mục uploads
    if (move_uploaded_file($_FILES["EventImage"]["tmp_name"], $targetFile)) { // Thay thế 'Image' bằng 'EventImage'
        // Chuẩn bị và thực thi câu lệnh SQL để chèn dữ liệu vào bảng events
        $sql = "INSERT INTO events (EventName, Description, Place, Date, Time, ClubID, Image, Status) 
                VALUES ('$eventName', '$description', '$place', '$date', '$time', '$clubID', '$eventImage', '$status')";

        // Thực thi câu lệnh SQL và kiểm tra kết quả
        if ($connection->query($sql) === TRUE) { // Sử dụng biến $connection thay vì $conn
            // Gán giá trị cho biến message nếu thêm thành công
            echo '<script>window.history.back();</script>';
            $message = "Thêm thành công";
            // Xóa dữ liệu ở các ô input
            $clearInputs = true;
            // Quay lại trang trước
            
        } else {
            // Gán giá trị cho biến message nếu có lỗi xảy ra
            $message = "Lỗi: " . $connection->error;
        }
    } else {
        // Gán giá trị cho biến message nếu có lỗi xảy ra khi tải ảnh lên
        $message = "Xin lỗi, đã xảy ra lỗi khi tải lên tập tin.";
    }
}

// Hiển thị thông báo
echo "<script>alert('$message');</script>";

// Nếu có lỗi xảy ra và cần xóa các ô input, thêm mã JavaScript để xóa chúng
if ($clearInputs) {
    echo "<script>
            document.getElementById('EventName').value = '';
            document.getElementById('Description').value = '';
            document.getElementById('Place').value = '';
            document.getElementById('EventDateTime').value = '';
            document.getElementById('CreationDateTime').value = '';
            document.getElementById('ClubID').value = '';
          </script>";
}
?>
