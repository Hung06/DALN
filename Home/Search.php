<?php
// Search.php

// Include database connection
include "../Data/database.php";
include "Pager.php"; // Đường dẫn đến thư viện Pager

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Kiểm tra nếu có tham số tìm kiếm
if (isset($_GET['searchTerm'])) {
    // Fetch the search term from the $_GET array
    $searchTerm = $_GET['searchTerm'];

    // Your SQL query for search
    $searchSql = "SELECT * FROM clubs WHERE ClubName LIKE '%$searchTerm%'";

    // Perform the query using the select method from the Database class
    $result = $db->select($searchSql);

    // Kiểm tra xem có kết quả tìm kiếm không
    if ($result && $result->num_rows > 0) {
        // Số lượng CLB-item hiển thị trên mỗi trang
        $itemsPerPage = 10;

        // Số trang hiện tại
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        // Vị trí bắt đầu của kết quả truy vấn cho trang hiện tại
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Thực hiện truy vấn SQL với điều kiện LIMIT và OFFSET
        $searchSql .= " LIMIT $offset, $itemsPerPage";
        $result = $db->select($searchSql);

        // Tính tổng số trang dựa trên số lượng CLB-item và số lượng hiển thị trên mỗi trang
        $totalItems = $db->count("clubs"); // Số lượng CLB-item trong toàn bộ bảng
        $totalPages = ceil($totalItems / $itemsPerPage);
    } else {
        // Hiển thị thông báo không có kết quả và lấy toàn bộ dữ liệu
        $error_message = "Không tìm thấy kết quả.";
        $sql = "SELECT * FROM clubs";
        $result = $db->select($sql);

        // Số lượng CLB-item hiển thị trên mỗi trang
        $itemsPerPage = 10;

        // Số trang hiện tại
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        // Vị trí bắt đầu của kết quả truy vấn cho trang hiện tại
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Thực hiện truy vấn SQL với điều kiện LIMIT và OFFSET
        $sql .= " LIMIT $offset, $itemsPerPage";
        $result = $db->select($sql);

        // Tính tổng số trang dựa trên số lượng CLB-item và số lượng hiển thị trên mỗi trang
        $totalItems = $db->count("clubs"); // Số lượng CLB-item trong toàn bộ bảng
        $totalPages = ceil($totalItems / $itemsPerPage);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="http://localhost/DALN/Login/Login.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="http://localhost/DALN/fontawesome-free-6.4.2-web/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="http://localhost/DALN/Home/Home.js"></script>
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
    <div class="wrapper wrapper-login flexs">
        <div class="left-sidebar">
            <div class="sidebar-menu">
                <a href="http://localhost/DALN/Home/Home.php" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-star"></i>
                    <span>Sự kiện</span>
                </a>
                <a href="http://localhost/DALN/Addclb/Addclb.php" class="menu-item">
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
            <div class="content-bottom">
            <?php
             if ($result->num_rows > 0) {
                 while ($row = $result->fetch_assoc()) {
                     echo '<div class="CLB-item">';
                     echo '    <div class="CLB-itme-img">';
                     echo '        <img src="http://localhost/DALN/img/' . $row["Avata"] . '" alt="">';
                     echo '    </div>';
                     echo '    <span>' . $row["ClubName"] . '</span>';
                     if ($_SESSION['Role'] == 'sv' || $_SESSION['Role'] == 'CLB') {
                         echo '    <button class="DK">Đăng ký</button>';
                     } elseif ($_SESSION['Role'] == 'admin') {
                         echo '<a href="Delete.php?clubID=' . $row["ClubID"] . '" class="DK">Xóa</a>';
                     }
                     echo '</div>';
                 }
             } else {
                 echo "Không tìm thấy kết quả.";
             }
             ?>
            </div>
            <input type="hidden" id="userRole" value="<?php echo $_SESSION['Role']; ?>">
            <div id="pagination-container">
                <?php
                // Hiển thị các liên kết phân trang
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
