<?php
include('../db.php');

// รับค่าจากฟอร์ม
$abouthead = $_POST['abouthead'];
$abouttitle = $_POST['abouttitle'];


$check_sql = "SELECT * FROM about ";
$result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE about
            SET abouthead = '$abouthead', 
                abouttitle = '$abouttitle'";     
} else {
    $sql = "INSERT INTO about (abouthead, abouttitle)
            VALUES ('$abouthead', '$abouttitle')";
}

if (mysqli_query($conn, $sql)) {
    header("Location: admin_panel.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
    header("Location: admin_panel.php");
    exit();
}
?>
