<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "uploads/";

    // ตรวจสอบว่าโฟลเดอร์อัปโหลดมีอยู่หรือไม่
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // เชื่อมต่อฐานข้อมูล
    $conn = new mysqli("localhost", "root", "", "website_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ดึงไฟล์เก่าจากฐานข้อมูล
    $sql = "SELECT file_path FROM images WHERE id = 1"; // สมมติว่า id = 1 เป็นไฟล์ล่าสุด
    $result = $conn->query($sql);
    $oldFile = '';
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $oldFile = $row['file_path'];
    }

    // รับไฟล์ใหม่จากฟอร์ม
    $file = $_FILES['logo'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION); // เอานามสกุลไฟล์
    $fileName = "Logo_" . time() . "." . $fileExtension; // ตั้งชื่อไฟล์ใหม่ เช่น Logo_1632268292.jpg
    $targetFile = $uploadDir . $fileName;

    // ตรวจสอบประเภทไฟล์ (รองรับเฉพาะภาพ)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($file['type'], $allowedTypes)) {
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // ลบไฟล์เก่าหากมี
            if ($oldFile && file_exists($oldFile)) {
                unlink($oldFile);
            }

            // อัปเดตไฟล์ใหม่ในฐานข้อมูล
            $sql = "REPLACE INTO images (id, file_name, file_path) VALUES (1, '$fileName', '$targetFile')";
            if ($conn->query($sql) === TRUE) {
                // หลังจากอัปเดตฐานข้อมูลเสร็จให้กลับไปหน้า index.php
                header("Location: ../index.php");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกฐานข้อมูล: " . $conn->error;
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    } else {
        echo "ประเภทไฟล์ไม่ถูกต้อง (รองรับ JPG, PNG, GIF)";
    }

    $conn->close();
}
?>
