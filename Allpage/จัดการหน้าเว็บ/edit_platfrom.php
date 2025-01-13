<?php
// edit_platform.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform_name = $_POST['platform_name'] ?? '';
    $platform_link = $_POST['platform_link'] ?? '';

    // เรียกไฟล์เชื่อมต่อฐานข้อมูล
    include('../../db.php');

    // เลือกชื่อตารางตามแพลตฟอร์ม
    switch ($platform_name) {
        case 'YouTube':
            $table = 'card_youtube';
            break;
        case 'Facebook':
            $table = 'card_facebook';
            break;
        case 'TikTok':
            $table = 'card_tiktok';
            break;
        default:
            // ถ้าไม่มี match จะไม่บันทึก หรือจะกำหนดตาราง default ก็ได้
            die('แพลตฟอร์มไม่ถูกต้อง!');
    }

    // เตรียมคำสั่ง SQL (INSERT ลงตารางที่เลือก)
    $sql = "INSERT INTO $table (platform_name, platform_link) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $platform_name, $platform_link);

    if ($stmt->execute()) {
        echo "บันทึกข้อมูลสำเร็จลงตาราง " . $table;
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>
