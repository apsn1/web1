<?php
include('../db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];

    $sql = "INSERT INTO content (title, body) VALUES ('$title', '$body')";
    if ($conn->query($sql) === TRUE) {
        echo "เพิ่มโพสสำเร็จ";
        echo "<meta http-equiv='refresh' content='2;url=../index.php'/>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>
    $sql = "INSERT INTO content (title, body) VALUES ('$title', '$body')";
                if ($conn->query($sql) === TRUE) {
                    echo "เพิ่มโพสสำเร็จ";
                    echo "<meta http-equiv='refresh' content='2;url=../index.php'/>";
                } else {
                    echo "เกิดข้อผิดพลาด: " . $conn->error;
                }
            } else {
                echo "อัพโหลดไฟล์ไม่สำเร็จ";
            }

?>
