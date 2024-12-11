<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aboutID = intval($_POST['aboutID']); // แปลงเป็น integer เพื่อป้องกันการโจมตี
    $onesiamText = $conn->real_escape_string(trim($_POST['onesiamText']));
    $aboutText = $conn->real_escape_string(trim($_POST['aboutText']));

    if ($aboutID > 0) { // ตรวจสอบว่า aboutID ถูกต้อง
        $sql = "UPDATE about SET onesiamText = '$onesiamText', aboutText = '$aboutText' WHERE aboutID = '$aboutID'";
        
        if ($conn->query($sql)) {
            echo "อัปเดตข้อมูลสำเร็จ!";
            header("Location: ../index.php"); // กลับไปหน้า index.php
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
    } else {
        echo "ID ไม่ถูกต้อง";
    }
}

?>