<?php include('../db.php'); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="CssForAdmin/register_css.css">
    <title>สมัครบัญชีผู้ใช้งาน</title>
</head>
<body>
    <h1>สมัครบัญชีผู้ใช้งาน</h1>
    <form method="POST" action="register_process.php">
        <input type="text" name="email" placeholder="อีเมล" required>
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <button type="submit">สมัคร</button>
    </form>
</body>
</html>