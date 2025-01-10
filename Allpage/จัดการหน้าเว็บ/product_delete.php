<?php
include('../../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete_ids = $_POST['delete_ids'] ?? [];

    if (!empty($delete_ids)) {
        foreach ($delete_ids as $id) {
            // ลบข้อมูลจากฐานข้อมูล
            $sql = "SELECT src_image, src_image_cover FROM product WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // ลบไฟล์ในระบบ
            if (!empty($row['src_image'])) {
                $images = json_decode($row['src_image'], true);
                foreach ($images as $image) {
                    if (file_exists($image)) {
                        unlink($image);
                    }
                }
            }
            if (!empty($row['src_image_cover']) && file_exists($row['src_image_cover'])) {
                unlink($row['src_image_cover']);
            }

            // ลบข้อมูลจากฐานข้อมูล
            $sql = "DELETE FROM product WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }
        echo "Selected products deleted successfully!";
    } else {
        echo "No products selected for deletion.";
    }
}
?>
