<?php
include('../db.php');

// ตรวจสอบว่าไฟล์และปุ่มถูกส่งมาจากฟอร์มหรือไม่
if (!isset($_FILES['header']) || $_FILES['header']['error'] !== UPLOAD_ERR_OK) {
    die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์");
}

if (!isset($_POST['header_button'])) {
    die("ไม่ได้รับข้อมูลปุ่ม");
}

$file_name = time() . '_' . basename($_FILES['header']['name']); // เพิ่มเวลาเพื่อป้องกันชื่อไฟล์ซ้ำ
$tempname = $_FILES['header']['tmp_name'];
$button = $conn->real_escape_string($_POST['header_button']); // ป้องกัน SQL Injection
$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/web1/admin/img/header/";
$target_file = $target_dir . $file_name;

// ตรวจสอบจำนวนรูปภาพในฐานข้อมูล
$sql_count = "SELECT COUNT(*) AS total FROM header_images";
$result = mysqli_query($conn, $sql_count);
$row = mysqli_fetch_assoc($result);
if ($row['total'] >= 4) {
    die("ไม่สามารถเพิ่มรูปได้ เนื่องจากมีรูปแบนเนอร์ครบ 4 รูปแล้ว");
}

// ตรวจสอบประเภทไฟล์ที่อนุญาต
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$file_type = mime_content_type($_FILES['header']['tmp_name']);
if (!in_array($file_type, $allowed_types)) {
    die("ประเภทไฟล์ไม่ถูกต้อง อนุญาตเฉพาะ JPG, PNG และ GIF");
}

// บันทึกไฟล์ไปยังเซิร์ฟเวอร์
if (move_uploaded_file($tempname, $target_file)) {
    // ใช้ Prepared Statement สำหรับคำสั่ง SQL
    $stmt = $conn->prepare("INSERT INTO header_images (img, button) VALUES (?, ?)");
    $stmt->bind_param("ss", $file_name, $button);

    if ($stmt->execute()) {
        echo "เพิ่มรูปสำเร็จ";
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
    $stmt->close();
} else {
    die("อัพโหลดไฟล์ไม่สำเร็จ");
}

$conn->close();
?>
