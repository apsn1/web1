<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    // กำหนดโฟลเดอร์ที่ต้องการเก็บไฟล์
    $target_dir = "uploads_project/"; // หรือโฟลเดอร์ที่คุณต้องการ
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ตรวจสอบว่าไฟล์เป็นรูปภาพจริงหรือไม่
    if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
        echo "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ.";
        $uploadOk = 0;
    }

    // ตรวจสอบว่ามีไฟล์ที่มีชื่อเดียวกันหรือไม่
    if (file_exists($target_file)) {
        echo "ไฟล์นี้มีอยู่แล้ว.";
        $uploadOk = 0;
    }

    // ตรวจสอบขนาดไฟล์ (ถ้าต้องการจำกัดขนาดไฟล์)
    if ($_FILES["image"]["size"] > 500000000000) {
        echo "ขนาดไฟล์ใหญ่เกินไป.";
        $uploadOk = 0;
    }

    // จำกัดประเภทไฟล์ที่อนุญาต
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "ขออภัย, เฉพาะไฟล์ .jpg, .jpeg, .png, .gif เท่านั้น.";
        $uploadOk = 0;
    }

    // ถ้าทุกอย่างโอเค ให้ย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง
    if ($uploadOk == 0) {
        echo "การอัปโหลดไฟล์ล้มเหลว.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "ไฟล์ " . htmlspecialchars(basename($_FILES["image"]["name"])) . " ได้รับการอัปโหลด.";

            // บันทึกข้อมูลลงในฐานข้อมูล
            $alt_text = htmlspecialchars(trim($_POST['alt_text']));
            $sql = "INSERT INTO projects (image_path, alt_text) VALUES ('$target_file', '$alt_text')";
            if ($conn->query($sql) === TRUE) {
                echo "บันทึกข้อมูลรูปภาพสำเร็จ";
                header("Location: ../index.php"); // กลับไปหน้าหลักหลังอัปโหลดเสร็จ
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
        }
    }
}
?>
