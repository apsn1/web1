<?php include('db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>หน้าเว็บหลัก</title>
</head>
<body>
    <h1>ข้อมูลในหน้าเว็บ</h1>
    <a href="admin/admin_panel.php">สร้างโพส</a>
    <?php
    $sql = "SELECT * FROM content";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<a href='admin/edit_content.php'> edit </a>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<p>" . $row['body'] . "</p>";
        }
    } else {
        echo "ไม่มีข้อมูล";
    }
    ?>
</body>
</html>