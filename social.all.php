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
    $folderPath = "admin/uploads/";

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
    <!------------------------------------------------------------------->
    <?php
    // เชื่อมต่อฐานข้อมูล
    include('db.php');

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ดึงข้อมูลจากทุกตาราง
    $tables = ['card_tiktok', 'card_facebook'];
    $data = [];

    // วนลูปดึงข้อมูลจากแต่ละตาราง
    foreach ($tables as $table) {
        $sql = "SELECT * FROM $table";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'platform_name' => $row['platform_name'],
                    'platform_link' => $row['platform_link']
                ];
            }
        }
    }

    // ฟังก์ชันกำหนด URL ของโลโก้
    function getLogoSrc($platformName)
    {
        $logos = [
            'TikTok' => 'https://upload.wikimedia.org/wikipedia/commons/a/a9/TikTok_logo.png',
            'Facebook' => 'https://upload.wikimedia.org/wikipedia/commons/8/89/Facebook_Logo_%282019%29.png',
            // เพิ่มแพลตฟอร์มอื่นๆ ที่นี่
        ];

        return $logos[$platformName] ?? 'https://via.placeholder.com/100'; // ใช้ placeholder หากไม่มีโลโก้ในรายการ
    }
    ?>

    <h1>ข้อมูลจากหลายตาราง</h1>
    <div class="allcard">
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/YouTube_2024.svg" alt="Platform Logo">
            <h3>Youtube</h3>
            <a href="social_youtube.php" target="_blank">ไปยังแพลตฟอร์ม</a>
        </div>
        <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
        <div class="card">
            <!-- ดึงโลโก้จากฟังก์ชัน -->
                    <img src="<?php echo getLogoSrc($item['platform_name']); ?>" alt="Platform Logo">
                    <!-- ชื่อแพลตฟอร์ม -->
                    <h3><?php echo htmlspecialchars($item['platform_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <!-- ลิงก์แพลตฟอร์ม -->
                    <a href="<?php echo htmlspecialchars($item['platform_link'], ENT_QUOTES, 'UTF-8'); ?>"
                        target="_blank">ไปยังแพลตฟอร์ม</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>ไม่มีข้อมูลในตาราง</p>
        <?php endif; ?>
    </div>




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
    <style>
        body {

            justify-content: center;
            /* จัดกึ่งกลางแนวนอน */
            height: 100vh;
            /* ความสูงเต็มหน้าจอ */
            margin: 0;
            /* ลบระยะขอบเริ่มต้น */
        }

        .allcard {
            margin-top: 150px;
        }

        h3 {
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin: 20px 0;
            text-align: center;
        }

        .card {
            border: 2px solid #000;
            border-radius: 15px;
            padding: 20px;
            margin: 10px auto;
            width: 100%;
            /* เต็มความกว้าง */
            max-width: 100%;
            /* ไม่เกินหน้าจอ */
            display: flex;
            align-items: center;
            gap: 15px;
            box-sizing: border-box;
            /* รวม padding ใน width */
        }

        .card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .card-text {
            flex-grow: 1;
            text-align: left;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 0 15px;
            /* ขอบซ้ายขวา */
            box-sizing: border-box;
        }
    </style>
</body>

</html>