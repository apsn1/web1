<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "website_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $videoTitle = $_POST['video_title'];  // รับชื่อวิดีโอจากฟอร์ม
    $videoLink = $_POST['video_link'];    // รับลิงก์วิดีโอจากฟอร์ม

    // ตรวจสอบและแปลงลิงก์ YouTube ให้เป็นรูปแบบ embed
    if (strpos($videoLink, 'watch?v=') !== false) {
        $videoLink = str_replace('watch?v=', 'embed/', $videoLink);
    }

    // อัปเดตทั้งชื่อและลิงก์วิดีโอ (ถ้ามีอยู่แล้วจะแทนที่)
    $stmt = $conn->prepare("REPLACE INTO videos (id, video_title, video_link) VALUES (1, ?, ?)");
    $stmt->bind_param("ss", $videoTitle, $videoLink);  // bind ชื่อและลิงก์วิดีโอ

    if ($stmt->execute()) {
        echo "อัปเดตวิดีโอสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // กลับไปที่หน้าแสดงวิดีโอ
    header("Location: ../index.php");
    exit;
}
?>
