<?php
include('../../db.php'); // เชื่อมต่อฐานข้อมูล

// จัดการการอัปโหลดภาพ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetDir = "images_all/";
    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $fileName;

    // ตรวจสอบชนิดไฟล์
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    if (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png', 'gif'])) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // บันทึกชื่อไฟล์ในฐานข้อมูล
            $sql = "INSERT INTO images_all (filename) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $fileName);

            if ($stmt->execute()) {
                $message = "อัปโหลดภาพสำเร็จ!";
            } else {
                $message = "เกิดข้อผิดพลาดในการบันทึกข้อมูลในฐานข้อมูล.";
            }
        } else {
            $message = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
        }
    } else {
        $message = "ชนิดไฟล์ไม่รองรับ. โปรดอัปโหลดไฟล์ jpg, jpeg, png หรือ gif.";
    }
}

// ดึงข้อมูลภาพทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM images_all ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<?php
    // Path to the folder
    $folderPath = "../../admin/uploads/";

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คลังภาพ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .upload-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }
        .gallery img {
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .gallery-item {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>คลังภาพ</h1>

    <!-- ฟอร์มอัปโหลดภาพ -->
    <div class="upload-form">
        <form action="images_upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit">อัปโหลดภาพ</button>
        </form>
        <?php if (isset($message)): ?>
            <p style="color: green;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>

    <!-- แสดงภาพทั้งหมด -->
    <div class="gallery">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="gallery-item">
                    <img src="images_all/<?= htmlspecialchars($row['filename']) ?>" alt="Image">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center;">ยังไม่มีภาพในคลัง</p>
        <?php endif; ?>
    </div>
</body>
</html>
