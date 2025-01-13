<?php
require_once '../db.php';
session_start();

// ลบบทความ
if (isset($_POST['delete'])) {
    $article_id = $_POST['article_id'];
    
    // ดึงข้อมูลรูปภาพก่อนลบ
    $sql = "SELECT image_path FROM article WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $article_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $article = mysqli_fetch_assoc($result);
    
    // ลบรูปภาพ
    if ($article['image_path']) {
        $image_path = "uploads/" . $article['image_path'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // ลบข้อมูลจากฐานข้อมูล
    $sql = "DELETE FROM article WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $article_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "ลบบทความเรียบร้อยแล้ว";
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบบทความ";
    }
    
    header("Location: manage_articles.php");
    exit();
}

// ดึงข้อมูลบทความทั้งหมด
$sql = "SELECT * FROM article ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการบทความ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>จัดการบทความ</h1>
            <a href="create_article.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> เพิ่มบทความใหม่
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>รูปภาพ</th>
                        <th>หัวข้อ</th>
                        <th>เนื้อหา</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($article = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td>
                                <?php if ($article['image_path']): ?>
                                    <img src="../Allpage/จัดการหน้าเว็บ/images_all/<?php echo htmlspecialchars($article['image_path']); ?>" 
                                         alt="Article image" style="max-width: 100px; ">
                                <?php else: ?>
                                    <span class="text-muted">ไม่มีรูปภาพ</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($article['title']); ?></td>
                            <td><?php echo mb_substr(strip_tags($article['content']), 0, 100, 'UTF-8') . '...'; ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="edit_article.php?id=<?php echo $article['id']; ?>" 
                                       class="btn btn-success btn-sm mx-1">
                                        <i class="bi bi-pencil"></i> แก้ไข
                                    </a>
                                    <form method="POST" class="d-inline" 
                                          onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบบทความนี้?');">
                                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm mx-1">
                                            <i class="bi bi-trash"></i> ลบ
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
