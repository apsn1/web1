<?php
include('../db.php');

// ตรวจสอบว่าทั้งสองไฟล์ถูกส่งมาจากฟอร์มหรือไม่
if (!isset($_FILES['header']) || $_FILES['header']['error'] !== UPLOAD_ERR_OK) {
    die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์ปกเว็บไซต์");
}

if (!isset($_FILES['img_vertical']) || $_FILES['img_vertical']['error'] !== UPLOAD_ERR_OK) {
    die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์รูปแนวตั้ง");
}

if (!isset($_POST['header_button'])) {
    die("ไม่ได้รับข้อมูลปุ่ม");
}

$button = $conn->real_escape_string($_POST['header_button']); // ป้องกัน SQL Injection

// ฟังก์ชันสำหรับการอัปโหลดไฟล์
function uploadImage($file, $uploadDir) {
    // ตรวจสอบชนิดไฟล์ที่อนุญาต
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    $file_type = mime_content_type($file['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        die("ประเภทไฟล์ไม่ถูกต้อง อนุญาตเฉพาะ JPG, PNG, GIF, WEBP, SVG");
    }

    // ตรวจสอบขนาดไฟล์ (จำกัดไว้ที่ 5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxFileSize) {
        die("ขนาดไฟล์เกินที่กำหนด (สูงสุด 5MB)");
    }

    // สร้างชื่อไฟล์ใหม่เพื่อป้องกันการชนกัน
    $file_name = time() . '_' . basename($file['name']);
    $target_file = $uploadDir . $file_name;

    // สร้างไดเรกทอรีถ้ายังไม่มี
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // ย้ายไฟล์ไปยังไดเรกทอรีปลายทาง
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $file_name;
    } else {
        die("ไม่สามารถอัปโหลดไฟล์ได้");
    }
}

// กำหนดไดเรกทอรีสำหรับเก็บไฟล์
$headerDir = $_SERVER['DOCUMENT_ROOT'] . "/web/admin/img/header/";
$verticalDir = $_SERVER['DOCUMENT_ROOT'] . "/web/admin/img/vertical/";

// อัปโหลดไฟล์
$headerFileName = uploadImage($_FILES['header'], $headerDir);
$verticalFileName = uploadImage($_FILES['img_vertical'], $verticalDir);

// ตรวจสอบจำนวนรูปภาพในฐานข้อมูล
$sql_count = "SELECT COUNT(*) AS total FROM header_images";
$result = mysqli_query($conn, $sql_count);
$row = mysqli_fetch_assoc($result);
if ($row['total'] >= 4) {
    // ลบไฟล์ที่อัปโหลดเพื่อป้องกันไม่ให้ไฟล์ถูกเก็บไว้โดยไม่จำเป็น
    unlink($headerDir . $headerFileName);
    unlink($verticalDir . $verticalFileName);
    die("ไม่สามารถเพิ่มรูปได้ เนื่องจากมีรูปแบนเนอร์ครบ 4 รูปแล้ว");
}

// ใช้ Prepared Statement สำหรับคำสั่ง SQL
$stmt = $conn->prepare("INSERT INTO header_images (img, img_vertical, button) VALUES (?, ?, ?)");
if ($stmt === false) {
    die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error);
}
$stmt->bind_param("sss", $headerFileName, $verticalFileName, $button);

if ($stmt->execute()) {
    echo "เพิ่มรูปสำเร็จ";
    header("Location: admin_panel.php");
    exit;
} else {
    // ลบไฟล์ที่อัปโหลดหากเกิดข้อผิดพลาดในการบันทึกข้อมูล
    unlink($headerDir . $headerFileName);
    unlink($verticalDir . $verticalFileName);
    die("เกิดข้อผิดพลาด: " . $stmt->error);
}


?>
