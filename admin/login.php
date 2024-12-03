<?php include('../db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CssForAdmin/Login_css.css">
    <title>ล็อกอินผู้ดูแลระบบ</title>
</head>
<body>
    <h1>ล็อกอิน</h1>
    <form method="POST" action="login_process.php">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <button type="submit">เข้าสู่ระบบ</button>
    </form>
</body>
</html>
