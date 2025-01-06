<?php

include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, trim($_POST['phone'])) : '';
    $lineID = isset($_POST['lineID']) ? mysqli_real_escape_string($conn, trim($_POST['lineID'])) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';

    $sql = "SELECT * FROM contacts LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        $sql = "UPDATE contacts SET phone = '$phone', line = '$lineID', email = '$email'";
    } else {
        $sql = "INSERT INTO contacts (phone, line, email) VALUES ('$phone', '$lineID', '$email')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_panel.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
        header("Location: add_address.php");
        exit();
    }
}
?>
