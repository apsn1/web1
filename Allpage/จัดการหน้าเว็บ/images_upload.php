<?php
include('../../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetDir = "images_all/";
    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $fileName;

    // ตรวจสอบชนิดไฟล์
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    if (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png', 'gif'])) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // บันทึกชื่อไฟล์ในฐานข้อมูล
            $sql = "INSERT INTO images_all (filename) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $fileName);

            if ($stmt->execute()) {
                echo "อัปโหลดภาพสำเร็จ!";
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกข้อมูลในฐานข้อมูล.";
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
        }
    } else {
        echo "ชนิดไฟล์ไม่รองรับ. โปรดอัปโหลดไฟล์ jpg, jpeg, png หรือ gif.";
    }
}
?>

