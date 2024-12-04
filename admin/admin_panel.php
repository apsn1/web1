<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} 
?> 

<!DOCTYPE html>
<html>
<head>
    <title>แอดมิน</title>
</head>
<body>
    <h1>จัดการข้อมูลหน้าเว็บ</h1>
    <form method="POST" action="add_content.php">
        <input type="text" name="title" placeholder="ชื่อหัวข้อ" required>
        <textarea name="body" placeholder="เนื้อหา" required></textarea>
        <button type="submit">เพิ่มข้อมูล</button>
    </form>
</body>
</html>
