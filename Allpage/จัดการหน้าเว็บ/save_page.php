<?php
// เชื่อมต่อฐานข้อมูล
include('../../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $body_top = mysqli_real_escape_string($conn, $_POST['body_top']);
    $text_left = mysqli_real_escape_string($conn, $_POST['text_left']);
    $img_left = mysqli_real_escape_string($conn, $_POST['img_left']);
    $img_right = mysqli_real_escape_string($conn, $_POST['img_right']);
    $img_onTop = mysqli_real_escape_string($conn, $_POST['img_onTop']);
    $text_right = mysqli_real_escape_string($conn, $_POST['text_right']);
    $seo_title = mysqli_real_escape_string($conn, $_POST['seo_title']);
    $seo_description = mysqli_real_escape_string($conn, $_POST['seo_description']);
    $seo_keyword = mysqli_real_escape_string($conn, $_POST['seo_keyword']);

    // ตรวจสอบว่า name ไม่ว่าง
    if (empty($name)) {
        die('ชื่อหน้าไม่สามารถเว้นว่างได้');
    }

    // เพิ่มข้อมูลลงฐานข้อมูล
    $query = "INSERT INTO page_aboutme
        (name, body_top, img_onTop, text_left, img_left, img_right, text_right, seo_title, seo_description, seo_keyword) 
        VALUES 
        ( '$name','$img_onTop','$body_top', '$text_left', '$img_left', '$img_right', '$text_right', '$seo_title', '$seo_description', '$seo_keyword')";

    if (mysqli_query($conn, $query)) {
        // ถ้าบันทึกสำเร็จ สร้างไฟล์ใหม่
        $file_name = "../" . $name . ".php";

        // ใช้ Nowdoc/HereDoc เพื่อความสะดวกในการใส่โค้ด HTML
        $file_content = <<<HTML
<?php
include('../db.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content='$seo_description'>
    <meta name='keywords' content='$seo_keyword'>

<?php
    // Path to the folder
    \$folderPath = "../admin/uploads/";

    // Get all files in the folder
    \$files = glob(\$folderPath . "*");

    // Sort files by modified time, newest first
    usort(\$files, function (\$a, \$b) {
        return filemtime(\$b) - filemtime(\$a);
    });

    // Get the latest file
    \$latestFile = !empty(\$files) ? basename(\$files[0]) : null;

    if (\$latestFile) {
        echo '<link rel="icon" type="image/x-icon" href="' . \$folderPath . \$latestFile . '">';
    } else {
        echo "No files found in the folder.";
    }
?>

    <!-- เพิ่มไฟล์ CSS ของ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <!-- เพิ่มไฟล์ JS ของ Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="CssForIndex/index_css.css">
    <title>หน้าเว็บหลัก</title>
    <script src="scripts.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (สำหรับไอคอน) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Bootstrap JS Bundle (รวม Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div>
    <title>$seo_title</title>
    
</head>
<body>  
    <link rel="stylesheet" href="จัดการหน้าเว็บ/cssforpanel/page_about.css">

<div id="notification-icon">
    <?php
    \$directory = '../admin/img/logo/';
    \$files = glob(\$directory . '*');
    \$latestFile = '';

    if (!empty(\$files)) {
        usort(\$files, function (\$a, \$b) {
            return filemtime(\$b) - filemtime(\$a);
        });
        \$latestFile = \$files[0];
    }
    ?>
    <img src="<?php echo \$latestFile; ?>" alt="Notification Icon" /> 
</div>

<button id="scrollToTop">↑ ขึ้นบนสุด</button>

<?php
    // กำหนดเมนูหลัก
    \$mainMenus = [
        ['id' => 1, 'name' => 'หน้าหลัก'],
        ['id' => 2, 'name' => 'เกี่ยวกับเรา'], 
        ['id' => 3, 'name' => 'สินค้า'],
        ['id' => 4, 'name' => 'โปรเจค'],
        ['id' => 5, 'name' => 'โซเซี่ยล'],
        ['id' => 6, 'name' => 'บทความ'],
        ['id' => 7, 'name' => 'ติดต่อเรา']
    ];

    // แปลงค่าเมนูหลัก (id) เป็น array เพื่อใช้ใน Query
    \$mainIds = array_column(\$mainMenus, 'id');
    \$inClause = implode(',', \$mainIds);

    // Query ดึงเมนูย่อยจากตาราง navbar
    \$sql = "SELECT * 
            FROM navbar
            WHERE parent_id IN (\$inClause)
            ORDER BY parent_id ASC, id ASC";

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

    echo "<nav class='navbar navbar-expand-lg bg-secondary1 text-uppercase fixed-top' id='mainNav'>";
    echo "<div class='container'>";

    // แสดงโลโก้
    echo "<a href='#'>";
    \$directory = '../admin/uploads/';

    if (is_dir(\$directory)) {
        // สแกนไฟล์ในโฟลเดอร์
        \$files = scandir(\$directory);
        // ตัด . และ .. ออก
        \$files = array_diff(\$files, array('.', '..'));

        // กรองให้เหลือไฟล์ภาพเท่านั้น
        \$imageFiles = array_filter(\$files, function(\$file) {
            \$ext = pathinfo(\$file, PATHINFO_EXTENSION);
            return in_array(strtolower(\$ext), ['jpg', 'jpeg', 'png', 'gif']);
        });

        // หากเจอไฟล์ภาพอย่างน้อย 1 ไฟล์
        if (!empty(\$imageFiles)) {
            \$latestImage = reset(\$imageFiles); // หยิบไฟล์แรกของ array
            echo "<img src='{\$directory}{\$latestImage}' alt='โลโก้' style='height: 75px; width: 97px; margin-right: 50px;'>";
        } else {
            echo "ไม่มีรูปภาพในโฟลเดอร์ uploads";
        }
    } else {
        echo "ไม่พบโฟลเดอร์ uploads";
    }

    echo "</a>";

    // ปุ่ม Toggle สำหรับ Mobile
    echo "<button class='navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded' 
        type='button' data-bs-toggle='collapse' data-bs-target='#navbarResponsive' 
        aria-controls='navbarResponsive' aria-expanded='false' 
        aria-label='Toggle navigation'>Menu <i class='fas fa-bars'></i></button>";

    // ส่วนเนื้อหาของ Navbar
    echo "<div class='collapse navbar-collapse' id='navbarResponsive'>";
    echo "<ul class='navbar-nav ms-auto'>";

    foreach (\$mainMenus as \$main) {
        \$mainId = \$main['id'];
        \$mainName = htmlspecialchars(\$main['name']);

        if (isset(\$subMenus[\$mainId])) {
            echo "<li class='nav-item dropdown mx-0 mx-lg-1'>";
            echo "<a class='nav-link dropdown-toggle py-3 px-0 px-lg-3 rounded' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>"
                . htmlspecialchars(\$mainName) . "</a>";
            echo "<ul class='dropdown-menu'>";
            foreach (\$subMenus[\$mainId] as \$submenu) {
                \$submenuLink = htmlspecialchars(\$submenu['link_to']);
                \$submenuName = htmlspecialchars(\$submenu['name']);
                echo "<li><a class='dropdown-item' href='\$submenuLink'>\$submenuName</a></li>";
            }
            echo "</ul></li>";
        } else {
            echo "<li class='nav-item mx-0 mx-lg-1'><a class='nav-link py-3 px-0 px-lg-3 rounded' href='#'>"
                . htmlspecialchars(\$mainName) . "</a></li>";
        }
    }

    echo "</ul>";
    echo "</div></div></nav>";
?>
<div class="ontop">
        <img src="จัดการหน้าเว็บ/<?php echo htmlspecialchars('$img_onTop', ENT_QUOTES, 'UTF-8'); ?>"
             alt="onTop Image" style="max-width: 300px;">
    </div>

<div class="content">
    <p><?php echo htmlspecialchars("$body_top", ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="left">
        <img src="จัดการหน้าเว็บ/<?php echo htmlspecialchars('$img_left', ENT_QUOTES, 'UTF-8'); ?>" 
             alt="Left Image" style="max-width: 300px;">
        <p><?php echo htmlspecialchars("$text_left", ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <div class="right">
        <img src="จัดการหน้าเว็บ/<?php echo htmlspecialchars('$img_right', ENT_QUOTES, 'UTF-8'); ?>"
             alt="Right Image" style="max-width: 300px;">
        <p><?php echo htmlspecialchars("$text_right", ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>

<!-------เพิ่มโค๊ดข้างล่าง (Footer)----------------->
<footer class="footer">
    <div class='d-flex justify-content-evenly'>
        <!---Location---->
        <div>
            <h4 class='text-center'>LOCATION</h4>
            <?php
            \$sql = "SELECT * FROM address";
            \$result = mysqli_query(\$conn, \$sql);
            if (\$result && mysqli_num_rows(\$result) > 0) {
                \$row = mysqli_fetch_assoc(\$result);
                ?>
                <p>
                    ที่อยู่บริษัท <?= htmlspecialchars(\$row['homeNumber']) ?>
                    <?= htmlspecialchars(\$row['street']) ?> 
                    แขวง<?= htmlspecialchars(\$row['subDistrict']) ?>
                    เขต<?= htmlspecialchars(\$row['district']) ?>
                </p>
                <p>
                    <?= htmlspecialchars(\$row['province']) ?>, 
                    <?= htmlspecialchars(\$row['postalCode']) ?>
                </p>
                <?php
            } else {
                echo "<p>ไม่มีข้อมูลที่อยู่</p>";
            }
            ?>
        </div>
        <!---Contact---->
        <div>
            <h4>Contact Us</h4>
            <?php
            \$sql = "SELECT * FROM contacts";
            \$result = mysqli_query(\$conn,\$sql);
            if (\$result && mysqli_num_rows(\$result) > 0) {
                \$row = \$result->fetch_assoc();

                echo "<div class='contactsall'>";
                echo "<div class='contactphone my-2 '>";
                echo "<i class='bi bi-telephone-fill'> " . htmlspecialchars(\$row['phone']) . "</i><br>";
                echo "<i class='bi bi-line'> " . htmlspecialchars(\$row['line']) . "</i><br>";
                echo "<i class='bi bi-envelope-at-fill'> " . htmlspecialchars(\$row['email']) . "</i><br>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<p>ไม่พบข้อมูลติดต่อ</p>";
            }
            ?>
        </div>
        <!---aboutUS---->
        <div>
            <?php
            try {
                \$pdo = new PDO(
                    "mysql:host=\$servername;dbname=\$dbname;charset=utf8",
                    \$username,
                    \$password
                );
                \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException \$e) {
                die("เชื่อมต่อฐานข้อมูลไม่ได้: " . \$e->getMessage());
            }
            \$sql = "SELECT * FROM messages ORDER BY id DESC";
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
            <?php 
            \$sql = 'SELECT * FROM footer_links';
            \$result = mysqli_query(\$conn, \$sql);
            \$row = (\$result && mysqli_num_rows(\$result) > 0) ? \$result->fetch_assoc() : null;
            ?>
            <h4 class='text-center'>SOCIAL</h4>
            
            <ul class='d-flex justify-content-evenly'>
                <li class='mx-2'>
                    <a href="<?php echo \$row ? htmlspecialchars(\$row['facebook']) : '#'; ?>" 
                       style="text-decoration: none; color:#339fff;">
                       <i class="bi bi-facebook fs-3"></i>
                    </a>
                </li>
                <li class='mx-2'>
                    <a href="<?php echo \$row ? htmlspecialchars(\$row['tiktok']) : '#'; ?>" 
                       style='text-decoration: none; color: #ffffff;'>
                       <i class="bi bi-tiktok fs-3"></i>
                    </a>
                </li>
                <li class='mx-2'>
                    <a href="#" style='text-decoration: none;color: #00f31e;'>
                        <i class="bi bi-line fs-3"></i>
                    </a>
                </li>
                <li class='mx-2'>
                    <a href="#" style='text-decoration: none; color: #f60505;'>
                        <i class="bi bi-youtube fs-3"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>

<div class="copyright py-4 text-center text-white">
    <div class="container">
        <small>
            บริษัท วันน์สยาม จำกัด และในเครือ
            ที่อยู่บริษัท 125 (สำนักงานาใหญ่) ถ.ศรีนครินทร์ แขวงบางนาใต้ เขตบางนา กรุงเทพฯลฯ 10260
        </small>
    </div>
</div>

</footer>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const navbar = document.getElementById("mainNav");

        window.addEventListener("scroll", () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll < 100) {
                // เมื่อเลื่อนน้อยกว่า 100px - โปร่งใส
                navbar.classList.add("transparent");
            } else {
                // เมื่อเลื่อนเกิน 100px - มีพื้นหลัง
                navbar.classList.remove("transparent");
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const scrollToTopBtn = document.getElementById("scrollToTop");

        // ตรวจสอบการเลื่อนหน้า
        window.addEventListener("scroll", function () {
            if (window.scrollY > 200) { // แสดงปุ่มเมื่อเลื่อนลงเกิน 200px
                scrollToTopBtn.classList.add("show");
            } else {
                scrollToTopBtn.classList.remove("show");
            }
        });

        // เมื่อคลิกปุ่ม ให้เลื่อนขึ้นไปด้านบน
        scrollToTopBtn.addEventListener("click", function () {
            window.scrollTo({
                top: 0,
                behavior: "smooth", // เลื่อนอย่างนุ่มนวล
            });
        });
    });
</script>
</body>
</html>
HTML; // ต้องไม่มี space หรือ tab ก่อนหน้า

        // เขียนไฟล์ใหม่
        if (file_put_contents($file_name, $file_content)) {
            echo "บันทึกสำเร็จและสร้างไฟล์ใหม่: $file_name";
        } else {
            echo "บันทึกสำเร็จ แต่สร้างไฟล์ใหม่ไม่สำเร็จ!";
        }
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($conn);
    }
}
?>