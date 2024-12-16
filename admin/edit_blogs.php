<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // ตรวจสอบว่ามีไฟล์อัปโหลดหรือไม่
    if (isset($_FILES["images"]) && count($_FILES["images"]["name"]) > 0) {
        $target_dir = "upload_blogs/";
        $uploaded_files = [];

        // วนลูปเพื่อตรวจสอบไฟล์แต่ละไฟล์
        for ($i = 0; $i < count($_FILES["images"]["name"]); $i++) {
            if ($_FILES["images"]["error"][$i] === UPLOAD_ERR_OK) {
                $target_file = $target_dir . basename($_FILES["images"]["name"][$i]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // ตรวจสอบชนิดไฟล์ที่อนุญาต
                if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    if ($_FILES["images"]["size"][$i] <= 50000000) { // ขนาดไฟล์ไม่เกิน 50MB
                        // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
                        $new_filename = uniqid() . "." . $imageFileType;
                        $target_file = $target_dir . $new_filename;

                        // อัปโหลดไฟล์
                        if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_file)) {
                            // เก็บชื่อไฟล์ใน array
                            $uploaded_files[] = $target_file;
                        } else {
                            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ที่ " . $_FILES["images"]["name"][$i];
                            exit;
                        }
                    } else {
                        echo "ไฟล์ " . $_FILES["images"]["name"][$i] . " มีขนาดใหญ่เกินไป (ขนาดสูงสุด 50MB).";
                        exit;
                    }
                } else {
                    echo "ไฟล์ " . $_FILES["images"]["name"][$i] . " อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น.";
                    exit;
                }
            } else {
                echo "ข้อผิดพลาดในการอัปโหลดไฟล์ที่ " . $_FILES["images"]["name"][$i];
                exit;
            }
        }

        // แปลง array ของชื่อไฟล์เป็น JSON
        $images_json = json_encode($uploaded_files);

        // ใช้ prepared statements เพื่อป้องกัน SQL Injection
        $sql = "INSERT INTO blogs (title, description, images) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $title, $description, $images_json);

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
        echo "กรุณาเลือกไฟล์รูปภาพ.";
    }
}
?>
