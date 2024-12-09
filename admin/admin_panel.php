<?php include('../db.php'); ?>
<?php
/*
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} */
?>

<!DOCTYPE html>
<html>

<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css"> <!-- เพิ่มลิงค์ไปยังไฟล์ CSS -->
</head>

<body>
    <h1>จัดการหน้าเมนูหน้าเว็บ</h1>
    <form method="POST" action="add_navbar.php">
        <div class="form-container">
            <div class="form-group">
                <input type="text" name="name" placeholder="ชื่อหัวข้อ" required>
            </div>
            <form action="upload_logo.php" method="post" enctype="multipart/form-data">
                <label for="logo">เลือกโลโก้ใหม่:</label>
                <input type="file" name="logo" id="logo" accept="image/*" required>
                <button type="submit">อัปโหลด</button>
            </div>
            <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>ชื่อหัวข้อ</th>
                <th>ลบ</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM navbar";
                $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // วนลูปแสดงข้อมูล
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td> <a href='delete_navbar.php?del=" . $row["id"] . "'>ลบ</a>" . "</td>";
                    echo "</tr>";
                    
                }
            } else {
                echo "<tr><td colspan='2'>ไม่มีข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>
        </div>
    </form>
    <h1>อัพโหลด Logo</h1>
    <form action="upload_update.php" method="post" enctype="multipart/form-data">
                <label for="logo">เลือกรูปภาพใหม่:</label>
                <input type="file" name="logo" id="logo" accept="image/*">
                <button type="submit">อัปโหลดและอัปเดต</button>
            </form>
        </div>

    </form>

    <h1>จัดการข้อมูลหน้าเว็บ</h1>
    <form method="POST" action="add_content.php" enctype="multipart/form-data">
        <div class="form-container">
            <div class="form-group">
                <input type="text" name="title" placeholder="ชื่อหัวข้อ" required>
            </div>
            <div class="form-group">
                <textarea name="body" placeholder="เนื้อหา" required></textarea>
            </div>
            <div class="form-group">
                <input type="file" name="SEO_image" placeholder="รูปภาพ" />
            </div>
        </div>

        <button type="submit">เพิ่มข้อมูล</button>
    </form>
</body>

</html>