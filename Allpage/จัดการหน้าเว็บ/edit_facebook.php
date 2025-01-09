<?php
// เชื่อมต่อฐานข้อมูล
include('../../db.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบและเพิ่มการ์ดใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['platform_name']));
    $link = $conn->real_escape_string(trim($_POST['platform_link']));

    if (!empty($name) && !empty($link)) {
        $sql = "INSERT INTO card_facebook (platform_name, platform_link) VALUES ('$name', '$link')";
        if ($conn->query($sql) === TRUE) {
            echo "เพิ่มการ์ดใหม่สำเร็จ!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    }
}
?>
