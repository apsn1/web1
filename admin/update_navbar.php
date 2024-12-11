<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $navbarID = $_POST['navbarID'];
    $name = $conn->real_escape_string($_POST['name']);
    $is_dropdown = isset($_POST['is_dropdown']) ? 1 : 0; // กำหนดสถานะ dropdown (1: ใช่, 0: ไม่ใช่)
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE navbar 
            SET name='$name', 
                is_dropdown='$is_dropdown', 
                meta_title='$meta_title', 
                meta_description='$meta_description', 
                meta_keywords='$meta_keywords' 
            WHERE id='$navbarID'";

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
    $navbarID = $_GET['edit'];

    // ดึงข้อมูลจากฐานข้อมูล
    $sql = "SELECT * FROM navbar WHERE id='$navbarID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $navbar = $result->fetch_assoc(); // เก็บข้อมูลในตัวแปร $navbar
    } else {
        die("ไม่พบข้อมูลที่ต้องการแก้ไข");
    }
}
?>

<!-- HTML ฟอร์มแก้ไข -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไข Navbar</title>
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
    <form method="POST" action="">
        <input type="hidden" name="navbarID" value="<?php echo $navbar['id']; ?>"> <!-- ซ่อน ID -->

        <!-- ชื่อเมนู -->
        <label for="name">ชื่อเมนู:</label>
        <input type="text" name="name" id="name" value="<?php echo $navbar['name']; ?>" required>
        <br>
        
        <!-- SEO: Meta Title -->
        <label for="meta_title">Meta Title:</label>
        <input type="text" name="meta_title" id="meta_title" value="<?php echo $navbar['meta_title']; ?>" maxlength="255">
        <br>

        <!-- SEO: Meta Description -->
        <label for="meta_description">Meta Description:</label>
        <textarea name="meta_description" id="meta_description" rows="4"><?php echo $navbar['meta_description']; ?></textarea>
        <br>

        <!-- SEO: Meta Keywords -->
        <label for="meta_keywords">Meta Keywords (คั่นด้วย ,):</label>
        <textarea name="meta_keywords" id="meta_keywords" rows="3"><?php echo $navbar['meta_keywords']; ?></textarea>
        <br>

        <!-- ปุ่มบันทึก -->
        <button type="submit">บันทึก</button>
    </form>
</body>
</html>