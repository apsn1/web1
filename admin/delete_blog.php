<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ลบข้อมูลจากฐานข้อมูล
    $sql = "DELETE FROM blogs WHERE id = ?";
    
    // เตรียมคำสั่ง SQL
    if ($stmt = $conn->prepare($sql)) {
        // ผูกค่าพารามิเตอร์
        $stmt->bind_param("i", $id);

        // Execute การลบข้อมูล
        if ($stmt->execute()) {
            echo "ลบข้อมูลสำเร็จ";
        } else {
            echo "เกิดข้อผิดพลาดในการลบข้อมูล";
        }

        $stmt->close();
    }

    // เปลี่ยนหน้าไปที่หน้าแสดงบทความทั้งหมด
    header("Location: ../index.php"); // เปลี่ยนเส้นทางไปยังหน้าแรกหรือหน้าที่แสดงข้อมูลทั้งหมด
    exit;
} else {
    echo "ไม่พบข้อมูลที่ต้องการลบ";
}
?>