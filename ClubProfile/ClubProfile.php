<?php
// Include file database.php
include "../Data/database.php";
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Start the session
session_start();

// Kiểm tra xem có ClubID được truyền qua tham số truy vấn không
if(isset($_GET['ClubID'])) {
    // Lấy giá trị ClubID từ tham số truy vấn
    $clubID = $_GET['ClubID'];

    // Instantiate the Database class
    $db = new Database();

    // Thực hiện câu truy vấn SQL để lấy thông tin về câu lạc bộ dựa trên ClubID
    $sql = "SELECT * FROM clubs WHERE ClubID = $clubID";
    // Thực hiện truy vấn SQL
    $result = $db->select($sql);

    // Kiểm tra xem biến $result có tồn tại và có dữ liệu hay không
    if($result && $result->num_rows > 0) {
        // Thực hiện truy vấn SQL để lấy dữ liệu sự kiện từ cơ sở dữ liệu
        $sql_events = "SELECT e.*, c.ClubName FROM events AS e
                       INNER JOIN clubs AS c ON e.ClubID = c.ClubID
                       WHERE e.ClubID = $clubID";

        $result_events = $db->select($sql_events);

        // Khởi tạo mảng $events để lưu trữ thông tin các sự kiện
        $events = array();

        // Kiểm tra xem có sự kiện nào được trả về không
        if ($result_events && $result_events->num_rows > 0) {
            // Duyệt qua từng hàng dữ liệu và lưu vào mảng $events
            while ($row_event = $result_events->fetch_assoc()) {
                $events[] = $row_event;
            }
        }

        // Thực hiện truy vấn SQL để lấy thông tin các thành viên của câu lạc bộ
        $sql_members = "SELECT cm.ID, cm.MSV, u.Name, u.Role, cm.Status 
                        FROM clubmembers AS cm
                        INNER JOIN user AS u ON cm.MSV = u.MSV
                        WHERE cm.ClubID = $clubID";

        // Thực hiện truy vấn SQL
        $result_members = $db->select($sql_members);

        // Tính toán số lượng thành viên
        $member_count = $result_members->num_rows;

        // Thực hiện cập nhật giá trị Members trong bảng clubs chỉ khi có sự thay đổi
        $sql_update_members_count = "UPDATE clubs SET Members = $member_count WHERE ClubID = $clubID";
        $result_update_members_count = $db->update($sql_update_members_count);
    } else {
        // Nếu không có dữ liệu trả về, hiển thị thông báo lỗi
        echo "<p>Lỗi: Không tìm thấy câu lạc bộ có ClubID là $clubID.</p>";
    }
} else {
    // Nếu không có ClubID được truyền, hiển thị thông báo lỗi
    echo "<p>Lỗi: ClubID không được cung cấp.</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/DALN/Login/Login.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://localhost/DALN/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="http://localhost/DALN/Home/Home.css">
    <link rel="stylesheet" href="http://localhost/DALN/fontawesome-free-6.4.2-web/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/DALN/ClubProfile/ClubProfile.css">
    <script src="http://localhost/DALN/Home/Home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script> 
    <title>ClubProfile</title>
</head>
<body>
    <div class="wrapper wrapper-login flexs">
        <div class="left-sidebar">
            <div class="sidebar-menu">
                <a href="http://localhost/DALN/Home/Home.php" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="http://localhost/DALN/Event/Event.php" class="menu-item">
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
                // Kiểm tra xem có dữ liệu từ truy vấn không
                if ($result->num_rows > 0) {
                    // Duyệt qua từng hàng dữ liệu và hiển thị thông tin tương ứng
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="avt-c">';
                        echo '  <div class="cover-art">';
                        echo '      <img src="http://localhost/DALN/img/' . $row["Background"] . '" alt="">';
                        echo '  </div>';
                        echo '  <button class="edit-button1">';
                        echo '      <i class="fa-solid fa-camera"></i>';
                        echo '  </button>';
                        echo '</div>';

                        echo '<div class="infomationCLB">';
                        echo '  <div class="infomation">';
                        echo '      <div class="avatar-container">';
                        echo '          <div class="avatar">';
                        echo '              <img src="http://localhost/DALN/img/' . $row["Avata"] . '" alt="">';
                        echo '          </div>';
                        echo '          <button class="edit-button">';
                        echo '              <i class="fa-solid fa-camera"></i>';
                        echo '          </button>';
                        echo '      </div>';
                        echo '      <div class="infomation-item">';
                        echo '          <h2>' . $row['ClubName'] . '</h2>'; // Hiển thị ClubName từ bảng clubs
                        echo '      </div>';
                        echo '  </div>';
                        echo '  <div class="menuCLB">';
                        echo '      <div class="menuleft">';
                        echo '          <div class="menuleft-button">';
                        echo '              <span><button onclick="showPage(\'event\')" data-page-id="event"><span id="event-btn" class="active-button">Sự kiện</span></button></span>';
                        // Kiểm tra điều kiện chỉ khi MSV của session bằng MSV trong hàng hiện tại
                        if ($_SESSION['MSV'] == $row['MSV']) {
                            echo '<button onclick="showPage(\'create-event\')" class="buttont" data-page-id="create-event"><span>Tạo sự kiện</span></button>';
                            echo '              <button onclick="showPage(\'registration\')" class="buttont" data-page-id="registration"><span>Mở đăng ký</span></button>';
                        }
                        echo '              <button onclick="showPage(\'member\')" class="buttont" data-page-id="member"><span>Thành viên</span></button>';
                        echo '          </div>';
                        echo '          <div class="content-button">';
                        echo '              <div class="content-menu content-event" style="display: block;" id="event">';
                        echo '                  <div class="scollbar">';
                        echo '                      <h2>Sự kiện</h2>';
                        echo '                  </div>';
                        echo '                  <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">';
                        echo '                      <div class="carousel-inner content-scollbar">';
                        // Hiển thị danh sách sự kiện từ bảng events
                        // $events là một mảng chứa các thông tin của sự kiện từ cơ sở dữ liệu
                        foreach ($events as $key => $event) {
                            echo '<div class="carousel-item event ' . ($key === 0 ? 'active' : '') . '">';
                            echo '    <div class="row">';
                            echo '        <div class="col">';
                            echo '            <img src="http://localhost/DALN/img/' . $event["Image"] . '" class="d-block w-100" alt="...">';
                            echo '        </div>';
                            echo '        <div class="col col-right">';
                            echo '            <div>';
                            echo '                <table>';
                            echo '                    <thead>';
                            echo '                        <tr>';
                            echo '                            <th colspan="2"><h4>' . $event['EventName'] . '</h4></th>';
                            echo '                        </tr>';
                            echo '                    </thead>';
                            echo '                    <tbody>';
                            echo '                        <tr>';
                            echo '                            <th class="th1">Câu lạc bộ</th>';
                            echo '                            <th class="th2">' . $event['ClubName'] . '</th>';
                            echo '                        </tr>';
                            echo '                        <tr>';
                            echo '                            <th class="th1">Thời gian</th>';
                            echo '                            <th class="th2">' . $event['Time'] . '</th>';
                            echo '                        </tr>';
                            echo '                        <tr>';
                            echo '                            <th class="th1">Địa điểm</th>';
                            echo '                            <th class="th2">' . $event['Place'] . '</th>';
                            echo '                        </tr>';
                            echo '                        <tr>';
                            echo '                            <th class="th1"></th>';
                            echo '                            <th class="th2">' . $event['Description'] . '</th>';
                            echo '                        </tr>';
                            echo '                        <tr>';
                            echo '                            <th class="th1">Trạng thái</th>';
                            echo '                            <th class="th2">' . $event['Status'] . '</th>';
                            echo '                        </tr>';
                            echo '                    </tbody>';
                            echo '                </table>';
                            echo '            </div>';
                            echo '        </div>';
                            echo '    </div>';
                            echo '</div>';
                        }
                        
                        echo '                      </div>';
                        echo '                      <button class="carousel-control-prev move" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">';
                        echo '                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                        echo '                          <span class="visually-hidden">Previous</span>';
                        echo '                      </button>';
                        echo '                      <button class="carousel-control-next move" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">';
                        echo '                          <span class="carousel-control-next-icon" aria-hidden="true"></span>';
                        echo '                          <span class="visually-hidden">Next</span>';
                        echo '                      </button>';
                        echo '                  </div>';
                        echo '              </div>';
                        $currentDateTime = date("Y-m-d\TH:i");
                        echo '<div class="content-menu" id="create-event">';
                        echo ' <div class="create-event">';
                        echo ' <form method="post" action="create.php" enctype="multipart/form-data">';
                        echo ' <table class="tb-event">';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' Tên sự kiện';
                        echo ' </th>';
                        echo ' <th>';
                        echo ' <input type="text" name="EventName" required>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' Mô tả';
                        echo ' </th>';
                        echo ' <th>';
                        echo ' <textarea name="Description" required></textarea>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' Địa điểm';
                        echo ' </th>';
                        echo ' <th>';
                        echo ' <input type="text" name="Place" required>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' Thời gian tổ chức';
                        echo ' </th>';
                        echo ' <th>';
                        echo ' <input type="datetime-local" name="EventDateTime" required>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' Thời gian đăng bài';
                        echo ' </th>';
                        echo ' <th>';
                        echo ' <input type="datetime-local" name="CreationDateTime" value="' . $currentDateTime . '" required>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' ClubID';
                        echo ' </th>';
                        echo ' <th>';
                        echo ' <input type="text" name="ClubID" value="' . $clubID . '" required>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' Ảnh sự kiện';
                        echo ' </th>';
                        echo ' <th>';
                        echo ' <input type="file" name="EventImage" style="border:none;" required>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo ' <th>';
                        echo ' <button class="btn" type="submit" value="Tạo sự kiện" name="createEvent">Tạo sự kiện</button>';
                        echo ' </th>';
                        echo ' </tr>';
                        echo ' </table>';
                        echo ' </form>';
                        echo ' </div>';
                        echo '</div>';                        
                        echo '              <div class="content-menu" id="member">';
                        echo '                  <div class="member">';
                        echo '                      <table>';
                        echo '                          <thead>';
                        echo '                              <tr>';
                        echo '                                  <th class="STT">ID</th>';
                        echo '                                  <th>Mã sinh viên</th>';
                        echo '                                  <th>Họ tên</th>';
                        echo '                                  <th>Chức vụ</th>';
                        echo '                                  <th>Tình trạng</th>';
                        echo '                                  <th>Action</th>';
                        echo '                              </tr>';
                        echo '                          </thead>';
                        echo '                          <tbody>';
                        
                        $counter = 1;
                        
                        foreach ($result_members as $member) {
                            if($member['Status']!="Từ chối"){
                            echo '<tr class="member-t">';
                            echo '<th class="STT">' . $counter . '</th>'; // Hiển thị số thứ tự
                            echo '<th>' . $member['MSV'] . '</th>'; // MSV từ bảng clubmembers
                            echo '<th>' . $member['Name'] . '</th>'; // Name từ bảng user
                            echo '<th>' . $member['Role'] . '</th>'; // Role từ bảng user
                            
                                echo '<th>' . $member['Status'] . '</th>'; // Status từ bảng clubmembers
                            }
                            // Kiểm tra xem MSV của người dùng có trùng khớp với giá trị MSV của câu lạc bộ hay không
                            if ($_SESSION['MSV'] == $row['MSV']) {                           
                                echo '<th>';
                                if($member['Status']=='Chờ duyệt'){
                                    echo '<a href="http://localhost/DALN/ClubProfile/process.php?action=reject&MSV=' . $member["MSV"] . '" class="Delete">Từ chối</a>';
                                    echo '<a href="http://localhost/DALN/ClubProfile/process.php?action=approve&MSV=' . $member["MSV"] . '" class="Pass">Duyệt</a>';
                                }elseif($member['Status']=='Thành công'){
                                    echo '<a href="http://localhost/DALN/ClubProfile/process.php?action=delete&MSV=' . $member["MSV"] . '" class="Delete">Xóa</a>';
                                }
                                echo '</th>';
                            } 
                            echo '</tr>';
                            $counter++;
                        }
                        echo '                          </tbody>';
                        echo '                      </table>';
                        echo '                  </div>';
                        echo '              </div>';
                        echo '              <div class="content-menu" id="registration">';
                        echo '                  <div>';
                        echo '                  </div>';
                        echo '              </div>';
                        echo '          </div>';
                        echo '      </div>';
                        echo '      <div class="menuright"></div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="ClubProfile.js"></script>
</body>
</html>