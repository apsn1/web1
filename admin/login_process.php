<?php
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // คำสั่ง SQL
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ดีบักแสดงค่า
        echo "รหัสผ่านที่เก็บในฐานข้อมูล: " . $user['password'] . "<br>";
        echo "รหัสผ่านที่กรอก: " . $password . "<br>";

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: admin_panel.php");
            exit;
        } else {
            echo "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        echo "ไม่พบผู้ใช้งาน";
    }
}
?>
