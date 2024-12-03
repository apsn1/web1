<?php
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = ($_POST['username']);
    $password = ($_POST['password']);
    $password = md5($password);

    // คำสั่ง SQL
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "email Already Exsis !"   ;  
    }
    else{
        $insertQuery="INSERT INTO users (email,username,password) VALUE ('$email','$username','$password')";
        if($conn->query($insertQuery)==TRUE){
            echo "สมัครสำเร็จ";
            echo "<meta http-equiv='refresh' content='3;url=login.php'/>";
            header("location login.php");
        }
        else{
            echo "Error.".$conn->error;
        }
    }
}
?>
