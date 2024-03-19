<?php
include "../Data/database.php";

// Start the session
session_start();

// Instantiate the Database class
$db = new Database();

// Số lượng CLB-item hiển thị trên mỗi trang
$itemsPerPage = 5;

// Số trang hiện tại
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Tính vị trí bắt đầu của kết quả truy vấn cho trang hiện tại
$offset = ($currentPage - 1) * $itemsPerPage;

// SQL query với điều kiện chỉ chọn các mục có trạng thái là "Thành công"
$sql = "SELECT * FROM clubs WHERE Status IN ('Chờ duyệt', 'Từ chối')";


// Thêm điều kiện LIMIT và OFFSET vào câu SQL
$sql .= " LIMIT $offset, $itemsPerPage";

// Thực hiện truy vấn SQL
$result = $db->select($sql);

// Tính tổng số trang dựa trên số lượng CLB-item và số lượng hiển thị trên mỗi trang
$totalItems = $db->count("clubs"); // Số lượng CLB-item trong toàn bộ bảng
$totalPages = ceil($totalItems / $itemsPerPage);

// Hiển thị HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo</title>
    <link rel="stylesheet" href="http://localhost/DALN/Login/Login.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="http://localhost/DALN/Home/Home.css">
    <link rel="stylesheet" href="http://localhost/DALN/fontawesome-free-6.4.2-web/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="http://localhost/DALN/Home/Home.js"></script>
    <link rel="stylesheet" href="http://localhost/DALN/Notify/Notify.css">

</head>
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
                <a href="http://localhost/DALN/Addclb/Addclb.php" class="menu-item">
                    <i class="fa-solid fa-users"></i>
                    <span>Tạo CLB</span>
                </a>
                <a href="" class="menu-item">
                    <i class="fa-solid fa-school"></i>
                    <span>Tạo sự kiện</span>
                </a>
                <a href="#" class="menu-item active">
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
            <div class="content-bottom1">
                <div class="actions">
                    <button class="clb" onclick="showClubTable()">Câu lạc bộ</button>
                    <button class="sk" onclick="showEventTable()">Sự kiện</button>
                </div>
                <table class="table-club" id="table-club">
                    <tr class="table-title">
                        <th class="small">ClubID</th>
                        <th class="small">ClubName</th>
                        <th class="big">Description</th>
                        <th class="small">Members</th>
                        <th class="small">Avata</th>
                        <th class="fit">Background</th>
                        <th class="small">MSV</th>
                        <th>Status</th>
                        <?php
                            if ($_SESSION['Role'] === 'admin') {
                            echo "<th>Actions</th>";
                            }
                        ?>
                    </tr>
                    <?php
                    // Kiểm tra xem có dữ liệu được trả về không
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                                echo '<tr class="table-title">';
                                echo "<td>" . $row["ClubID"] . "</td>";
                                echo "<td>" . $row["ClubName"] . "</td>";
                                echo "<td>" . $row["Description"] . "</td>";
                                echo "<td>" . $row["Members"] . "</td>";
                                echo '<td ><img class="row-avt" src="http://localhost/DALN/img/' . $row["Avata"] . '" alt=""></td>';
                                echo '<td ><img class="row-bgr" src="http://localhost/DALN/img/' . $row["Background"] . '" alt=""></td>';
                                echo "<td>" . $row["MSV"] . "</td>";
                                echo "<td>" . $row["Status"] . "</td>";
                                if ($_SESSION['Role'] === 'admin') {
                                    echo '<td>';
                                    echo '<a href="http://localhost/DALN/Notify/process.php?action=delete&clubID=' . $row["ClubID"] . '" class="Delete">Xóa</a>';
                                    echo '<a href="http://localhost/DALN/Notify/process.php?action=reject&clubID=' . $row["ClubID"] . '" class="Delete">Từ chối</a>';
                                    echo '<a href="http://localhost/DALN/Notify/process.php?action=approve&clubID=' . $row["ClubID"] . '" class="Pass">Duyệt</a>';
                                    echo '</td>';
                                }                                
                                echo "</tr>";
                            }
                        } else {
                        echo "<tr><td colspan='8'>Không có dữ liệu</td></tr>";
                    }
                    ?>
                </table>
                <table class="table-event" id="table-event" style="display: none;">
                <div class="t" style="background-color: wheat; width:100%;"></div>
                </table>
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
