<?php
$edit = $_POST['edit'];
// ตรวจสอบการอัพโหลดไฟล์
if(isset($_FILES['header'])) {
    $imgTmpName = $_FILES['header']['tmp_name']; // ไฟล์ที่อัพโหลดชั่วคราว
    $imgName = $_FILES['header']['name']; // ชื่อไฟล์ต้นฉบับ
    $imgSize = $_FILES['header']['size']; // ขนาดไฟล์

    // กำหนดเส้นทางที่ต้องการเก็บไฟล์
    $uploadDir = '../admin/img/header/';
    $uploadFile = $uploadDir . basename($imgName);

        // ย้ายไฟล์จากที่ชั่วคราวไปที่ตำแหน่งที่กำหนด
        if (move_uploaded_file($imgTmpName, $uploadFile)) {
            include('../db.php');

            // อัพเดตฐานข้อมูลด้วยชื่อไฟล์ใหม่
            $sql = "UPDATE header_images SET img='$imgName' WHERE headerID = '$edit'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "แก้ไขสำเร็จ";
                echo "<meta http-equiv='refresh' content='2;url=../index.php'/>";
            } else {
                die(mysqli_error($conn)); // ถ้ามีข้อผิดพลาดในการอัพเดต
            }
        } else {
            echo "การอัพโหลดไฟล์ล้มเหลว!";
        }
    } else {
        echo "ไม่ได้เลือกไฟล์หรือเกิดข้อผิดพลาดในการอัพโหลด!";
    }
?>
