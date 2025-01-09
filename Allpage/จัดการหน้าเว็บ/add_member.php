<?php
include('../db.php');

if(isset($_POST['submit'])) {
    // รับค่าจากฟอร์ม
    $member_name = mysqli_real_escape_string($conn, $_POST['member_name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);

    // ตรวจสอบไฟล์รูปภาพ
    if(isset($_FILES['member_image'])) {
        $file = $_FILES['member_image'];
        
        // ตรวจสอบ error
        if($file['error'] === 0) {
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // ตรวจสอบนามสกุลไฟล์
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            
            if(in_array($file_ext, $allowed)) {
                // สร้างชื่อไฟล์ใหม่
                $new_file_name = uniqid('member_') . '.' . $file_ext;
                $upload_path = 'img/members/' . $new_file_name;
                
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                if (!file_exists('img/members/')) {
                    mkdir('img/members/', 0777, true);
                }
                
                // อัพโหลดไฟล์
                if(move_uploaded_file($file_tmp, $upload_path)) {
                    // เพิ่มข้อมูลลงในฐานข้อมูล
                    $sql = "INSERT INTO members (member_name, position, member_image) 
                            VALUES (?, ?, ?)";
                    
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "sss", $member_name, $position, $new_file_name);
                    
                    if(mysqli_stmt_execute($stmt)) {
                        echo "<script>
                                alert('เพิ่มสมาชิกสำเร็จ');
                                window.location.href = 'admin_panel.php';
                              </script>";
                    } else {
                        echo "<script>
                                alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                                window.history.back();
                              </script>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<script>
                            alert('เกิดข้อผิดพลาดในการอัพโหลดไฟล์');
                            window.history.back();
                          </script>";
                }
            } else {
                echo "<script>
                        alert('กรุณาอัพโหลดไฟล์รูปภาพเท่านั้น (jpg, jpeg, png, gif)');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('เกิดข้อผิดพลาดกับไฟล์รูปภาพ');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('กรุณาเลือกรูปภาพ');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            window.history.back();
          </script>";
}

mysqli_close($conn);
