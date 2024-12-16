<?php
include('../db.php');

$homeNumber = $_POST['homeNumber'];
$street = $_POST['street'];
$subDistrict = $_POST['subDistrict'];
$district = $_POST['district'];
$province = $_POST['province'];
$postalCode = $_POST['postalCode'];

$sql = "INSERT INTO address (homeNumber, street, subDistrict, district, province, postalCode)
        VALUES ('$homeNumber', '$street', '$subDistrict', '$district', '$province', '$postalCode')";


if (mysqli_query($conn, $sql)) {
    header("Location: admin_panel.php");;
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
    header("Location: add_address.php");;
}
?>
