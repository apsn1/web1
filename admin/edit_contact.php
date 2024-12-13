<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // กรองข้อมูลที่รับมา
    $type = htmlspecialchars(trim($_POST['type']));
    $value = htmlspecialchars(trim($_POST['value']));

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']); // แปลงเป็นตัวเลขเพื่อความปลอดภัย

        // ใช้ prepared statement สำหรับการอัปเดต
        $stmt = $conn->prepare("UPDATE minicontacts SET type = ?, value = ? WHERE id = ?");
        $stmt->bind_param("ssi", $type, $value, $id);
    } else {
        // ใช้ prepared statement สำหรับการเพิ่มข้อมูลใหม่
        $stmt = $conn->prepare("INSERT INTO minicontacts (type, value) VALUES (?, ?)");
        $stmt->bind_param("ss", $type, $value);
    }

    // ตรวจสอบการดำเนินการ
    if ($stmt->execute()) {
        header("Location: ../index.php"); // กลับไปที่หน้าหลัก
        exit();
    } else {
        // บันทึกข้อผิดพลาดในไฟล์ log
        error_log("SQL Error: " . $stmt->error, 3, "../errors.log");
        echo "เกิดข้อผิดพลาด: กรุณาลองใหม่.";
    }

    // ปิด statement และการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
?>
