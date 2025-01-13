<?php
require_once '../db.php';
session_start();

// ตรวจสอบว่ามี ID ที่ส่งมาหรือไม่
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ไม่พบ ID บทความ";
    header("Location: manage_articles.php");
    exit();
}

$article_id = $_GET['id'];

// ดึงข้อมูลบทความ
$sql = "SELECT * FROM article WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $article_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$article = mysqli_fetch_assoc($result);

if (!$article) {
    $_SESSION['error'] = "ไม่พบบทความ";
    header("Location: manage_articles.php");
    exit();
}

// เมื่อมีการส่งฟอร์มแก้ไข
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // อัพเดทข้อมูลบทความ
    $sql = "UPDATE article SET title = ?, content = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $article_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // จัดการกับรูปภาพใหม่ (ถ้ามี)
        if ($_FILES['image']['error'] === 0) {
            $upload_dir = "../Allpage/จัดการหน้าเว็บ/images_all/";
            
            // ลบรูปเก่า (ถ้ามี)
            if (!empty($article['image_path'])) {
                $old_image_path = $upload_dir . $article['image_path'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            
            // อัพโหลดรูปใหม่
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE article SET image_path = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "si", $new_filename, $article_id);
                mysqli_stmt_execute($stmt);
            }
        }
        
        $_SESSION['success'] = "แก้ไขบทความเรียบร้อยแล้ว";
        header("Location: manage_articles.php");
        exit();
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการแก้ไขข้อมูล";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขบทความ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>แก้ไขบทความ</h1>
        
        <form method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="title" class="form-label">หัวข้อ</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">เนื้อหา</label>
                <textarea class="form-control" id="content" name="content" 
                          rows="10" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">รูปภาพ</label>
                <?php if ($article['image_path']): ?>
                    <div class="mb-2">
                        <img src="../Allpage/จัดการหน้าเว็บ/images_all/<?php echo htmlspecialchars($article['image_path']); ?>" 
                             alt="Current image" style="max-width: 200px;">
                        <p class="text-muted">รูปภาพปัจจุบัน</p>
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <small class="text-muted">อัพโหลดรูปใหม่เฉพาะเมื่อต้องการเปลี่ยน</small>
            </div>
            
            <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
            <a href="manage_articles.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
