<?php
include "../Data/database.php";

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Kiểm tra nếu có sự kiện submit từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem form đã được điền đầy đủ thông tin chưa 
    if (!empty($_POST['ClubName']) && !empty($_POST['Description']) && !empty($_POST['MSV'])) {
        // Lấy thông tin từ form
        $clubName = $_POST['ClubName'];
        $description = $_POST['Description'];
        $msv_chu_nhiem = $_POST['MSV']; // Lấy MSV chủ nhiệm từ biểu mẫu
        
        // Lấy số lượng thành viên từ input có id là "count"
        $numberOfMSVs = $_POST['count'];


        // Lấy tên tệp Avata và Background
        $avataName = $_FILES['Avata']['name'];
        $backgroundName = $_FILES['Background']['name'];

        // Thư mục lưu trữ avata và background
        $uploadDir = '../img/';

        // Di chuyển tệp Avata và Background vào thư mục lưu trữ
        $avataPath = $uploadDir . $avataName;
        $backgroundPath = $uploadDir . $backgroundName;

        // Di chuyển tệp Avata và Background từ vị trí tạm thời vào thư mục lưu trữ
        move_uploaded_file($_FILES['Avata']['tmp_name'], $avataPath);
        move_uploaded_file($_FILES['Background']['tmp_name'], $backgroundPath);

        // Kiểm tra xem file avata và background đã được tải lên thành công hay không
        if (file_exists($avataPath) && file_exists($backgroundPath)) {
            // Tạo truy vấn SQL để thêm câu lạc bộ vào cơ sở dữ liệu
            $query = "INSERT INTO clubs (ClubName, Description, Members, Avata, Background, MSV) VALUES ('$clubName', '$description', '$numberOfMSVs', '$avataName', '$backgroundName', '$msv_chu_nhiem')";
            
            // Thực thi truy vấn
            $result = $db->insert($query);

            // Kiểm tra kết quả thêm câu lạc bộ
            if ($result) {
                $message = "Thêm thành công";
            } else {
                echo "Đã xảy ra lỗi khi thêm câu lạc bộ!";
            }
        } else {
            echo "Đã xảy ra lỗi khi tải lên file avata hoặc background!";
        }
    } else {
        echo "Vui lòng điền đầy đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Câu Lạc Bộ</title>
    <link rel="stylesheet" href="http://localhost/DALN/Login/Login.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="Addclb.css">
    <link rel="stylesheet" href="http://localhost/DALN//Home/Home.css">
    <script src="http://localhost/DALN/Home/Home.js"></script>
    <script src="http://localhost/DALN/Addclb/Addclb.js"></script>
    <link rel="stylesheet" href="http://localhost/DALN/fontawesome-free-6.4.2-web/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<script>
        $(document).ready(function(){
            <?php
            // Kiểm tra nếu có thông báo lỗi, thì hiển thị khung thông báo
            if (!empty($message)) {
                echo "alert('$message');";
            }
            ?>
        });
    </script>
<body>
    <div class="wrapper wrapper-login flexs">
        <div class="left-sidebar">
            <div class="sidebar-menu">
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-star"></i>
                    <span>Sự kiện</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-users"></i>
                    <span>Tạo CLB</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-school"></i>
                    <span>Tạo sự kiện</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-bell"></i>
                    <span>Thông báo</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Thống kê</span>
                </a>    
            </div>
        </div>
        <div class="content-page">
            <div class="content-top">
            <div class="search">
                <form id="searchForm" action="Search.php" method="POST"> <!-- Thay đổi action và method của form -->
                    <input type="text" placeholder="Tìm kiếm" class="search-form" id="searchInput" name="searchInput"> <!-- Thêm thuộc tính name cho input -->
                    <i class="fa-solid fa-magnifying-glass" id="searchIcon"></i>
                </form>
            </div>
                <div class="acc">
                    <i class="fa-solid fa-circle-user" style="font-size: 40px;"></i>
                    <div class="acc-dow">
                        <?php
                        // Check if 'Name' is set in the session before using it
                        if (isset($_SESSION['Name'])) {
                            echo '<span>' . $_SESSION['Name'] . '</span>';
                        } else {
                            echo '<span>Unknown User</span>'; // Provide a default value if 'Name' is not set
                        }
                        ?>
                        <i class="fa-solid fa-caret-down"></i>
                    </div>
                    <ul class="user-drop" data-bs-popper="none">
                        <li><a href="http://localhost/DALN/Login/Login.php" class="dropdown-item" id="btnLogout"><i class="fa-solid fa-arrow-right-from-bracket"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
            <div class="content-bottom ">
                <form action="" class="addclb" method="POST" enctype="multipart/form-data">
                    <label for="ClubName">Tên Câu lạc bộ:</label>
                    <input type="text" name="ClubName" id="ClubName" required>
                    <label for="ClubName">MSV chủ nhiệm câu lạc bộ</label>
                    <input type="text" name="MSV" id="MSV" required>
                    <label for="MSV">Thành viên(MSV)</label>
                    <input type="text" id="MSVInput" list="MSVList">
                    <datalist id="MSVList">
                        <?php
                        // Lấy và hiển thị danh sách MSV từ cơ sở dữ liệu
                        $query = "SELECT MSV FROM user WHERE Role != 'admin'";
                        $result = $db->select($query);
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['MSV'] . '">';
                            }
                        }
                        ?>
                    </datalist>
                    <button style="width: 100px" type="button" onclick="addSelectedOption()">Thêm</button>

                    <!-- Div để hiển thị tất cả các MSV đã chọn -->
                    <div>
                        <p id="selectedCount">Số lượng MSV đã chọn: <input id="count" name="count"></input></p>
                        <ul id="selectedMSVs"></ul>
                    </div>
                    <label for="Description">Mô tả:</label>
                    <textarea name="Description" id="Description" cols="30" rows="5" required></textarea>
            
                    <label for="Avata">Avata:</label>
                    <input class="sb-img" type="file" name="Avata" id="Avata" accept="image/*" required>
            
                    <label for="Background">Background:</label>
                    <input class="sb-img" type="file" name="Background" id="Background" accept="image/*" required>
            
                    <button class="addbtn" type="submit">Tạo</button>
                </form>
            </div>               
            <input type="hidden" id="userRole" value="<?php echo $_SESSION['Role']; ?>">
        </div>
    </div>
</body>
</html>
