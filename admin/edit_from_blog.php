<?php
include('../db.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลบทความจากฐานข้อมูล
    $sql = "SELECT * FROM blogs WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            ?>
            <form action="update_blog.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="title">หัวข้อ:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required><br>

                <label for="description">คำอธิบาย:</label>
                <textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea><br>

                <label for="images">อัพโหลดรูปภาพ:</label>
                <input type="file" name="images[]" multiple><br>

                <button type="submit">บันทึกการแก้ไข</button>
            </form>
            <?php
        } else {
            echo "ไม่พบข้อมูลบทความที่ต้องการแก้ไข";
        }

        $stmt->close();
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการแก้ไข";
}
?>
