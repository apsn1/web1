<?php 
/*
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} */
?> 

<!DOCTYPE html>
<html>
<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css"> <!-- เพิ่มลิงค์ไปยังไฟล์ CSS -->
</head>
<body>
    <h1>จัดการข้อมูลหน้าเว็บ</h1>
    <form method="POST" action="add_content.php">
        <div class="form-container">
            <div class="form-group">
                <input type="text" name="title" placeholder="ชื่อหัวข้อ" required>
            </div>
            <div class="form-group">
                <textarea name="body" placeholder="เนื้อหา" required></textarea>
            </div>
        </div>
        <button type="submit">เพิ่มข้อมูล</button>
    </form>
</body>
</html>
