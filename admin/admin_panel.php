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
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css">
    <script src="scripts.js"></script>
</head>

<body>
    <h1>จัดการหน้าเมนูหน้าเว็บ</h1>
    <form method="POST" action="add_navbar.php">
        <div class="form-container">
            <div class="form-group">
                <input type="text" name="name" placeholder="ชื่อหัวข้อ">
                <button type="submit">อัปโหลด</button>
            </div>

    </form>
    <h1>อัพโหลด Logo</h1>
    <form action="upload_update.php" method="post" enctype="multipart/form-data">
                <label for="logo">เลือกรูปภาพใหม่:</label>
                <input type="file" name="logo" id="logo" accept="image/*">
                <button type="submit">อัปโหลดและอัปเดต</button>
            </form>
        </div>

        <h1>เพิ่มรูปแบนเนอร์</h1>
    <form method="POST" action="add_header.php" enctype="multipart/form-data">
        <div class="form-container">
            <div class="form-group">
                <input type="file" name="header" required>
                <button type="submit">อัปโหลด</button>
            </div>
            <a class="btn btn-info" href='edit_header.php'>แก้ไข</a>
        </div>
    </form> 

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
    <h1>จัดการข้อมูล วิดิโอ</h1>
    <form action="update_video.php" method="POST">
        <input type="text" name="video_link" placeholder="YouTube Video Link" required>
        <div class="form-group">
        <input type="text" name="video_title" placeholder="ชื่อหัวข้อ Link" required>
    </div>
        <button type="submit">อัปเดตวิดีโอ</button>
        
    </form>
    
</body>

</html>