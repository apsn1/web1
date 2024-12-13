<?php
    include('../db.php');
    $file_name = $_FILES['header']['name'];
    $tempname = $_FILES['header']['tmp_name'];
    $folder = 'admin/img/header/'.$file_name;
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/web/admin/img/header/";
    $target_file = $target_dir . basename($_FILES["header"]["name"]);
    //$file_name = time().$file_name;
    $sql_count = "SELECT COUNT(*) AS total FROM header_images";
    $result = mysqli_query($conn, $sql_count);
    $row = mysqli_fetch_assoc($result);
    if( $row['total'] >= 4){
        echo "ไม่สามารถเพิ่มรูปได้ เนื่องจากมีรูปแบนเนอร์ครบ 4 รูปแล้ว";
        return;
    }else{
        $sql = "INSERT INTO header_images (img) VALUES ('$file_name')";

        if (is_uploaded_file($_FILES['header']['tmp_name'])) {
            if (move_uploaded_file($_FILES["header"]["tmp_name"], $target_file)) {
                
                if ($conn->query($sql) === TRUE) {
                    echo "เพิ่มรูปสำเร็จ";
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
