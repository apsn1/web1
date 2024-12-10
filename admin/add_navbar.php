<?php
include('../db.php');  // รวมไฟล์การเชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];  // ชื่อเมนูหลัก
    $is_dropdown = isset($_POST['is_dropdown']) ? 1 : 0;  // เช็คว่าเป็น dropdown หรือไม่
    $dropdown_names = isset($_POST['dropdown_name']) ? $_POST['dropdown_name'] : [];  // เมนูย่อย

    // หากเป็นเมนูหลักที่ไม่เป็น dropdown
    if ($is_dropdown) {
        $sql = "INSERT INTO navbar (name, is_dropdown, parent_id) VALUES ('$name', 1, NULL)";
    } else {
        $sql = "INSERT INTO navbar (name, is_dropdown, parent_id) VALUES ('$name', 0, 0)";
    }

    if ($conn->query($sql)) {
        $parent_id = $conn->insert_id;  // รับ id ของเมนูหลักที่เพิ่มใหม่

        // เพิ่มเมนูย่อย ถ้ามี
        foreach ($dropdown_names as $dropdown_name) {
            $sql = "INSERT INTO navbar (name, is_dropdown, parent_id) VALUES ('$dropdown_name', 0, '$parent_id')";
            $conn->query($sql);
        }

        echo "เพิ่มเมนูสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>
