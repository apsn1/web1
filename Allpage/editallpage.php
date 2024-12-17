
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างไฟล์ PHP</title>
</head>

<body>
    <h2>สร้างไฟล์ PHP</h2>
    <form action="../admin/create_file.php" method="post">
        <!-- ฟิลด์สำหรับกรอกชื่อไฟล์ -->
        <label for="filename">ชื่อไฟล์ (เช่น index1.php):</label>
        <input type="text" id="filename" name="filename" placeholder="ชื่อไฟล์ (ไม่ต้องใส่นามสกุล .php)" required>
        <br><br>

        <!-- ฟิลด์สำหรับกรอกเนื้อหาของไฟล์ -->
        <label for="content">เนื้อหาภายในไฟล์:</label><br>
        <textarea id="content" name="content" rows="10" cols="50">
<?php
echo "Hello, world!";
?>
</textarea>
        <br><br>

        <!-- ปุ่มสำหรับยืนยันการสร้างไฟล์ -->
        <button type="submit">สร้างไฟล์</button>
    </form>
    <h2>รายการไฟล์ที่บันทึกไว้</h2>
    <?php
    include('../db.php'); // เชื่อมต่อฐานข้อมูล

    // ดึงข้อมูลบทความทั้งหมดจากฐานข้อมูล
    $sql = "SELECT * FROM filemanager ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        echo '<ul>';
        while ($row = $result->fetch_assoc()):
            // สร้างเส้นทางของไฟล์จากชื่อไฟล์ในฐานข้อมูล
            $filePath = '' . $row['filename'];
            
            // ตรวจสอบว่าไฟล์มีอยู่ใน directory หรือไม่
            if (file_exists($filePath)) {
                // หากไฟล์มีอยู่ แสดงลิงก์ไปยังไฟล์
                echo '<li><strong>' . htmlspecialchars($row['filename']) . '</strong> - <a href="' . $filePath . '" target="_blank">ดูเนื้อหา</a></li>';
            } else {
                // หากไฟล์ไม่พบ แสดงข้อความว่า "ไฟล์ไม่พบ"
                echo '<li><strong>' . htmlspecialchars($row['filename']) . '</strong> - <span>ไฟล์ไม่พบ</span></li>';
            }
        endwhile;
        echo '</ul>';
    else:
        echo '<p>ยังไม่มีไฟล์ที่ถูกบันทึกไว้</p>';
    endif;

    $conn->close();
?>


</body>

</html>