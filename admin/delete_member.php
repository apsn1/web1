<?php
include('../db.php');

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "SELECT member_image FROM members WHERE memberID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        $image_path = '../Allpage/จัดการหน้าเว็บ/images_all/' . $row['member_image'];
        
        // ลบข้อมูลจากฐานข้อมูล
        $delete_sql = "DELETE FROM members WHERE memberID = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        
        if(mysqli_stmt_execute($delete_stmt)) {
            // ลบไฟล์รูปภาพ
            if(file_exists($image_path)) {
                unlink($image_path);
            }
            
            echo "<script>
                    window.location.href = 'edit_member.php';
                  </script>";
        } else {
            echo "<script>
                    alert('เกิดข้อผิดพลาดในการลบข้อมูล');
                    window.history.back();
                  </script>";
        }
        
        mysqli_stmt_close($delete_stmt);
    } else {
        echo "<script>
                alert('ไม่พบข้อมูลที่ต้องการลบ');
                window.location.href = 'edit_member.php';
              </script>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "<script>
            alert('ไม่พบ ID ที่ต้องการลบ');
            window.location.href = 'edit_member.php';
          </script>";
}

mysqli_close($conn);
?>
