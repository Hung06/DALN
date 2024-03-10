<?php

include "../Data/database.php";

// Bắt đầu phiên làm việc
session_start();

// Khởi tạo đối tượng Database
$db = new Database();

// Truy vấn SQL để lấy thông tin về các sự kiện từ bảng events và tên của CLB từ bảng clubs thông qua ClubID
$sql = "SELECT events.*, clubs.ClubName,clubs.Avata FROM events INNER JOIN clubs ON events.ClubID = clubs.ClubID";
$result = $db->select($sql);

// Hiển thị HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sự Kiện</title>
    <link rel="stylesheet" href="http://localhost/DALN/Login/Login.css">
    <link rel="stylesheet" href="http://localhost/DALN/Home/Home.css">
    <link rel="stylesheet" href="http://localhost/DALN/fontawesome-free-6.4.2-web/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="http://localhost/DALN/Home/Home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>   
    <link rel="stylesheet" href="http://localhost/DALN/Event/Event.css">

</head>
<script>
     function showMore(element) {
            var contentText = element.previousElementSibling;
            var showMoreButton = element;
            
            if (contentText.classList.contains('expanded')) {
                contentText.classList.remove('expanded');
                showMoreButton.innerText = 'Xem thêm';
            } else {
                contentText.classList.add('expanded');
                showMoreButton.innerText = 'Thu gọn';
            }
        }
</script>

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
                <a href="http://localhost/DALN/Notify/Notify.php" class="menu-item">
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
                    <form action="">
                        <input type="text" placeholder="Tìm kiếm" class="search-form"> 
                    </form>
                    <i class="fa-solid fa-magnifying-glass"></i>
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
                // Kiểm tra xem có dữ liệu từ truy vấn không
                if ($result->num_rows > 0) {
                    // Duyệt qua từng hàng dữ liệu và hiển thị thông tin tương ứng
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="event-title">';
                        echo '    <div class="event-club">';
                        echo '        <div class="club-img">';
                        echo '            <img src="http://localhost/DALN/img/'. $row["Avata"] . '" alt="">';
                        echo '        </div>';
                        echo '        <div class="club-name">';
                        echo '            <h1>' . $row["ClubName"] . ' / <span>' . $row["EventName"] . '</span></h1>';
                        echo '            <h3>' . $row["Time"] . '</h3>';
                        echo '        </div>';
                        echo '        <div class="dkevent">';
                        if ($row["Status"] == "Mở đăng ký") {
                            echo '            <button class="dkevent-btn">Đăng ký</button>';
                        } else {
                            echo '            <span> <button class="dkevent-btn">Hết hạn</button></span>';
                        }
                        echo '        </div>';
                        echo '    </div>';
                        echo '    <div class="event-content">';
                        echo '        <p class="content-text">' . $row["Description"] . '</p>';
                        echo '        <p class="show-more" onclick="showMore(this)">Xem thêm</p>';
                        echo '    </div>';
                        echo '    <div id="image-container" ondrop="drop(event)" ondragover="allowDrop(event)">';
                        echo '        <img src="http://localhost/DALN/img/'. $row["Image"] . '" alt="" onload="checkImageOrientation(this)">';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo "Không có sự kiện nào.";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
