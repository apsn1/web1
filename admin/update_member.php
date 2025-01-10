<?php
include('../db.php');

if(isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $member_name = mysqli_real_escape_string($conn, $_POST['member_name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    
    // เริ่มต้นด้วยการดึงข้อมูลรูปภาพเดิม
    $sql = "SELECT member_image FROM members WHERE memberID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $old_image = $row['member_image'];
    
    // ตรวจสอบว่ามีการอัพโหลดรูปภาพใหม่หรือไม่
    if(!empty($_FILES['member_image']['name'])) {
        $file = $_FILES['member_image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // ตรวจสอบนามสกุลไฟล์
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if(in_array($file_ext, $allowed)) {
            // สร้างชื่อไฟล์ใหม่
            $new_image = uniqid('member_') . '.' . $file_ext;
            $upload_path = '../Allpage/จัดการหน้าเว็บ/images_all/' . $new_image;
            
            // อัพโหลดไฟล์ใหม่
            if(move_uploaded_file($file_tmp, $upload_path)) {
                // ลบรูปเก่า (ถ้ามี)
                if($old_image && file_exists('../Allpage/จัดการหน้าเว็บ/images_all/' . $old_image)) {
                    unlink('../Allpage/จัดการหน้าเว็บ/images_all/' . $old_image);
                }
                
                // อัพเดทข้อมูลพร้อมรูปภาพใหม่
                $sql = "UPDATE members SET member_name = ?, position = ?, member_image = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssi", $member_name, $position, $new_image, $id);
            } else {
                echo "<script>
                        alert('เกิดข้อผิดพลาดในการอัพโหลดไฟล์');
                        window.history.back();
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('กรุณาอัพโหลดไฟล์รูปภาพเท่านั้น (jpg, jpeg, png, gif)');
                    window.history.back();
                  </script>";
            exit();
        }
    } else {
        // อัพเดทข้อมูลโดยไม่เปลี่ยนรูปภาพ
        $sql = "UPDATE members SET member_name = ?, position = ? WHERE memberID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $member_name, $position, $id);
    }
    
    // ดำเนินการอัพเดท
    if(mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('อัพเดทข้อมูลสำเร็จ');
                window.location.href = 'edit_member.php';
              </script>";
    } else {
        echo "<script>
                alert('เกิดข้อผิดพลาดในการอัพเดทข้อมูล');
                window.history.back();
              </script>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            window.history.back();
          </script>";
}

mysqli_close($conn);
?>
