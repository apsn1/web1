<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // กรองข้อมูลที่รับมา
    $type = htmlspecialchars(trim($_POST['type']));
    $value = htmlspecialchars(trim($_POST['value']));

    // ตรวจสอบว่ามีข้อมูลประเภทนี้อยู่ในฐานข้อมูลแล้วหรือไม่
    $stmt_check = $conn->prepare("SELECT id FROM minicontacts WHERE type = ?");
    $stmt_check->bind_param("s", $type);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // มีข้อมูลอยู่แล้ว ทำการอัปเดต
        $row = $result->fetch_assoc();
        $id = $row['id'];

        $stmt_update = $conn->prepare("UPDATE minicontacts SET value = ? WHERE id = ?");
        $stmt_update->bind_param("si", $value, $id);

        if ($stmt_update->execute()) {
            header("Location: ../index.php"); // กลับไปที่หน้าหลัก
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการอัปเดต: " . $stmt_update->error;
        }

        $stmt_update->close();
    } else {
        // ไม่มีข้อมูล ทำการเพิ่มใหม่
        $stmt_insert = $conn->prepare("INSERT INTO minicontacts (type, value) VALUES (?, ?)");
        $stmt_insert->bind_param("ss", $type, $value);

        if ($stmt_insert->execute()) {
            header("Location: ../index.php"); // กลับไปที่หน้าหลัก
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูล: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }

    // ปิด statement
    $stmt_check->close();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
