<?php
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากแบบฟอร์ม
    $facebook = $_POST['facebook'];
    $tiktok = $_POST['tiktok'];
    $line = $_POST['line'];
    $youtube = $_POST['youtube'];

    // ตรวจสอบจำนวนแถวในตาราง footer_links
    $checkSql = "SELECT COUNT(*) AS total FROM footer_links";
    $checkResult = $conn->query($checkSql);
    $row = $checkResult->fetch_assoc();

    if ($row['total'] < 1) {
        $sql = "INSERT INTO footer_links (facebook, tiktok, line, youtube) VALUES (?, ?, ?, ?)";
        $row = $conn->prepare($sql);
        if ($row) {
            $row->bind_param("ssss", $facebook, $tiktok, $line, $youtube);
            if ($row->execute()) {
                echo "เพิ่มข้อมูลสำเร็จ";
            } else {
                echo "เพิ่มข้อมูลไม่สำเร็จ: " . $row->error;
            }
            $row->close();
        } else {
            echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error;
        }
    } else {
        // ถ้าแถวในตารางมีมากกว่าหรือเท่ากับ 1 ให้แก้ไขข้อมูลแทน
        $sql = "UPDATE footer_links SET facebook = ?, tiktok = ?, line = ?, youtube = ? WHERE id = 1";
        $row = $conn->prepare($sql);
        if ($row) {
            $row->bind_param("ssss", $facebook, $tiktok, $line, $youtube);
            if ($row->execute()) {
                header("Location: admin_panel.php");
            } else {
                echo "แก้ไขข้อมูลไม่สำเร็จ: " . $row->error;
            }
            $row->close();
        } else {
            echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error;
        }
    }
} else {
    echo "ไม่ได้ส่งข้อมูลผ่าน POST";
}
?>
