<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // สร้างรายการในฐานข้อมูลก่อนเพื่อรับ ID
    $sql = "INSERT INTO blogs (title, description) VALUES ('$title', '$description')";
    if ($conn->query($sql)) {
        $blog_id = $conn->insert_id; // ดึง ID ที่เพิ่งสร้างขึ้นมา
        $uploaded_images = []; // เก็บชื่อไฟล์ที่อัปโหลดสำเร็จ

        // ตรวจสอบว่ามีไฟล์อัปโหลดหรือไม่
        if (isset($_FILES["images"]) && count($_FILES["images"]["name"]) > 0) {
            foreach ($_FILES["images"]["name"] as $key => $name) {
                if ($_FILES["images"]["error"][$key] === UPLOAD_ERR_OK) {
                    $target_dir = "upload_blogs/";
                    $imageFileType = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                    // ตรวจสอบชนิดไฟล์ที่อนุญาต
                    if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        if ($_FILES["images"]["size"][$key] <= 5000000) { // ตรวจสอบขนาดไฟล์ไม่เกิน 5MB
                            // สร้างชื่อไฟล์ใหม่ เช่น image1.jpg
                            $new_filename = "image" . $blog_id . "_" . ($key + 1) . "." . $imageFileType;
                            $target_file = $target_dir . $new_filename;

                            if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                                $uploaded_images[] = $new_filename; // เก็บชื่อไฟล์ที่อัปโหลดสำเร็จ
                            } else {
                                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: $name<br>";
                            }
                        } else {
                            echo "ไฟล์ $name มีขนาดใหญ่เกินไป.<br>";
                        }
                    } else {
                        echo "ไฟล์ $name: อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น.<br>";
                    }
                } else {
                    echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: $name<br>";
                }
            }

            // อัปเดตชื่อไฟล์ที่อัปโหลดลงฐานข้อมูล
            if (!empty($uploaded_images)) {
                $images_json = json_encode($uploaded_images); // แปลง array เป็น JSON
                $update_sql = "UPDATE blogs SET images = '$images_json' WHERE id = $blog_id";
                if ($conn->query($update_sql)) {
                    header('Location: ../index.php');
                    exit;
                } else {
                    echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $conn->error;
                }
            } else {
                echo "ไม่มีไฟล์ที่อัปโหลดสำเร็จ.";
            }
        } else {
            echo "กรุณาเลือกไฟล์รูปภาพ.";
        }
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างบล็อก: " . $conn->error;
    }
}
?>
