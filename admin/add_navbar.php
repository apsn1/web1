<?php
include('../db.php');  // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $parent_id = $_POST['parent_id'];  // เลข id เมนูหลัก (1,2,3,4)
    $sub_name  = $_POST['sub_name'];   // ชื่อเมนูย่อย
    $link_to   = $_POST['link_to'];    // ไฟล์ที่จะลิงค์

    // สั่ง INSERT ลงตาราง navbar
    // โดยไม่สนว่า $parent_id มี row จริงในตารางหรือไม่
    // เพราะไม่ได้ใช้ Foreign Key Constraint
    $sqlInsert = "INSERT INTO navbar (name, parent_id, link_to)
                  VALUES ('$sub_name', '$parent_id', '$link_to')";

    if ($conn->query($sqlInsert) === TRUE) {
        echo "เพิ่มเมนูย่อยเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    // Redirect กลับไปหน้าหลัก
    header("Location: index_navbar.php");
    exit;
}
?>
