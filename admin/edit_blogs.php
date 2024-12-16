<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // ตรวจสอบว่ามีไฟล์อัปโหลดหรือไม่
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "upload_blogs/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบชนิดไฟล์ที่อนุญาต
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if ($_FILES["image"]["size"] <= 5000000) { // ตรวจสอบขนาดไฟล์ไม่เกิน 5MB
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // บันทึกข้อมูลลงฐานข้อมูล
                    $sql = "INSERT INTO blogs (title, description, image) VALUES ('$title', '$description', '$target_file')";
                    if ($conn->query($sql)) {
                        header('Location: index.php');
                        exit;
                    } else {
                        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
                    }
                } else {
                    echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
                }
            } else {
                echo "ไฟล์มีขนาดใหญ่เกินไป.";
            }
        } else {
            echo "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น.";
        }
    } else {
        echo "กรุณาเลือกไฟล์รูปภาพ.";
    }
}

?>
