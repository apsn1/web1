<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $contentID = $_POST['contentID'];
    $title = $conn->real_escape_string($_POST['title']);
    $body = $conn->real_escape_string($_POST['body']);

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE content SET title='$title', body='$body' WHERE contentID='$contentID'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location.href='../index.php';</script>"; // แจ้งเตือนและกลับไปหน้าหลัก
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
} else {
    // ตรวจสอบว่ามี ID ที่ต้องการแก้ไขหรือไม่
    if (!isset($_GET['edit'])) {
        die("ไม่มี ID ที่ต้องการแก้ไข");
    }
    $contentID = $_GET['edit'];

    // ดึงข้อมูลจากฐานข้อมูล
    $sql = "SELECT * FROM content WHERE contentID='$contentID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc(); // เก็บข้อมูลในตัวแปร $content
    } else {
        die("ไม่พบข้อมูลที่ต้องการแก้ไข");
    }
}
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูล</title>
    <style>
        /* จัดหน้า */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            padding: 90px;
        }

        /* กล่องฟอร์ม */
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* หัวข้อ */
        h1 {
            text-align: center;
            color: #007BFF;
        }

        /* label */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* input และ textarea */
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        /* ปุ่ม */
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* สำหรับหน้าจอขนาดเล็ก */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }

            input[type="text"], textarea {
                font-size: 14px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <h1>แก้ไขข้อมูล</h1>
    <form action="" method="POST">
        <input type="hidden" name="contentID" value="<?php echo $content['contentID']; ?>">
        <label>ชื่อเรื่อง:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($content['title']); ?>" required>
        <br>
        <label>เนื้อหา:</label>
        <textarea name="body" required><?php echo htmlspecialchars($content['body']); ?></textarea>
        <br>
        <button type="submit">บันทึก</button>
    </form>
</body>
</html>
