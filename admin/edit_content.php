<?php
$edit = $_GET['edit'];
include("../db.php");
$sql = "SELECT * from content where contentId = '$edit' ";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css"> <!-- เพิ่มลิงค์ไปยังไฟล์ CSS -->
</head>
<body>
    <h1>จัดการข้อมูลหน้าเว็บ</h1>
    <form method="post" action="editing_content.php" enctype="multipart/form-data">
        <div class="form-container">
            <div class="form-group">
                <input type="text" value="<?=$row['title'];?>" name="title"  />
            </div>
            <div class="form-group">
                <textarea name="body"> <?=$row['body'];?></textarea>
            </div>
        </div>
        <button type="submit" >แก้ไขข้อมูล</button>
        <input type="hidden" value='<?=$row['contentId']?>' name="edit" />
        
    </form>
</body>
</html>