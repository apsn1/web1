<?php 
$edit=$_POST['edit'];
$title = $_POST['title'];
$body = $_POST['body'];
include('../db.php');

$sql = "update content set title='$title',body='$body' where contentId = '$edit' ";
$result = mysqli_query($conn,$sql);
    
    if($result){
        echo"แก้ไขสำเสร็จ";
        echo "<meta http-equiv='refresh' content='2;url=../index.php'/>";
    }
    else{
        die(mysqli_error($conn));
        
    }

?>