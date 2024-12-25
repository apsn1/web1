<?php include('../db.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="CssForAdmin/Login_css.css">
    <title>ล็อกอินผู้ดูแลระบบ</title>
    <?php
    // Path to the folder
    $folderPath = "uploads/";

    // Get all files in the folder
    $files = glob($folderPath . "*");

    // Sort files by modified time, newest first
    usort($files, function ($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    // Get the latest file
    $latestFile = !empty($files) ? basename($files[0]) : null;

    if ($latestFile) {
        echo '<link rel="icon" type="image/x-icon" href="' . $folderPath . $latestFile . '">';
    } else {
        echo "No files found in the folder.";
    }
    ?>
</head>

<body>
    <div class="login">
        <h1>ล็อกอิน</h1>
        <form method="POST" action="login_process.php">
            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <form>
            <a href="register.php">
                <button type="button">ลงทะเบียน</button>
            </a>
        </form>
    </div>



</body>

</html>