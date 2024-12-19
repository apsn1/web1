<?php
$edit = $_POST['edit'];
// ตรวจสอบการอัพโหลดไฟล์
if(isset($_FILES['header'])) {
    $imgTmpName = $_FILES['header']['tmp_name']; // ไฟล์ที่อัพโหลดชั่วคราว
    $imgName = $_FILES['header']['name']; // ชื่อไฟล์ต้นฉบับ
    $imgSize = $_FILES['header']['size']; // ขนาดไฟล์
    $button = $_POST['button'];

    // กำหนดเส้นทางที่ต้องการเก็บไฟล์
    $uploadDir = '../admin/img/header/';
    $uploadFile = $uploadDir . basename($imgName);

    include('../db.php');

    // ดึงชื่อไฟล์เก่าจากฐานข้อมูล
    $query = "SELECT img FROM header_images WHERE headerID = '$edit'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $oldImage = $uploadDir . $row['img']; // เส้นทางไฟล์รูปเก่า

    // ย้ายไฟล์จากที่ชั่วคราวไปที่ตำแหน่งที่กำหนด
    if (move_uploaded_file($imgTmpName, $uploadFile)) {
        // ลบไฟล์รูปภาพเก่า
        if (file_exists($oldImage)) {
            unlink($oldImage); // ลบไฟล์รูปภาพเก่า
        }

        // อัพเดตฐานข้อมูลด้วยชื่อไฟล์ใหม่
        $sql = "UPDATE header_images SET img='$imgName', button='$button' WHERE headerID = '$edit'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "แก้ไขสำเร็จ";
            header("Location: admin_panel.php");
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
