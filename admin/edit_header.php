<!DOCTYPE html>
<html>
<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css"> <!-- เพิ่มลิงค์ไปยังไฟล์ CSS -->
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
            // ตรวจสอบเส้นทางของไฟล์รูปภาพ
            $imagePath = 'img/header/' . $rows['img'];
            echo "<br>";
            echo "<img src='" . $imagePath . "' width='700px' >"; 
            echo "<p>" . $rows['img'] . "</p>";
            echo "</div>";
            echo "<form method='post' action='editing_header.php' enctype='multipart/form-data'>
        <div class='form-container'>
            <div class='form-group'>
                <input type='file' name='header' required />
                <input type='text' name='button' value='" . htmlspecialchars($rows['button'] ?? '') . "' />
            </div>
        </div>
        <button type='submit'>แก้ไขข้อมูล</button>
        <!--<a href='delete_header.php?del=" . $rows['headerID'] . "'>ลบ</a>-->
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
