<?php
require_once '../Model/login_model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['usernameForm'];
    $pass = $_POST['passwordForm'];

    $stmt = $db->prepare("SELECT * FROM Users WHERE TenUser = ? AND MatKhau = ?");
    $stmt->execute([$user, $pass]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION["IsLogin"] = true;
        header("Location: ../View/SachView.php");
        exit; 
    } else {
        // Nếu tài khoản không chính xác, chuyển hướng người dùng đến trang đăng nhập lại
        header("Location: ../View/login.htm");
        exit;
    }
}
?>
