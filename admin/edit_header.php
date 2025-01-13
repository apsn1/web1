<?php 
    include('../db.php');
    session_start();

    // ตรวจสอบการล็อกอินของแอดมิน
    if (!isset($_SESSION['admin_logged_in'])) {
        // ถ้ายังไม่ได้ล็อกอิน จะทำการเปลี่ยนเส้นทางไปที่หน้า login.php
        header('Location: login.php');
        exit();
    }
?>


<!DOCTYPE html>
<html>

<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css"> <!-- เพิ่มลิงค์ไปยังไฟล์ CSS -->
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
    <div class="imgHD">
        <h1>แก้ไขรูปแบนเนอร์</h1>
        <?php
        include("../db.php");
        $sql = "SELECT * FROM header_images";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "<div>";
                // แสดงรูปภาพปก (header)
                $headerImagePath = 'img/header/' . $rows['img'];
                echo "<br>";
                echo "<img src='" . $headerImagePath . "' width='700px' >";
                echo "<p>Header Image: " . $rows['img'] . "</p>";

                // แสดงรูปภาพแนวตั้ง (vertical)
                $verticalImagePath = 'img/vertical/' . $rows['img_vertical'];
                echo "<img src='" . $verticalImagePath . "' width='300px' >";
                echo "<p>Vertical Image: " . $rows['img_vertical'] . "</p>";

                echo "</div>";

                // ฟอร์มแก้ไขข้อมูล
                echo "<form method='post' action='editing_header.php' enctype='multipart/form-data'>
                <div class='form-container'>
                    <div class='form-group'>
                        <label for='header'>อัปโหลดรูปปกใหม่:</label>
                        <input type='file' name='header' accept='image/*' required />
                        <br>
                        <label for='image_vertical'>อัปโหลดรูปแนวตั้งใหม่:</label>
                        <input type='file' name='image_vertical' accept='image/*' />
                        <br>
                        <label for='button'>ข้อความในปุ่ม:</label>
                        <input type='text' name='button' value='" . htmlspecialchars($rows['button'] ?? '', ENT_QUOTES, 'UTF-8') . "' required />
                    </div>
                </div>
                <button type='submit'>แก้ไขข้อมูล</button>
                <input type='hidden' value='" . $rows['headerID'] . "' name='edit' />
            </form>";
            }
        } else {
            echo "<p>ไม่มีข้อมูลแบนเนอร์</p>";
        }
        ?>
    </div>
</body>

</html>