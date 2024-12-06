<?php
include('../db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['name'];
    $body = $_POST['body'];

    $sql = "INSERT INTO navbar (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        echo "เพิ่มโพสสำเร็จ";
        echo "<meta http-equiv='refresh' content='2;url=../index.php'/>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>
