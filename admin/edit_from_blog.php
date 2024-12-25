<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลบทความจากฐานข้อมูล
    $sql = "SELECT * FROM blogs WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            ?> <?php
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
            <form action="edit_blog.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="title">หัวข้อ:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required><br>

                <label for="description">คำอธิบาย:</label>
                <textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea><br>

                <label for="images">อัพโหลดรูปภาพ:</label>
                <input type="file" name="images[]" multiple><br>

                <button type="submit">บันทึกการแก้ไข</button>
            </form>
            <?php
        } else {
            echo "ไม่พบข้อมูลบทความที่ต้องการแก้ไข";
        }

        $stmt->close();
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการแก้ไข";
}
?>
<style>
        /* จัดหน้า */
/* จัดหน้า */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
    padding: 30px;
}

/* กล่องฟอร์ม */
.form-container {
    max-width: 400px; /* ขนาดฟอร์มเล็กลง */
    margin: 50px auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 2px solid #007BFF; /* กรอบสีฟ้า */
    border-radius: 8px;
}

/* หัวข้อ */
h1 {
    text-align: center;
    color: #007BFF;
    font-size: 22px;
    margin-bottom: 20px;
}

/* label */
label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

/* input และ textarea */
input[type="text"], textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus, textarea:focus {
    border-color: #007BFF; /* เปลี่ยนสีเมื่อถูกเลือก */
    outline: none;
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

/* สไตล์สำหรับการเลือกไฟล์ */
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

/* เพิ่มให้รูปที่อัพโหลดแสดงเต็มความกว้าง */
.imges {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

</style>