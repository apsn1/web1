<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่ามีข้อมูลที่ส่งมาจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $b1 = $_POST['bb1'];
    $b2 = $_POST['bb2'];
    $b3 = $_POST['bb3'];
    $b4 = $_POST['bb4'];
    $b5 = $_POST['bb5'];
    $b6 = $_POST['bb6'];
    $b7 = $_POST['bb7'];

    // เตรียมคำสั่ง SQL เพื่อบันทึกข้อมูล
    $sql = "INSERT INTO textabout (b1, b2, b3, b4, b5, b6, b7)
            VALUES ('$b1', '$b2', '$b3', '$b4', '$b5', '$b6', '$b7')";

    // ตรวจสอบว่าเพิ่มข้อมูลสำเร็จหรือไม่
    if ($conn->query($sql) === TRUE) {
        echo "<h2>บันทึกข้อมูลสำเร็จ!</h2>";
        echo "<p>" . htmlspecialchars($b1) . "</p>";
        echo "<p>" . htmlspecialchars($b2) . "</p>";
        echo "<p>" . htmlspecialchars($b3) . "</p>";
        echo "<p>" . htmlspecialchars($b4) . "</p>";
        echo "<p>" . htmlspecialchars($b5) . "</p>";
        echo "<p>" . htmlspecialchars($b6) . "</p>";
        echo "<p>" . htmlspecialchars($b7) . "</p>";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn->close();
}
?>
