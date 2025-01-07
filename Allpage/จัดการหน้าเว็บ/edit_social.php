<?php
// เชื่อมต่อฐานข้อมูล
include('../../db.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าตารางมีอยู่หรือไม่
$sqlCheck = "SHOW TABLES LIKE 'cards'";
$resultCheck = $conn->query($sqlCheck);

if ($resultCheck->num_rows == 0) {
    // สร้างตารางหากไม่มี
    $createTable = "CREATE TABLE cards (
        id INT AUTO_INCREMENT PRIMARY KEY,
        platform_name VARCHAR(255) NOT NULL,
        platform_logo ENUM('YouTube', 'TikTok', 'Facebook') NOT NULL,
        platform_link VARCHAR(255) NOT NULL
    )";
    if ($conn->query($createTable) === TRUE) {
        echo "สร้างตาราง 'cards' สำเร็จ!";
    } else {
        die("Error creating table: " . $conn->error);
    }
}

// ตรวจสอบและเพิ่มการ์ดใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['platform_name']));
    $logo = $conn->real_escape_string(trim($_POST['platform_logo']));
    $link = $conn->real_escape_string(trim($_POST['platform_link']));

    if (!empty($name) && !empty($logo) && !empty($link)) {
        $sql = "INSERT INTO cards (platform_name, platform_logo, platform_link) VALUES ('$name', '$logo', '$link')";
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
