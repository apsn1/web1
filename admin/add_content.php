<?php
include('../db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $file_name = $_FILES['SEO_image']['name'];
    $tempname = $_FILES['SEO_image']['tmp_name'];
    $folder = 'admin/img/SEO_image/'.$file_name;
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/web1/admin/img/SEO_image/";
    $target_file = $target_dir . basename($_FILES["SEO_image"]["name"]);

    //$file_name = time().$file_name;


    $sql = "INSERT INTO content (title, body, img) VALUES ('$title', '$body','$file_name')";
    
        
        if (is_uploaded_file($_FILES['SEO_image']['tmp_name'])) {
            if (move_uploaded_file($_FILES["SEO_image"]["tmp_name"], $target_file)) {
                
                if ($conn->query($sql) === TRUE) {
                    echo "เพิ่มโพสสำเร็จ";
                    echo "<meta http-equiv='refresh' content='2;url=../index.php'/>";
                } else {
                    echo "เกิดข้อผิดพลาด: " . $conn->error;
                }
            } else {
                echo "อัพโหลดไฟล์ไม่สำเร็จ";
            }
        } else {
            echo "ไม่พบไฟล์";
        }
        

    
}
?>
