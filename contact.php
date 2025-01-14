<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content='ทีมงานคุณภาพ'>
    <meta name='keywords' content='วันสยาม,เกี่ยวกับฉัน'>

    <?php
    // Path to the folder
    $folderPath = "../admin/uploads/";

    // Get all files in the folder
    $files = glob($folderPath . "*");

    // Sort files by modified time, newest first
    usort($files, function ($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    // Get the latest file
    $latestFile = !empty($files) ? basename($files[0]) : null;

    if ($latestFile) {
        echo '<link rel="icon" type="image/x-icon" href="' . $folderPath . $latestFile . '">';
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

    <style>
/* Organization Chart Styles */
.org-chart {
        text-align: center;
      }
      .org-node {
        display: inline-block;
        padding: 10px;
        margin: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
      }
      .org-connection {
        height: 20px;
        border-left: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        margin: 0 auto;
        width: 50%;
      }
.org-chart-section {
    padding: 20px;
}

.org-level {
    display: flex;
    justify-content: center;
    margin: 20px 0;
    position: relative;
}

.org-box-container {
    display: flex;
    gap: 30px;
    position: relative;
}

.org-box {
    min-width: 400px;
}

.org-box .card {
    transition: transform 0.3s ease;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.org-box .card:hover {
    transform: translateY(-5px);
}

.org-box img {
    margin-top: 10px;
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.org-line {
    width: 2px;
    height: 30px;
    background-color: #fff;
    margin: 0 auto;
}

.position {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .org-box-container {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .org-box {
        min-width: 180px;
    }
}
</style>

    <title>เกี่ยวกับ วันสยาม</title>

</head>

<body>
    <link rel="stylesheet" href="Allpage/จัดการหน้าเว็บ/cssforpanel/page_about.css">

    <div id="notification-icon">
        <?php
        $directory = 'admin/img/logo/';
        $files = glob($directory . '*');
        $latestFile = '';

        if (!empty($files)) {
            usort($files, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            $latestFile = $files[0];
        }
        ?>
        <img src="<?php echo $latestFile; ?>" alt="Notification Icon" />
    </div>

    <button id="scrollToTop">↑ ขึ้นบนสุด</button>

    <?php
    // 1) กำหนดเมนูหลัก (Hard-coded) ในไฟล์เดียวกัน (ไม่ต้อง include admin_panel.php)
    $mainMenus = [
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
    include('db.php');

    // 3) แปลงค่าเมนูหลัก (id) เป็น array เพื่อใช้ใน Query
    $mainIds = array_column($mainMenus, 'id'); // [1,2,3,4,5,6,7]
    $inClause = implode(',', $mainIds); // "1,2,3,4,5,6,7"
    
    // 4) Query ดึงเมนูย่อยจากตาราง navbar
    $sql = "SELECT *
    FROM navbar
    WHERE parent_id IN ($inClause)
    ORDER BY parent_id ASC, id ASC";

    $result = $conn->query($sql);

    // เก็บเมนูย่อยลงใน $subMenus โดย key = parent_id
    $subMenus = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pid = $row['parent_id'];
            if (!isset($subMenus[$pid])) {
                $subMenus[$pid] = [];
            }
            $subMenus[$pid][] = $row;
        }
    }

    echo "<nav class='navbar navbar-expand-lg bg-secondary1 text-uppercase fixed-top' id='mainNav'>";

    echo "<div class='container'>";

    // 5.1 แสดงโลโก้ (ถ้ามี)
    echo "<a href='index.php'>";

    // ตรวจสอบรูปภาพใน 'admin/uploads' (ถ้าไม่ใช้ ก็ลบส่วนนี้ออกได้)
    $directory = 'admin/uploads/';
    if (is_dir($directory)) {
        $files = scandir($directory);
        if ($files !== false) {
            $files = array_diff($files, array('.', '..'));
            $imageFiles = array_filter($files, function ($file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                return in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
            });
            if (count($imageFiles) > 0) {
                $image = reset($imageFiles);
                echo "<div class='logoinmenu'>";
                echo "<img src='{$directory}{$image}' alt='รูปภาพล่าสุด' ' class='track-link' >";
                echo "</div>";
            } else {
                echo "ไม่มีรูปภาพในโฟลเดอร์ uploads";
            }
        } else {
            echo "ไม่สามารถอ่านไฟล์ในโฟลเดอร์ uploads ได้";
        }
    } else {
        echo "ไม่พบโฟลเดอร์ uploads";
    }

    echo "</a>";

    // 5.2 ปุ่ม Toggle สำหรับ Mobile
    echo "<button class=' navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded' type='button'
                        data-bs-toggle='collapse' data-bs-target='#navbarResponsive' aria-controls='navbarResponsive'
                        aria-expanded='false' aria-label='Toggle navigation'>
                    Menu <i class='fas fa-bars'></i>
                    </button>";

    // 5.3 ส่วนเนื้อหาของ Navbar
    echo "<div class='collapse navbar-collapse' id='navbarResponsive'>";
    echo "<ul class='navbar-nav'>";

    // 6) วนลูปสร้าง “เมนูหลัก” จาก $mainMenus
    foreach ($mainMenus as $main) {
        $mainId = $main['id'];
        $mainName = $main['name'];
        $mainLink = htmlspecialchars($main['link'], ENT_QUOTES, 'UTF-8'); // ใช้ link จาก $mainMenus
    
        // ตรวจสอบว่ามีเมนูย่อยหรือไม่
        if (isset($subMenus[$mainId])) {
            // มีเมนูย่อย แสดงเป็น Dropdown
            echo "<li class='nav-item dropdown mx-0 '>";
            echo "<a class='track-link nav-link dropdown-toggle py-3 ' href='{$mainLink}' >"
                . htmlspecialchars($mainName, ENT_QUOTES, 'UTF-8') . "</a>";
            echo "<ul class='dropdown-menu'>";
            foreach ($subMenus[$mainId] as $submenu) {
                $submenuLink = "Allpage/" . htmlspecialchars(
                    $submenu['link_to'],
                    ENT_QUOTES,
                    'UTF-8'
                );
                $submenuName = htmlspecialchars($submenu['name'], ENT_QUOTES, 'UTF-8');
                echo "<li><a class='track-link dropdown-item' href='{$submenuLink}.php'>{$submenuName}</a></li>
                                    ";
            }
            echo "</ul>";
            echo "</li>";
        } else {
            // ไม่มีเมนูย่อย แสดงเป็นปกติ ไม่ใช่ Dropdown
            echo "<li class='nav-item mx-0 '>";
            echo "<a class='nav-link py-3 px-0 px-lg-3 rounded' href='{$mainLink}'>"
                . htmlspecialchars($mainName, ENT_QUOTES, 'UTF-8') . "</a>";
            echo "</li>";
        }
    }
    echo "</nav>";


    ?>
    <hr>
<!-- Map--------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
 
<div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d969.2274475934657!2d100.64466799473288!3d13.663249002999938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d5fbeba09d72b%3A0x85768955a946db67!2z4Lia4Lij4Li04Lip4Lix4LiX4Lin4Lix4LiZ4LiZ4LmM4Liq4Lii4Liy4LihIOC4iOC4s-C4geC4seC4lA!5e0!3m2!1sen!2sth!4v1732690306197!5m2!1sen!2sth"
             width="1910" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </section>
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    
<!-- เบอร์ห--------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<hr>
<?php
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8",
                   $username,
                   $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $e->getMessage());
}

// ดึงข้อมูลทั้งหมด (SELECT)
$sql = "SELECT * FROM ctpage ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หน้าแสดงข้อความทั้งหมด</title>
    <style>
        /* กำหนดขนาดฟอนต์สำหรับทั้งหน้า */
        body {
            font-size: 18px; /* ปรับขนาดตามต้องการ เช่น 20px, 1.2em */
            font-family: Arial, sans-serif; /* เลือกฟอนต์ที่ต้องการ */
        }

        /* กำหนดขนาดฟอนต์สำหรับหัวข้อ */
        h1 {
            font-size: 2em; /* ตัวอย่างเพิ่มขนาดฟอนต์สำหรับหัวข้อ */
        }

        /* กำหนดขนาดฟอนต์สำหรับรายการและเอาจุดไข้ปลาออก */
        ul {
            list-style-type: none; /* เอาจุดไข้ปลาออก */
            padding: 0; /* เอาช่องว่างด้านซ้ายออก */
            margin: 0; /* เอาช่องว่างด้านนอกออกถ้าจำเป็น */
        }

        ul li {
            font-size: 1.2em; /* ปรับขนาดตามต้องการ */
            margin-bottom: 10px; /* เพิ่มช่องว่างระหว่างรายการถ้าต้องการ */
        }

        /* กำหนดขนาดฟอนต์สำหรับข้อความเมื่อไม่มีข้อมูล */
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1>บริษัท วันน์สยาม จำกัด สำนักงานาใหญ่</h1>
    <?php if (!empty($entries)): ?>
        <ul>
            <?php foreach ($entries as $item): ?>
                <li><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>ยังไม่มีข้อความใด ๆ</p>
    <?php endif; ?>
    <hr>
</body>
</html>



<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-------เพิ่มโค๊ดข้างล่าง (Footer)----------------->
    <footer class="footer position-relative text-center  p-4 w-100 w-100">
        <div class='d-flex justify-content-evenly'>
            <!---Location---->
            <div>
                <h4 class='text-center'>LOCATION</h4>
                <?php
                $sql = "SELECT * FROM address";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <p>
                        ที่อยู่บริษัท <?= htmlspecialchars($row['homeNumber']) ?>
                        <?= htmlspecialchars($row['street']) ?>
                        แขวง<?= htmlspecialchars($row['subDistrict']) ?>
                        เขต<?= htmlspecialchars($row['district']) ?>
                    </p>
                    <p>
                        <?= htmlspecialchars($row['province']) ?>,
                        <?= htmlspecialchars($row['postalCode']) ?>
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
                $sql = "SELECT * FROM contacts";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = $result->fetch_assoc();

                    echo "<div class='contactsall'>";
                    echo "<div class='contactphone my-2 '>";
                    echo "<i class='bi bi-telephone-fill'> " . htmlspecialchars($row['phone']) . "</i><br>";
                    echo "<i class='bi bi-line'> " . htmlspecialchars($row['line']) . "</i><br>";
                    echo "<i class='bi bi-envelope-at-fill'> " . htmlspecialchars($row['email']) . "</i><br>";
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
                    $pdo = new PDO(
                        "mysql:host=$servername;dbname=$dbname;charset=utf8",
                        $username,
                        $password
                    );
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $e->getMessage());
                }
                $sql = "SELECT * FROM messages ORDER BY id DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <h4 class='text-center'>About us</h4>

                <?php if (!empty($entries)): ?>
                    <ul style='list-style-type: none;'>
                        <?php foreach ($entries as $item): ?>
                            <li><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>ยังไม่มีข้อความใด ๆ</p>
                <?php endif; ?>
            </div>
            <!---Social---->
            <div>
                <?php
                $sql = 'SELECT * FROM footer_links';
                $result = mysqli_query($conn, $sql);
                $row = ($result && mysqli_num_rows($result) > 0) ? $result->fetch_assoc() : null;
                ?>
                <h4 class='text-center'>SOCIAL</h4>

                <ul class='d-flex justify-content-evenly'>
                    <li class='mx-2'>
                        <a href="<?php echo $row ? htmlspecialchars($row['facebook']) : '#'; ?>"
                            style="text-decoration: none; color:#339fff;">
                            <i class="bi bi-facebook fs-3"></i>
                        </a>
                    </li>
                    <li class='mx-2'>
                        <a href="<?php echo $row ? htmlspecialchars($row['tiktok']) : '#'; ?>"
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