<?php
include('../db.php'); // เรียกใช้การเชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากแบบฟอร์ม
    $facebook = $_POST['facebook'];
    $tiktok = $_POST['tiktok'];
    $line = $_POST['line'];

    // ตรวจสอบจำนวนแถวในตาราง footer_links
    $checkSql = "SELECT COUNT(*) AS total FROM footer_links";
    $checkResult = $conn->query($checkSql);
    $row = $checkResult->fetch_assoc();

    if ($row['total'] < 1) {
        // ถ้าแถวในตารางมีน้อยกว่า 1 ให้เพิ่มข้อมูลใหม่
        $sql = "INSERT INTO footer_links (facebook, tiktok, line) VALUES (?, ?, ?)";
        $row = $conn->prepare($sql);
        if ($row) {
            $row->bind_param("sss", $facebook, $tiktok, $line);
            if ($row->execute()) {
                echo "เพิ่มข้อมูลสำเร็จ"; // แจ้งเตือนเมื่อสำเร็จ
            } else {
                echo "เพิ่มข้อมูลไม่สำเร็จ: " . $row->error; // แจ้งเตือนข้อผิดพลาด
            }
            $row->close();
        } else {
            echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error;
        }
    } else {
        // ถ้าแถวในตารางมีมากกว่าหรือเท่ากับ 1 ให้แก้ไขข้อมูลแทน
        $sql = "UPDATE footer_links SET facebook = ?, tiktok = ?, line = ? WHERE id = 1";
        $row = $conn->prepare($sql);
        if ($row) {
            $row->bind_param("sss", $facebook, $tiktok, $line);
            if ($row->execute()) {
                header("Location: admin_panel.php"); // แจ้งเตือนเมื่อสำเร็จ
            } else {
                echo "แก้ไขข้อมูลไม่สำเร็จ: " . $row->error; // แจ้งเตือนข้อผิดพลาด
            }
            $row->close();
        } else {
            echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error;
        }
    }
} else {
    echo "ไม่ได้ส่งข้อมูลผ่าน POST"; // กรณีไม่ได้ใช้ POST
}
?>
