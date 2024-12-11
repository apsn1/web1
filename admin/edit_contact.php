<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST['type'];
    $value = $_POST['value'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // ตรวจสอบว่า id มีค่าหรือไม่
        $id = $_POST['id'];
        // อัปเดตข้อมูล
        $sql = "UPDATE minicontacts SET type='$type', value='$value' WHERE id=$id";
    } else {
        // เพิ่มข้อมูลใหม่
        $sql = "INSERT INTO minicontacts (type, value) VALUES ('$type', '$value')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: ../index.php"); // กลับไปที่หน้าหลัก
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>
