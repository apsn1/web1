<?php
// edit_upload_design.php
include('../db.php');// ที่ใช้ MySQLi

if(isset($_POST['submit'])) {
    if(isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        
        $targetDir = "uploaddesign/";
        $fileName  = time() . "_" . basename($_FILES["image_file"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        $allowedTypes = array('jpg','jpeg','png','gif','webp');
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if(in_array($fileType, $allowedTypes)) {
            if(move_uploaded_file($_FILES["image_file"]["tmp_name"], $targetFilePath)){
                
                // Prepare SQL แบบ MySQLi
                $stmt = $conn->prepare("INSERT INTO imgdesign (image_path) VALUES (?)");
                if($stmt === false){
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                    exit();
                }

                // bind_param("s", string_variable)
                $stmt->bind_param("s", $targetFilePath);
                
                if(!$stmt->execute()){
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    echo "อัปโหลดสำเร็จ!<br>";
                    header("Location: admin_panel.php");
                }

                $stmt->close();

            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
            }
        } else {
            echo "ชนิดไฟล์ไม่ถูกต้อง (รองรับไฟล์: ". implode(", ", $allowedTypes) . ")";
        }

    } else {
        echo "ไม่พบไฟล์หรือมีข้อผิดพลาด";
    }
} else {
    echo "ไม่มีการส่งข้อมูลรูปภาพ";
}
?>
