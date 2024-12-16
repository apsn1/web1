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
            if ($_FILES["image"]["size"] <= 50000000) { // ตรวจสอบขนาดไฟล์ไม่เกิน 50MB
                // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
                $new_filename = uniqid() . "." . $imageFileType;
                $target_file = $target_dir . $new_filename;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // ใช้ prepared statements เพื่อป้องกัน SQL Injection
                    $sql = "INSERT INTO blogs (title, description, image) VALUES (?, ?, ?)";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sss", $title, $description, $target_file);

                        if ($stmt->execute()) {
                            header('Location: ../index.php');
                            exit;
                        } else {
                            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
                        }

                        $stmt->close();
                    } else {
                        echo "ไม่สามารถเตรียมคำสั่ง SQL ได้.";
                    }
                } else {
                    echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
                }
            } else {
                echo "ไฟล์มีขนาดใหญ่เกินไป (ขนาดสูงสุด 50MB).";
            }
        } else {
            echo "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น.";
        }
    } else {
        // ตรวจสอบข้อผิดพลาดของการอัปโหลดไฟล์
        if (isset($_FILES["image"])) {
            echo "ข้อผิดพลาดในการอัปโหลดไฟล์: " . $_FILES["image"]["error"];
        } else {
            echo "กรุณาเลือกไฟล์รูปภาพ.";
        }
    }
}
?>
