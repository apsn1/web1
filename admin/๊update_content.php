<?php
include('../db.php');

$title = $_POST['title'];
$body = $_POST['body'];


// อัปเดตข้อมูล
$sql = "UPDATE content SET title='$title', body=$body WHERE id=$contentID";
if ($conn->query($sql) === TRUE) {
    echo "อัปเดตข้อมูลสำเร็จ!";
    header("Location: ../index.php"); // กลับไปหน้าแสดงข้อมูล
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}
?>