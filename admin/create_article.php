<?php
// เพิ่ม connection database
require_once '../db.php';

// ตรวจสอบการ submit form
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // จัดการกับรูปภาพ
    $target_dir = "../Allpage/จัดการหน้าเว็บ/images_article/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $image = $_FILES['article_image'];
    $image_name = time() . '_' . basename($image['name']);
    $target_file = $target_dir . $image_name;
    
    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        // เพิ่มข้อมูลลงในฐานข้อมูล
        $sql = "INSERT INTO article (title, content, image_path, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$title, $content,  $image_name])) {
            // สร้างไฟล์ PHP ใหม่ตามชื่อ title
            $article_id = $conn->insert_id; // รับ ID ของบทความที่เพิ่งสร้าง
            $safe_title = preg_replace('/[^ก-์เa-zA-Z0-9]/', '_', $title); // แทนที่อักขระพิเศษด้วย _
            $file_name = "../Allpage/article_{$article_id}_{$safe_title}.php";
            
            // สร้างเนื้อหาของไฟล์
            $file_content = "<?php
require_once '../db.php';
\$sql = \"SELECT * FROM article WHERE id = {$article_id}\";
\$result = mysqli_query(\$conn, \$sql);
\$row = mysqli_fetch_assoc(\$result);
?>
<!DOCTYPE html>
<html lang=\"th\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title><?php echo htmlspecialchars(\$row['title']); ?></title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <?php
    // Path to the folder
    \$folderPath = \"../admin/uploads/\";

    // Get all files in the folder
    \$files = glob(\$folderPath . \"*\");

    // Sort files by modified time, newest first
    usort(\$files, function (\$a, \$b) {
        return filemtime(\$b) - filemtime(\$a);
    });

    // Get the latest file
    \$latestFile = !empty(\$files) ? basename(\$files[0]) : null;

    if (\$latestFile) {
        echo \" <link rel='icon' type='image/x-icon' href='\" . \$folderPath . \$latestFile . \"'>\";
    } else {
        echo \"No files found in the folder.\";
    }
    ?>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css\" rel=\"stylesheet\">


    <!-- เพิ่มไฟล์ JS ของ Bootstrap -->
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js\"></script>
    <script src=\"https://use.fontawesome.com/releases/v6.3.0/js/all.js\" crossorigin=\"anonymous\"></script>
    <!-- Google fonts-->
    <link href=\"https://fonts.googleapis.com/css?family=Montserrat:400,700\" rel=\"stylesheet\" type=\"text/css\" />
    <link href=\"https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic\" rel=\"stylesheet\"
        type=\"text/css\" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel=\"stylesheet\" href=\"../CssForIndex/index_css.css\">
    <title>หน้าเว็บหลัก</title>
    <script src=\"scripts.js\"></script>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\">

    <!-- Font Awesome (สำหรับไอคอน) -->
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css\" />

    <!-- Bootstrap JS Bundle (รวม Popper) -->
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js\"></script>
</head>
<body id=\"page-top\">
    <div id=\"notification-icon\">
        <?php
        \$directory = '../admin/uploads/';
        \$files = glob(\$directory . '*'); // ดึงไฟล์ทั้งหมดในโฟลเดอร์
        \$latestFile = '';

        if (!empty(\$files)) {
            usort(\$files, function (\$a, \$b) {
                return filemtime(\$b) - filemtime(\$a); // เรียงไฟล์ตามวันที่แก้ไขล่าสุด
            });
            \$latestFile = \$files[0]; // ไฟล์ล่าสุด
        }
        ?>

        <img src=\"<?php echo \$latestFile; ?>\" alt=\"Notification Icon\" />
    </div>

    <button id=\"scrollToTop\">↑ ขึ้นบนสุด</button>
   <?php
    // 1) กำหนดเมนูหลัก (Hard-coded) ในไฟล์เดียวกัน (ไม่ต้อง include admin_panel.php)
    \$mainMenus = [
        ['id' => 1, 'name' => 'หน้าหลัก', 'link' => 'index.php'],
        ['id' => 2, 'name' => 'รู้จักวันสยาม', 'link' => 'about.php'],
        ['id' => 3, 'name' => 'ธุรกิจวันสยาม', 'link' => 'index.php'],
        ['id' => 4, 'name' => 'ข่าวสารและการเคลื่อนไหว', 'link' => 'index.php'],
        ['id' => 5, 'name' => 'สินค้า', 'link' => 'show_product.php'],
        ['id' => 6, 'name' => 'โปรเจค', 'link' => 'projects.php'],
        ['id' => 7, 'name' => 'โซเชียล', 'link' => 'social.all.php'],
        ['id' => 8, 'name' => 'บทความ', 'link' => 'show_article.php'],
        ['id' => 9, 'name' => 'ติดต่อเรา', 'link' => 'contact.php']
    ];

    // 2) เชื่อมต่อฐานข้อมูล (db.php) ถ้ามี
    include('../db.php');

    // 3) แปลงค่าเมนูหลัก (id) เป็น array เพื่อใช้ใน Query
    \$mainIds = array_column(\$mainMenus, 'id'); // [1,2,3,4,5,6,7]
    \$inClause = implode(',', \$mainIds); // \"1,2,3,4,5,6,7\"
    
    // 4) Query ดึงเมนูย่อยจากตาราง navbar
    \$sql = \"SELECT *
    FROM navbar
    WHERE parent_id IN (\$inClause)
    ORDER BY parent_id ASC, id ASC\";

    \$result = \$conn->query(\$sql);

    // เก็บเมนูย่อยลงใน \$subMenus โดย key = parent_id
    \$subMenus = [];
    if (\$result->num_rows > 0) {
        while (\$row = \$result->fetch_assoc()) {
            \$pid = \$row['parent_id'];
            if (!isset(\$subMenus[\$pid])) {
                \$subMenus[\$pid] = [];
            }
            \$subMenus[\$pid][] = \$row;
        }
    }

    echo \"<nav class='navbar navbar-expand-lg bg-secondary1 text-uppercase fixed-top' id='mainNav'>\";

    echo \"<div class='container'>\";

    // 5.1 แสดงโลโก้ (ถ้ามี)
    echo \"<a href='index.php'>\";

    // ตรวจสอบรูปภาพใน '../admin/uploads' (ถ้าไม่ใช้ ก็ลบส่วนนี้ออกได้)
    \$directory = 'admin/uploads/';
    if (is_dir(\$directory)) {
        \$files = scandir(\$directory);
        if (\$files !== false) {
            \$files = array_diff(\$files, array('.', '..'));
            \$imageFiles = array_filter(\$files, function (\$file) {
                \$ext = pathinfo(\$file, PATHINFO_EXTENSION);
                return in_array(strtolower(\$ext), ['jpg', 'jpeg', 'png', 'gif']);
            });
            if (count(\$imageFiles) > 0) {
                \$image = reset(\$imageFiles);
                echo \"<div class='logoinmenu'>\";
                echo \"<img src='{\$directory}{\$image}' alt='รูปภาพล่าสุด' ' class='track-link' >\";
                echo \"</div>\";
            } else {
                echo \"ไม่มีรูปภาพในโฟลเดอร์ uploads\";
            }
        } else {
            echo \"ไม่สามารถอ่านไฟล์ในโฟลเดอร์ uploads ได้\";
        }
    } else {
        echo \"ไม่พบโฟลเดอร์ uploads\";
    }

    echo \"</a>\";

    // 5.2 ปุ่ม Toggle สำหรับ Mobile
    echo \"<button class=' navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded' type='button'
                        data-bs-toggle='collapse' data-bs-target='#navbarResponsive' aria-controls='navbarResponsive'
                        aria-expanded='false' aria-label='Toggle navigation'>
                    Menu <i class='fas fa-bars'></i>
                    </button>\";

    // 5.3 ส่วนเนื้อหาของ Navbar
    echo \"<div class='collapse navbar-collapse' id='navbarResponsive'>\";
    echo \"<ul class='navbar-nav'>\";

    // 6) วนลูปสร้าง “เมนูหลัก” จาก \$mainMenus
    foreach (\$mainMenus as \$main) {
        \$mainId = \$main['id'];
        \$mainName = \$main['name'];
        \$mainLink = htmlspecialchars(\$main['link'], ENT_QUOTES, 'UTF-8'); // ใช้ link จาก \$mainMenus
    
        // ตรวจสอบว่ามีเมนูย่อยหรือไม่
        if (isset(\$subMenus[\$mainId])) {
            // มีเมนูย่อย แสดงเป็น Dropdown
            echo \"<li class='nav-item dropdown mx-0 '>\";
            echo \"<a class='track-link nav-link dropdown-toggle py-3 ' href='{\$mainLink}' >\"
                . htmlspecialchars(\$mainName, ENT_QUOTES, 'UTF-8') . \"</a>\";
            echo \"<ul class='dropdown-menu'>\";
            foreach (\$subMenus[\$mainId] as \$submenu) {
                \$submenuLink = \"Allpage/\" . htmlspecialchars(
                    \$submenu['link_to'],
                    ENT_QUOTES,
                    'UTF-8'
                );
                \$submenuName = htmlspecialchars(\$submenu['name'], ENT_QUOTES, 'UTF-8');
                echo \"<li><a class='track-link dropdown-item' href='{\$submenuLink}.php'>{\$submenuName}</a></li>
                                    \";
            }
            echo \"</ul>\";
            echo \"</li>\";
        } else {
            // ไม่มีเมนูย่อย แสดงเป็นปกติ ไม่ใช่ Dropdown
            echo \"<li class='nav-item mx-0 '>\";
            echo \"<a class='nav-link py-3 px-0 px-lg-3 rounded' href='{\$mainLink}'>\"
                . htmlspecialchars(\$mainName, ENT_QUOTES, 'UTF-8') . \"</a>\";
            echo \"</li>\";
        }
    }
    echo \"</nav>\";


    ?>
    <?php
    include ('../db.php');
    \$filename = basename(__FILE__); // ได้ชื่อไฟล์ปัจจุบัน
    preg_match('/article_(\d+)_/', \$filename, \$matches); // ดึงเฉพาะตัวเลขหลัง article_
    \$article_id = \$matches[1];

    \$sql = \"SELECT * FROM article WHERE id = ?\";
    \$stmt = \$conn->prepare(\$sql);
    \$stmt->bind_param('i', \$article_id);
    \$stmt->execute();
    \$result = \$stmt->get_result();
    \$row = \$result->fetch_assoc();
    ?>

    <div class=\"container mt-5 text-center flex-column\" style=\"margin-top: 150px;\">
        <h1><?php echo htmlspecialchars(\$row['title']); ?></h1>
        <div class=\"my-4\">
            <img src=\"จัดการหน้าเว็บ/images_article/<?php echo \$row['image_path']; ?>\" class=\"img-fluid\" alt=\"<?php echo htmlspecialchars(\$row['title']); ?>\">
        </div>
        <div class=\"content\">
            <?php echo \$row['content']; ?>
        
        <div class=\"mt-4\">
            <a href=\"../show_article.php\" class=\"btn btn-primary\">กลับหน้าหลัก</a>
        </div>
    </div>
</body>


<footer class=\"footer position-relative\">
    <div class='d-flex justify-content-evenly'>
        <!---Location---->
        <div>
            <h4 class='text-center'>LOCATION</h4>
            <?php
            \$sql = \"SELECT * FROM address\";
            \$result = mysqli_query(\$conn, \$sql);
            if (mysqli_num_rows(\$result) > 0) {
                \$row = mysqli_fetch_assoc(\$result);
                ?>
                <p>ที่อยู่บริษัท <?= \$row['homeNumber'] ?>     <?= \$row['street'] ?> แขวง<?= \$row['subDistrict'] ?>
                    เขต<?= \$row['district'] ?></p>
                <p><?= \$row['province'] ?>, <?= \$row['postalCode'] ?></p>
                <?php
            } else {
                echo \"<p>ไม่มีข้อมูลที่อยู่</p>\";
            }
            ?>
        </div>
        <!---Contact---->
        <div>
            <h4>Contact Us</h4>
            <?php
            \$sql = \"SELECT c.*, f.line as line_url 
                FROM contacts c 
                LEFT JOIN footer_links f 
                ON 1=1 
                LIMIT 1\";
            \$result = mysqli_query(\$conn, \$sql);
            \$row = mysqli_fetch_assoc(\$result);

            echo \"<div class='contactsall'>\";
            echo \"<div class='contactphone my-2 '>\";
            echo \"<i class='bi bi-telephone-fill'>\" . \" \" . htmlspecialchars(\$row['phone']) . \"</i><br>\";
            echo \"<i class='bi bi-building'>\" . \" \" . (\$row['telephone'] === \"\" ? htmlspecialchars(\$row['phone']) : htmlspecialchars(\$row['telephone'])) . \"</i><br>\";
            echo \"<a class='text-decoration-none ' style='color: white;' href='mailto:\" . htmlspecialchars(\$row['email']) . \"'><i class='bi bi-envelope-at-fill'>\" . \" \" . htmlspecialchars(\$row['email']) . \"</i></a><br>\";
            echo \"<a class='text-decoration-none ' style='color: white;' href='\" . htmlspecialchars(\$row['line_url']) . \"'><i class='bi bi-line'>\" . \" \" . htmlspecialchars(\$row['line']) . \"</i></a><br>\";
            echo \"</div>\";

            echo \"</div>\";
            ?>
        </div>
        <!---aboutUS---->
        <div>
            <?php
            try {
                \$pdo = new PDO(
                    \"mysql:host=\$servername;dbname=\$dbname;charset=utf8\",
                    \$username,
                    \$password
                );
                \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException \$e) {
                die(\"เชื่อมต่อฐานข้อมูลไม่ได้: \" . \$e->getMessage());
            }
            \$sql = \"SELECT * FROM messages ORDER BY id DESC\";
            \$stmt = \$pdo->prepare(\$sql);
            \$stmt->execute();
            \$entries = \$stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <h4 class='text-center'>About us</h4>

            <?php if (!empty(\$entries)): ?>
                <ul style='list-style-type: none;'>
                    <?php foreach (\$entries as \$item): ?>
                        <li><?php echo htmlspecialchars(\$item['text'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>ยังไม่มีข้อความใด ๆ</p>
            <?php endif; ?>
        </div>
        <!---Social---->
        <div>
            <?php \$sql = 'select * from footer_links';
            \$result = mysqli_query(\$conn, \$sql);
            \$row = \$result->fetch_assoc()
                ?>
            <h4 class='text-center'>SOCIAL</h4>

            <ul class='d-flex justify-content-evenly'>
                <il class='mx-2'>
                    <a href=\"<?= \$row['facebook'] ?>\" style=\"text-decoration: none; color:#339fff;\"><i
                            class=\"bi bi-facebook fs-3\"></i></a>
                    </li>
                    <il class='mx-2'>
                        <a href=\"<?= \$row['tiktok'] ?>\" style='text-decoration: none; color: #ffffff;'><i
                                class=\"bi bi-tiktok fs-3\"></i></a></li>
                        <il class='mx-2'>
                            <a href=\"<?= \$row['line'] ?>\" style='text-decoration: none;color: #00f31e;'><i
                                    class=\"bi bi-line fs-3\"></i></a></li>
                            <il class='mx-2'>
                                <a href=\"<?= \$row['youtube'] ?>\" style='text-decoration: none; color: #f60505;'><i
                                        class=\"bi bi-youtube fs-3\"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
<div class=\"copyright py-4 text-center text-white\">
    <div class=\"container\"><small>บริษัท วันน์สยาม จำกัด และในเครือ
            ที่อยู่บริษัท 125 (สำนักงานาใหญ่) ถ.ศรีนครินทร์ แขวงบางนาใต้ เขตบางนา กรุงเทพฯลฯ 10260</small></div>
</div>
</html>";

            // บันทึกไฟล์
            if (file_put_contents($file_name, $file_content)) {
                echo "<script>alert('สร้าง Blog และไฟล์สำเร็จ'); window.location.href='admin_panel.php';</script>";
            } else {
                echo "<script>alert('สร้าง Blog สำเร็จ แต่ไม่สามารถสร้างไฟล์ได้'); window.location.href='admin_panel.php';</script>";
            }
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด กรุณาลองใหม่');</script>";
        }
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัพโหลดรูปภาพ');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้าง Blog ใหม่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- เพิ่ม CKEditor สำหรับแก้ไขเนื้อหา -->
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">สร้าง Blog ใหม่</h2>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">หัวข้อ Blog</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">เนื้อหา</label>
                <textarea id="editor" name="content" required style="width: 100%; height: 200px;"></textarea>
            </div>
            
            <div class="mb-3">
                <label for="article_image" class="form-label">รูปภาพประกอบ</label>
                <input type="file" class="form-control" id="article_image" name="article_image" accept="image/*" required>
            </div>
            
            <div class="mb-3">
                <button type="submit" name="submit" class="btn btn-primary">สร้าง Blog</button>
                <a href="admin_panel.php" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>
