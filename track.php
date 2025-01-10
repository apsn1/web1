<?php
include('db.php');
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลที่ส่งมา
$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['userId'];
$visitedLink = $data['visitedLink'];
$timestamp = $data['timestamp'];

// บันทึกข้อมูลลงฐานข้อมูล
$sql = "INSERT INTO user_visits (userId, visitedLink, timestamp) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $userId, $visitedLink, $timestamp);

if ($stmt->execute()) {
    echo json_encode(["message" => "Data saved successfully"]);
} else {
    echo json_encode(["message" => "Error saving data"]);
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>
