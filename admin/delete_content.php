<?php

$del = $_GET['del'];
include("../db.php");

$sql = "delete from content where contentID = '$del' ";
$result = mysqli_query($conn,$sql);

if($result){
    echo "Delete successful!!";
   
}else{
    echo "Can't delete this card please try again!!";
}


?>