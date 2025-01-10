<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../../db.php'); // เชื่อมต่อฐานข้อมูล

    // ตรวจสอบและดึงข้อมูลจากแบบฟอร์ม
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $link_to_product = isset($_POST['link_to_product']) ? trim($_POST['link_to_product']) : '';
    $description_product = isset($_POST['description_product']) ? trim($_POST['description_product']) : '';

    $uploaded_images = []; // เก็บไฟล์ที่อัปโหลดทั้งหมด
    $src_image_cover = null; // เก็บไฟล์หน้าปก

    $upload_dir = 'images_product/';
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // ตรวจสอบการอัปโหลดรูปภาพหลายรูป
    if (isset($_FILES['src_images'])) {
        $total_files = count($_FILES['src_images']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            $file_name = $_FILES['src_images']['name'][$i];
            $file_tmp = $_FILES['src_images']['tmp_name'][$i];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // ตรวจสอบชนิดไฟล์
            if (!in_array($file_ext, $allowed_extensions)) {
                continue; // ข้ามไฟล์ที่ไม่ได้รับอนุญาต
            }

            // สร้างชื่อไฟล์ใหม่และย้ายไฟล์ไปยังโฟลเดอร์เป้าหมาย
            $new_file_name = uniqid('img_', true) . '.' . $file_ext;
            $file_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $uploaded_images[] = $file_path;
            }
        }
    }

    // จัดการการอัปโหลดรูปภาพหน้าปก
    if (isset($_FILES['src_image_cover']) && $_FILES['src_image_cover']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['src_image_cover']['name'];
        $file_tmp = $_FILES['src_image_cover']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_extensions)) {
            $new_cover_name = uniqid('cover_', true) . '.' . $file_ext;
            $src_image_cover = $upload_dir . $new_cover_name;

            if (!move_uploaded_file($file_tmp, $src_image_cover)) {
                $src_image_cover = null; // ตั้งค่า null หากการอัปโหลดล้มเหลว
            }
        }
    }

    // ตรวจสอบว่ามีข้อมูลเพียงพอในการบันทึกลงฐานข้อมูล
    if ($name && $product_name && $description_product) {
        // JSON Encode รูปภาพที่อัปโหลด
        $images_json = json_encode($uploaded_images);

        // บันทึกข้อมูลลงฐานข้อมูล
        $sql = "INSERT INTO product (name, product_name, link_to_product, description_product, src_image_cover, src_image) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $product_name, $link_to_product, $description_product, $src_image_cover, $images_json);

        if ($stmt->execute()) {
            echo "Product uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Missing required fields.";
    }

    $conn->close();
} else {
    echo "Error: Invalid request method.";
}
?>
