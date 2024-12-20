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

        <!-- ใช้ hidden input แทน textarea -->
        <input type="hidden" id="content" name="content" value="
Hello, world!
Welcome to My Page.
This is a default content of the page.
">

        <!-- ปุ่มส่งข้อมูล -->
        <button type="submit">สร้างไฟล์</button>
    </form>

    <br><br>
    <!-- ปุ่มสำหรับยืนยันการสร้างไฟล์ -->

    <h2>รายการไฟล์ที่บันทึกไว้</h2>
    <?php
    include('../../db.php'); // เชื่อมต่อฐานข้อมูล
    
    // ดึงข้อมูลบทความทั้งหมดจากฐานข้อมูล
    $sql = "SELECT * FROM filemanager ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        echo '<ul>';
        while ($row = $result->fetch_assoc()):
            // สร้างเส้นทางของไฟล์จากชื่อไฟล์ในฐานข้อมูล
            $filePath = '../' . $row['filename'];

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
<style>
    /* รีเซ็ต Margin และ Padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        margin: 20px;
    }

    h2 {
        text-align: center;
        color: #007bff;
        margin-bottom: 20px;
    }

    form {
        background: #fff;
        padding: 20px;
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
        color: #555;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    textarea {
        resize: vertical;
    }

    button {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #218838;
    }

    ul {
        list-style: none;
        margin: 20px auto;
        padding: 0;
        max-width: 600px;
    }

    li {
        background: #fff;
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    li strong {
        color: #007bff;
    }

    a {
        color: #28a745;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        color: #218838;
        text-decoration: underline;
    }

    span {
        color: #dc3545;
    }
</style>