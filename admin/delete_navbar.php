<?php

$del = $_GET['del'];
include("../db.php");

$sql = "delete from navbar where id = '$del' ";
$result = mysqli_query($conn,$sql);

if($result){
    echo "Delete successful!!";
    echo "<meta http-equiv='refresh' navbar='2;url=../index.php'/>";
   
}else{
    echo "Can't delete this card please try again!!";
}


?>