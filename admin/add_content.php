<?php
include('../db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];

    $sql = "INSERT INTO content (title, body) VALUES ('$title', '$body')";
                if ($conn->query($sql) === TRUE) {
                    echo "เพิ่มข้อความสำเร็จ";
                    header("Location: admin_panel.php");
                } else {
                    echo "เกิดข้อผิดพลาด: " . $conn->error;
                }
             
}else {
    echo "อัพโหลดไฟล์ไม่สำเร็จ";
}


    

?>
