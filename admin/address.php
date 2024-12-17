<?php
include('../db.php');

// รับค่าจากฟอร์ม
$homeNumber = $_POST['homeNumber'];
$street = $_POST['street'];
$subDistrict = $_POST['subDistrict'];
$district = $_POST['district'];
$province = $_POST['province'];
$postalCode = $_POST['postalCode'];

$check_sql = "SELECT * FROM address WHERE addressID = 1";
$result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE address 
            SET homeNumber = '$homeNumber', 
                street = '$street', 
                subDistrict = '$subDistrict', 
                district = '$district', 
                province = '$province', 
                postalCode = '$postalCode'
            WHERE addressID = 1";
} else {
    $sql = "INSERT INTO address (homeNumber, street, subDistrict, district, province, postalCode)
            VALUES ('$homeNumber', '$street', '$subDistrict', '$district', '$province', '$postalCode')";
}

if (mysqli_query($conn, $sql)) {
    header("Location: admin_panel.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
    header("Location: add_address.php");
    exit();
}
?>
