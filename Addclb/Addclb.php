<?php
include "../Data/database.php";

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Initialize message variable
$message = "";
if($_SESSION['Role'] == 'admin'){
    // Kiểm tra nếu có sự kiện submit từ form
    if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
        // Kiểm tra xem form đã được điền đầy đủ thông tin chưa 
        if (!empty($_POST['ClubName']) && !empty($_POST['Description']) && !empty($_POST['MSV'])) {
            // Lấy thông tin từ form
            $clubName = $_POST['ClubName'];
            $description = $_POST['Description'];
            $msv_chu_nhiem = $_POST['MSV']; // Lấy MSV chủ nhiệm từ biểu mẫu
            
            // Lấy số lượng thành viên từ input có id là "count"
            $numberOfMSVs = $_POST['count'];
            $query_check_role = "SELECT Role FROM user WHERE MSV = '$msv_chu_nhiem'";
            $result_check_role = $db->select($query_check_role);
            if ($result_check_role) {
                $row = $result_check_role->fetch_assoc();
                $msv_role = $row['Role'];

                if ($msv_role != 'admin' && $msv_role != 'CLB') {
                    // Sửa vai trò của MSV thành CLB
                    $query_update_role = "UPDATE user SET Role = 'CLB' WHERE MSV = '$msv_chu_nhiem'";
                    $result_update_role = $db->update($query_update_role);
                    }
                }
            // Kiểm tra xem số lượng thành viên có ít nhất 4 không
            if ($numberOfMSVs < 4) {
                $message = "Số lượng thành viên phải ít nhất là 4";
            } else {
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
                    $query = "INSERT INTO clubs (ClubName, Description, Members, Avata, Background, MSV,Status) VALUES ('$clubName', '$description', '$numberOfMSVs', '$avataName', '$backgroundName', '$msv_chu_nhiem','Thành công')";
                    
                    // Thực thi truy vấn
                    $result = $db->insert($query);

                    // Kiểm tra kết quả thêm câu lạc bộ
                    if ($result) {
                        $clubID = $db->link->insert_id;
                    
                        // Lấy danh sách các MSV từ input có id là "selectedMSVsInput"
                        if (isset($_POST['selectedMSVsInput'])) {
                            $selectedMSVsInput = $_POST['selectedMSVsInput'];
                            // Chia chuỗi các MSV thành một mảng
                            $selectedMSVs = explode(', ', $selectedMSVsInput);
                    
                            // Thêm các MSV vào bảng clubmembers
                            foreach ($selectedMSVs as $msv) {
                                // Kiểm tra xem MSV có tồn tại trong cơ sở dữ liệu không trước khi thêm
                                $query_check_msv = "SELECT MSV FROM user WHERE MSV = '$msv'";
                                $result_check_msv = $db->select($query_check_msv);
                                if ($result_check_msv && $result_check_msv->num_rows > 0) {
                                    // Thực hiện truy vấn SQL để thêm MSV vào bảng clubmembers
                                    $query_member = "INSERT INTO clubmembers (MSV, ClubID, Status) VALUES ('$msv', '$clubID', 'Thành công')";
                                    $result_member = $db->insert($query_member);
                                    if (!$result_member) {
                                        $message = "Đã xảy ra lỗi khi thêm thành viên vào câu lạc bộ!";
                                        break; // Thoát vòng lặp nếu có lỗi xảy ra
                                    }
                                } else {
                                    $message = "MSV $msv không tồn tại trong cơ sở dữ liệu!";
                                }
                            }
                        }
                    }
                     else {
                        $message = "Đã xảy ra lỗi khi thêm câu lạc bộ!";
                    }
                } else {
                    $message = "Đã xảy ra lỗi khi tải lên file avata hoặc background!";
                }
            }
        } else {
            $message = "Vui lòng điền đầy đủ thông tin!";
        }
    }
}elseif ($_SESSION['Role'] == 'sv' || $_SESSION['Role'] == 'CLB') {
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

            // Kiểm tra xem số lượng thành viên có ít nhất 4 không
            if ($numberOfMSVs < 4) {
                $message = "Số lượng thành viên phải ít nhất là 4";
            } else {
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
                    $query = "INSERT INTO clubs (ClubName, Description, Members, Avata, Background, MSV, Status) VALUES ('$clubName', '$description', '$numberOfMSVs', '$avataName', '$backgroundName', '$msv_chu_nhiem', 'Chờ duyệt')";
                    
                    // Thực thi truy vấn
                    $result = $db->insert($query);
                    if ($result) {
                        $clubID = $db->link->insert_id;

                        // Lấy danh sách các MSV từ input có id là "selectedMSVsInput"
                        if (isset($_POST['selectedMSVsInput'])) {
                            $selectedMSVsInput = $_POST['selectedMSVsInput'];

                            // Chia chuỗi các MSV thành một mảng
                            $selectedMSVs = explode(', ', $selectedMSVsInput);

                            // Thêm các MSV vào bảng clubmembers
                            foreach ($selectedMSVs as $msv) {
                                // Thực hiện truy vấn SQL để thêm MSV vào bảng clubmembers
                                $query_member = "INSERT INTO clubmembers (MSV, ClubID, Status) VALUES ('$msv', '$clubID', 'Chờ duyệt')";
                                $result_member = $db->insert($query_member);
                                if (!$result_member) {
                                    $message = "Đã xảy ra lỗi khi thêm thành viên vào câu lạc bộ!";
                                    break; // Thoát vòng lặp nếu có lỗi xảy ra
                                }
                            }
                        }
                        $message = "Đăng ký thành công";
                    }else {
                        $message = "Đã xảy ra lỗi khi gửi yêu cầu.";
                    }
                } else {
                    $message = "Đã xảy ra lỗi khi tải lên file avata hoặc background!";
                }
            }
        } else {
            $message = "Vui lòng điền đầy đủ thông tin!";
        }
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
                <a href="http://localhost/DALN//Home/Home.php" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-star"></i>
                    <span>Sự kiện</span>
                </a>
                <a href="#" class="menu-item active">
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
                <div class="action-title">
                    <h1>Tạo câu lạc bộ</h1>
                </div>
                <form action="" class="addclb" method="POST" enctype="multipart/form-data">
                    <label for="ClubName">Tên Câu lạc bộ:</label>
                    <input type="text" name="ClubName" id="ClubName" required>
                    <label for="ClubName">MSV chủ nhiệm câu lạc bộ</label>
                    <input type="text" name="MSV" id="MSV" required>
                    <label for="MSV">Thành viên ban đầu</label>
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
                    <button class="thembtn" style="width: 100px" type="button" onclick="addSelectedOption()">Thêm</button>

                    <!-- Div để hiển thị tất cả các MSV đã chọn -->
                    <div>
                        <!-- Thêm input ẩn để lưu trữ danh sách các MSV đã chọn -->
                        <input type="hidden" id="selectedMSVsInput" name="selectedMSVsInput">
                        <p id="selectedCount">Số lượng MSV đã chọn: <input style="width: 50px;text-align: center;" id="count" name="count"></p>
                        <div id="selectedMSVsContainer"></div>
                    </div>
                    <label for="Description">Mô tả:</label>
                    <textarea name="Description" id="Description" cols="30" rows="5" required></textarea>
            
                    <label for="Avata">Avata:</label>
                    <input class="sb-img" type="file" name="Avata" id="Avata" accept="image/*" required>
            
                    <label for="Background">Background:</label>
                    <input class="sb-img" type="file" name="Background" id="Background" accept="image/*" required>
            
                    
                    <?php
                    if ($_SESSION['Role'] == 'sv' || $_SESSION['Role'] == 'CLB') {
                            echo '<button class="addbtn" type="submit" id="dkclb" >Đăng ký</button>';
                        } elseif ($_SESSION['Role'] == 'admin') {
                            echo '<button class="addbtn" type="submit" id="submitButton" >Tạo</button>';
                        }
                    ?>
                </form>
            </div>               
            <input type="hidden" id="userRole" value="<?php echo $_SESSION['Role']; ?>">
        </div>
    </div>
</body>
</html>
