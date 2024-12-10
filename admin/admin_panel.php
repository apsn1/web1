<?php include('../db.php'); ?>
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
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css"> <!-- เพิ่มลิงค์ไปยังไฟล์ CSS -->
</head>

<body>
    <h1>จัดการหน้าเมนูหน้าเว็บ</h1>
    <form method="POST" action="add_navbar.php">
        <div class="form-container">
            <form action="upload_logo.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="name" placeholder="ชื่อหัวข้อ" required>
                    <button type="submit">อัปโหลด</button>
                </div>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อหัวข้อ</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM navbar";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // วนลูปแสดงข้อมูล
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td> <a href='delete_navbar.php?del=" . $row["id"] . "'>ลบ</a>" . "</td>";
                                echo "</tr>";

                            }
                        } else {
                            echo "<tr><td colspan='2'>ไม่มีข้อมูล</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
        </div>
    </form>
    <h1>อัพโหลด Logo</h1>
    <form action="upload_update.php" method="post" enctype="multipart/form-data">
        <label for="logo">เลือกรูปภาพใหม่:</label>
        <input type="file" name="logo" id="logo" accept="image/*">
        <button type="submit">อัปโหลดและอัปเดต</button>
        <?php
        $sql = "SELECT * FROM navbar";
        $result = $conn->query($sql);
        $directory = 'uploads/';
         echo "<div class ='imageShow'>";                  
        // ตรวจสอบว่าโฟลเดอร์มีอยู่จริง
        if (is_dir($directory)) {
            $files = scandir($directory);

            // ตรวจสอบว่า scandir() คืนค่าไม่เป็น false
            if ($files !== false) {
                // ลบ . และ .. ออกจากลิสต์
                $files = array_diff($files, array('.', '..'));

                // กรองเฉพาะไฟล์ที่เป็นรูปภาพ (เช่น .jpg, .png)
                $imageFiles = array_filter($files, function ($file) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    return in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']); // เพิ่มประเภทไฟล์ที่ต้องการ
                });

                // ตรวจสอบว่าอาร์เรย์ $imageFiles มีไฟล์รูปภาพ
                if (count($imageFiles) > 0) {
                    // เลือกรูปภาพแรกจากโฟลเดอร์
                    $image = reset($imageFiles); // เลือกรูปภาพแรกจากอาร์เรย์ที่กรองแล้ว
                    
                    echo "<img src='$directory$image' alt='รูปภาพล่าสุด' style='height: 75px; width: 97px; margin-right: 50px;'>";
                } else {
                    echo "ไม่มีรูปภาพในโฟลเดอร์ uploads";
                }
            } else {
                echo "ไม่สามารถอ่านไฟล์ในโฟลเดอร์ uploads ได้";
            }
        } else {
            echo "ไม่พบโฟลเดอร์ uploads";
        }
        echo "</div>";       
        ?>
    </form>
    </div>

    </form>

    <h1>จัดการข้อมูลหน้าเว็บ</h1>
    <form method="POST" action="add_content.php" enctype="multipart/form-data">
        <div class="form-container">
            <div class="form-group">
                <input type="text" name="title" placeholder="ชื่อหัวข้อ" required>
            </div>
            <div class="form-group">
                <textarea name="body" placeholder="เนื้อหา" required></textarea>
            </div>
            <div class="form-group">
                <input type="file" name="SEO_image" placeholder="รูปภาพ" />
            </div>
        </div>

        <button type="submit">เพิ่มข้อมูล</button>
    </form>
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