<?php
$edit = $_POST['edit'];

// ตรวจสอบการอัพโหลดไฟล์ header
if (isset($_FILES['header'])) {
    $imgTmpName = $_FILES['header']['tmp_name'];
    $imgName = $_FILES['header']['name'];
    $button = $_POST['button'];

    // กำหนดเส้นทางที่ต้องการเก็บไฟล์
    $uploadDirHeader = '../admin/img/header/';
    $uploadFileHeader = $uploadDirHeader . basename($imgName);

    include('../db.php');

    // ดึงชื่อไฟล์เก่าจากฐานข้อมูล
    $query = "SELECT img, img_vertical FROM header_images WHERE headerID = '$edit'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $oldImageHeader = $uploadDirHeader . $row['img']; // เส้นทางไฟล์รูป header เก่า

    // ตรวจสอบและจัดการอัพโหลดไฟล์ header
    if (move_uploaded_file($imgTmpName, $uploadFileHeader)) {
        // ลบไฟล์ header เก่า
        if (file_exists($oldImageHeader)) {
            unlink($oldImageHeader);
        }
    } else {
        echo "การอัพโหลดไฟล์ Header ล้มเหลว!";
        exit;
    }
}

// ตรวจสอบการอัพโหลดไฟล์ vertical
if (isset($_FILES['image_vertical'])) {
    $imgVerticalTmpName = $_FILES['image_vertical']['tmp_name'];
    $imgVerticalName = $_FILES['image_vertical']['name'];

    // กำหนดเส้นทางที่ต้องการเก็บไฟล์
    $uploadDirVertical = '../admin/img/vertical/';
    $uploadFileVertical = $uploadDirVertical . basename($imgVerticalName);

    // ตรวจสอบและจัดการอัพโหลดไฟล์ vertical
    if (move_uploaded_file($imgVerticalTmpName, $uploadFileVertical)) {
        // ลบไฟล์ vertical เก่า
        $oldImageVertical = $uploadDirVertical . $row['img_vertical'];
        if (file_exists($oldImageVertical)) {
            unlink($oldImageVertical);
        }
    } else {
        echo "การอัพโหลดไฟล์ Vertical ล้มเหลว!";
        exit;
    }
}

// อัพเดตฐานข้อมูลด้วยชื่อไฟล์ใหม่
$sql = "UPDATE header_images SET img='$imgName', img_vertical='$imgVerticalName', button='$button' WHERE headerID = '$edit'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "แก้ไขสำเร็จ";
    header("Location: admin_panel.php?success=1");
} else {
    die(mysqli_error($conn)); // ถ้ามีข้อผิดพลาดในการอัพเดต
}
?>