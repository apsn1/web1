<?php
// เชื่อมต่อฐานข้อมูล
include('../../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $text_description = $_POST['text_description'];
    $text_nameproduct = $_POST['text_nameproduct'];
    $link_Social = $_POST['link_Social'];
    
    // รับข้อมูล img_onTop เป็น array
    $img_onTop = isset($_POST['img_onTop']) ? $_POST['img_onTop'] : [];
    // แปลง array เป็น JSON เพื่อเก็บในฐานข้อมูล
    $img_onTop_json = json_encode($img_onTop);
    
    // เตรียมตัวแปรสำหรับไฟล์อัปโหลด
    $uploadDir = '../uploads/';
    $img_product = '';
    $img_others = [];

    // ฟังก์ชันช่วยเหลือสำหรับการอัปโหลดไฟล์
    function uploadFile($file, $uploadDir) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            if (in_array($file['type'], $allowedTypes)) {
                $uniqueName = uniqid() . '_' . basename($file['name']);
                $targetPath = $uploadDir . $uniqueName;
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    return $uniqueName; // Return the file name
                }
            }
        }
        return false;
    }

    // อัปโหลดภาพหลักของสินค้า
    if (isset($_FILES['img_product']) && $_FILES['img_product']['name'] !== '') {
        $uploadedFile = uploadFile($_FILES['img_product'], $uploadDir);
        if ($uploadedFile) {
            $img_product = $uploadedFile;
        } else {
            die("การอัปโหลด img_product ล้มเหลว หรือไม่รองรับประเภทไฟล์");
        }
    }

    // อัปโหลดภาพเพิ่มเติม (หลายไฟล์)
    if (isset($_FILES['img_others'])) {
        foreach ($_FILES['img_others']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['img_others']['name'][$key] !== '') {
                $fileArray = [
                    'name' => $_FILES['img_others']['name'][$key],
                    'type' => $_FILES['img_others']['type'][$key],
                    'tmp_name' => $_FILES['img_others']['tmp_name'][$key],
                    'error' => $_FILES['img_others']['error'][$key],
                    'size' => $_FILES['img_others']['size'][$key]
                ];
                $uploadedFile = uploadFile($fileArray, $uploadDir);
                if ($uploadedFile) {
                    $img_others[] = $uploadedFile;
                }
            }
        }
    }

    // แปลง array ของ img_others เป็น JSON สำหรับการเก็บในฐานข้อมูล
    $img_others_json = json_encode($img_others);

    // ใช้ prepared statements เพื่อความปลอดภัย
    $stmt = $conn->prepare("INSERT INTO page_aboutme (name, text_description, text_nameproduct, img_product, img_others, link_Social, img_onTop) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("เตรียม statement ล้มเหลว: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $name, $text_description, $text_nameproduct, $img_product, $img_others_json, $link_Social, $img_onTop_json);

    if ($stmt->execute()) {
        // ถ้าบันทึกสำเร็จ สร้างไฟล์ใหม่
        // ตรวจสอบและ sanitize ชื่อไฟล์เพื่อความปลอดภัย
        $safe_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $file_name = "../" . $safe_name . ".php";

        // แปลง img_onTop_json กลับเป็น arrayเพื่อใช้งานในไฟล์ PHP ใหม่
        $img_onTop_array = json_decode($img_onTop_json, true);
        $img_onTop_html = '';
        if (!empty($img_onTop_array)) {
            foreach ($img_onTop_array as $img_url) {
                $img_onTop_html .= "<img src=\"../uploads/" . htmlspecialchars($img_url) . "\" alt=\"" . htmlspecialchars($name) . "\" style=\"width:100px; margin-right:10px;\">";
            }
        }

        // แปลง img_others_json กลับเป็น arrayเพื่อใช้งานในไฟล์ PHP ใหม่
        $img_others_array = json_decode($img_others_json, true);
        $img_others_html = '';
        if (!empty($img_others_array)) {
            foreach ($img_others_array as $img_url) {
                $img_others_html .= "<img src=\"../uploads/" . htmlspecialchars($img_url) . "\" alt=\"" . htmlspecialchars($name) . "\" style=\"width:100px; margin-right:10px;\">";
            }
        }

        // ใช้ Nowdoc เพื่อสร้างไฟล์ PHP
        $file_content = <<<HTML
<?php
include('../db.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <title>{$name}</title>
</head>
<body>
    <h1>{$name}</h1>
    <p>{$text_description}</p>
    <div>
        <img src="../uploads/{$img_product}" alt="{$name}">
    </div>
    <div>
        {$img_onTop_html}
    </div>
    <div>
        <h2>ภาพเพิ่มเติม</h2>
        {$img_others_html}
    </div>
    <div>
        <!-- เพิ่มเนื้อหาอื่นๆ ตามต้องการ -->
    </div>
</body>
</html>
HTML;

        // เขียนไฟล์ใหม่
        if (file_put_contents($file_name, $file_content)) {
            echo "บันทึกสำเร็จและสร้างไฟล์ใหม่: $file_name";
        } else {
            echo "บันทึกสำเร็จ แต่สร้างไฟล์ใหม่ไม่สำเร็จ!";
        }
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
