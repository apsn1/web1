<?php
// การเชื่อมต่อฐานข้อมูล
include('../db.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบการส่งข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $filename = trim($_POST['filename']);
    $content = $_POST['content'];

    // ตรวจสอบชื่อไฟล์และทำความสะอาด
    if (empty($filename)) {
        die("กรุณากรอกชื่อไฟล์ให้ถูกต้อง!");
    }
    $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);
    $filename .= ".php"; // เพิ่ม .php ให้อัตโนมัติ

    // ระบุตำแหน่งสำหรับบันทึกไฟล์
    $directory = "../Allpage/"; // เปลี่ยนตำแหน่งตามต้องการ
    $fullPath = $directory . $filename;

    // ตรวจสอบว่าโฟลเดอร์มีอยู่หรือไม่ ถ้าไม่ให้สร้างขึ้น
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true); // สร้างโฟลเดอร์และกำหนด permission
    }

    // บันทึกไฟล์ลงโฟลเดอร์
    if (file_put_contents($fullPath, $content) !== false) {
        echo "ไฟล์ <strong>$filename</strong> ถูกสร้างที่ตำแหน่ง <strong>$directory</strong> เรียบร้อยแล้ว!<br>";
    } else {
        die("เกิดข้อผิดพลาดในการบันทึกไฟล์ที่ตำแหน่ง $directory");
    }

    // บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO filemanager (filename, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $filename, $content);

    if ($stmt->execute()) {
        echo "บันทึกข้อมูลไฟล์ลงฐานข้อมูลเรียบร้อยแล้ว!";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกฐานข้อมูล: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
} else {
    echo "ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง!";
}
?>
