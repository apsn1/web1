<?php
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);

    // คำสั่ง SQL
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $username;
            header("Location: admin_panel.php");
            exit();

    } else {
        echo "ผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
