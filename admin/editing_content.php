<?php 
$edit=$_POST['edit'];
$title = $_POST['title'];
$body = $_POST['body'];
include('../db.php');

$sql = "update content set title='$title',body='$body' where contentId = '$edit' ";
$result = mysqli_query($conn,$sql);
    
    if($result){
        echo"แก้ไขสำเสร็จ";
        header("Location: admin_panel.php");
    }
    else{
        die(mysqli_error($conn));
        
    }

?>