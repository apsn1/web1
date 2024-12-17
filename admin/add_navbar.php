<?php
include('../db.php');  // รวมไฟล์การเชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);  // ป้องกัน SQL Injection
    $is_dropdown = isset($_POST['is_dropdown']) ? 1 : 0;  // เช็คว่าเป็น dropdown หรือไม่
    $dropdown_names = isset($_POST['dropdown_name']) ? $_POST['dropdown_name'] : [];  // เมนูย่อย
    $link_to = isset($_POST['link_to']) ? $_POST['link_to'] : '';  // รับชื่อไฟล์จากฟอร์ม

    // เพิ่มเมนูหลัก
    $parent_id = ($is_dropdown) ? 'NULL' : 'NULL';  // ใช้ NULL แทน 0 สำหรับเมนูหลัก
    $sql = "INSERT INTO navbar (name, is_dropdown, parent_id, link_to) VALUES ('$name', '$is_dropdown', $parent_id, '$link_to')";

    if ($conn->query($sql)) {
        $parent_id = $conn->insert_id;  // รับ id ของเมนูหลักที่เพิ่มใหม่

        // เพิ่มเมนูย่อย ถ้ามี
        if ($is_dropdown && !empty($dropdown_names)) {
            foreach ($dropdown_names as $dropdown_name) {
                $dropdown_name = $conn->real_escape_string($dropdown_name);
                $sql = "INSERT INTO navbar (name, is_dropdown, parent_id, link_to) VALUES ('$dropdown_name', 0, '$parent_id', '$link_to')";
                if (!$conn->query($sql)) {
                    echo "เกิดข้อผิดพลาดในการเพิ่มเมนูย่อย: " . $conn->error;
                    exit;
                }
            }
        }

        echo "เพิ่มเมนูสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>
