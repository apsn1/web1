<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>one siameRudee</title>
    <!-- Favicon-->

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


    </style>
    <title>SEO</title>
  </head>
  <body id="page-top">
    <div id="notification-icon">
        <?php
        $directory = 'admin/img/logo/';
        $files = glob($directory . '*'); // ดึงไฟล์ทั้งหมดในโฟลเดอร์
        $latestFile = '';

        if (!empty($files)) {
            usort($files, function ($a, $b) {
                return filemtime($b) - filemtime($a); // เรียงไฟล์ตามวันที่แก้ไขล่าสุด
            });
            $latestFile = $files[0]; // ไฟล์ล่าสุด
        }
        ?>

        <img src="<?php echo $latestFile; ?>" alt="Notification Icon" />
    </div>

    <button id="scrollToTop">↑ ขึ้นบนสุด</button>
    <?php
    // 1) กำหนดเมนูหลัก (Hard-coded) ในไฟล์เดียวกัน (ไม่ต้อง include admin_panel.php)
    $mainMenus = [
        ['id' => 1, 'name' => 'หน้าหลัก', 'link' => 'home.php'],
        ['id' => 2, 'name' => 'เกี่ยวกับเรา', 'link' => 'about.php'],
        ['id' => 3, 'name' => 'สินค้า', 'link' => 'showproducts.php'],
        ['id' => 4, 'name' => 'โปรเจค', 'link' => 'projects.php'],
        ['id' => 5, 'name' => 'โซเชียล', 'link' => 'social.php'],
        ['id' => 6, 'name' => 'บทความ', 'link' => 'show_articles.php'],
        ['id' => 7, 'name' => 'ติดต่อเรา', 'link' => 'contact.php']
    ];

    // 2) เชื่อมต่อฐานข้อมูล (db.php) ถ้ามี
    include('db.php');

    // 3) แปลงค่าเมนูหลัก (id) เป็น array เพื่อใช้ใน Query
    $mainIds = array_column($mainMenus, 'id'); // [1,2,3,4,5,6,7]
    $inClause = implode(',', $mainIds);        // "1,2,3,4,5,6,7"
    
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

    // ดึงข้อมูลบทความทั้งหมดจากฐานข้อมูล
    $sql = "SELECT * FROM article ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $articles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    }
    ?>

    <nav class='navbar navbar-expand-lg bg-secondary1 text-uppercase fixed-top' id='mainNav'>
        <div class='container'>

            <!-- 5.1 แสดงโลโก้ (ถ้ามี) -->
            <a class='navbar-brand' href='home.php'>
                <?php
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
                            echo "<img src='{$directory}" . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "' 
                              alt='โลโก้' 
                              style='height: 95px;'>";
                        } else {
                            echo "ไม่มีรูปภาพในโฟลเดอร์ uploads";
                        }
                    } else {
                        echo "ไม่สามารถอ่านไฟล์ในโฟลเดอร์ uploads ได้";
                    }
                } else {
                    echo "ไม่พบโฟลเดอร์ uploads";
                }
                ?>
            </a>

            <!-- 5.2 ปุ่ม Toggle สำหรับ Mobile -->
            <button class='navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded' type='button'
                data-bs-toggle='collapse' data-bs-target='#navbarResponsive' aria-controls='navbarResponsive'
                aria-expanded='false' aria-label='Toggle navigation'>
                Menu <i class='fas fa-bars'></i>
            </button>

            <!-- 5.3 ส่วนเนื้อหาของ Navbar -->
            <div class='collapse navbar-collapse' id='navbarResponsive'>
                <ul class='navbar-nav ms-auto'> <!-- ใช้ ms-auto เพื่อจัดเมนูไปทางขวา -->
                    <?php foreach ($mainMenus as $main): ?>
                        <?php
                        $mainId = $main['id'];
                        $mainName = htmlspecialchars($main['name'], ENT_QUOTES, 'UTF-8');
                        $mainLink = htmlspecialchars($main['link'], ENT_QUOTES, 'UTF-8');

                        // ตรวจสอบว่ามีเมนูย่อยหรือไม่
                        if (isset($subMenus[$mainId])):
                            ?>
                            <li class='nav-item dropdown'>
                                <a class='nav-link dropdown-toggle' href='<?php echo $mainLink; ?>' role='button'
                                    data-bs-toggle='dropdown' aria-expanded='false'>
                                    <?php echo $mainName; ?>
                                </a>
                                <ul class='dropdown-menu'>
                                    <?php foreach ($subMenus[$mainId] as $submenu): ?>
                                        <?php
                                        $submenuName = htmlspecialchars($submenu['name'], ENT_QUOTES, 'UTF-8');
                                        $submenuLinkTo = htmlspecialchars($submenu['link_to'], ENT_QUOTES, 'UTF-8');
                                        $submenuLink = "Allpage/" . $submenuLinkTo . ".php";
                                        ?>
                                        <li><a class='dropdown-item'
                                                href='<?php echo $submenuLink; ?>'><?php echo $submenuName; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class='nav-item'>
                                <a class='nav-link' href='<?php echo $mainLink; ?>'><?php echo $mainName; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
    <header id="Home" class="custom-headerbanner text-center ">
            <div class="custom-position-relative" style="padding-top: 0px; padding-bottom: 0px;">
              <div class="custom-banner-container">
                  <img id="bannerImage" src="" style="width:100%; alt='<?php echo $buttons[0]; ?>'">
              </div>
            </div>
    </header>

        <h2 class="text-center " style="margin-top: 150px;">บทความ</h2>

        <div class="container mt-3">
          <div class="row">
            <?php
            // แสดง 4 บทความแรกในรูปแบบการ์ด  
            for ($i = 0; $i < min(4, count($articles)); $i++) {
                $article = $articles[$i];
            ?>
                <div class="col-3 justify-content-center align-items-center">
                    <div class="card h-100">
                        <img src="Allpage/จัดการหน้าเว็บ/images_all/<?php echo $article['image_path']; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($article['title']); ?>"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
                            <p class="card-text"><?php echo mb_substr(strip_tags($article['content']), 0, 100, 'UTF-8') . '...'; ?></p>
                            <div class="text-center">
                                <a href="/web/Allpage/จัดการหน้าเว็บ/article_<?php echo $article['id']; ?>_<?php echo $article['title']; ?>.php" 
                                   class="btn btn-primary mt-auto">อ่านบทความ</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
          </div>
        </div>
        <div class="container my-5 flex-column text-start">
            <h5 class="text-start mb-4">บทความที่น่าสนใจ</h5>
            <div class="row row-cols-1 row-cols-md-6 g-4 text-center">
                <?php
                // แสดงบทความที่เหลือในรูปแบบ grid
                for ($i = 4; $i < count($articles); $i++) {
                    $article = $articles[$i];
                ?>
                    <div class="col">
                        <a href="/web/Allpage/จัดการหน้าเว็บ/article_<?php echo $article['id']; ?>_<?php echo $article['title']; ?>.php" 
                           class="text-decoration-none">
                            <div class=" h-100">
                                <div class="">
                                    <h6 class=" text-dark"><?php echo htmlspecialchars($article['title']); ?></h6>
                                    <small class="text-muted"><?php echo date('d/m/Y', strtotime($article['created_at'])); ?></small>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>

  </body>
  <footer class="footer position-relative text-center  p-4 w-100 w-100">
    <div class='d-flex justify-content-evenly'>
        <!---Location---->
        <div>
            <h4 class='text-center'>LOCATION</h4>
            <?php
            $sql = "SELECT * FROM address";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                ?>
                <p>ที่อยู่บริษัท <?= $row['homeNumber'] ?>     <?= $row['street'] ?> แขวง<?= $row['subDistrict'] ?>
                    เขต<?= $row['district'] ?></p>
                <p><?= $row['province'] ?>, <?= $row['postalCode'] ?></p>
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
            $sql = "SELECT c.*, f.line as line_url 
                FROM contacts c 
                LEFT JOIN footer_links f 
                ON 1=1 
                LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            echo "<div class='contactsall'>";
            echo "<div class='contactphone my-2 '>";
            echo "<i class='bi bi-telephone-fill'>" . " " . htmlspecialchars($row['phone']) . "</i><br>";
            echo "<i class='bi bi-building'>" . " " . ($row['telephone'] === "" ? htmlspecialchars($row['phone']) : htmlspecialchars($row['telephone'])) . "</i><br>";
            echo "<a class='text-decoration-none ' style='color: white;' href='mailto:" . htmlspecialchars($row['email']) . "'><i class='bi bi-envelope-at-fill'>" . " " . htmlspecialchars($row['email']) . "</i></a><br>";
            echo "<a class='text-decoration-none ' style='color: white;' href='" . htmlspecialchars($row['line_url']) . "'><i class='bi bi-line'>" . " " . htmlspecialchars($row['line']) . "</i></a><br>";
            echo "</div>";

            echo "</div>";
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
            <?php $sql = 'select * from footer_links';
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc()
                ?>
            <h4 class='text-center'>SOCIAL</h4>

            <ul class='d-flex justify-content-evenly'>
                <il class='mx-2'>
                    <a href="<?= $row['facebook'] ?>" style="text-decoration: none; color:#339fff;"><i
                            class="bi bi-facebook fs-3"></i></a>
                    </li>
                    <il class='mx-2'>
                        <a href="<?= $row['tiktok'] ?>" style='text-decoration: none; color: #ffffff;'><i
                                class="bi bi-tiktok fs-3"></i></a></li>
                        <il class='mx-2'>
                            <a href="<?= $row['line'] ?>" style='text-decoration: none;color: #00f31e;'><i
                                    class="bi bi-line fs-3"></i></a></li>
                            <il class='mx-2'>
                                <a href="<?= $row['youtube'] ?>" style='text-decoration: none; color: #f60505;'><i
                                        class="bi bi-youtube fs-3"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
<div class="copyright py-4 text-center text-white">
    <div class="container"><small>บริษัท วันน์สยาม จำกัด และในเครือ
            ที่อยู่บริษัท 125 (สำนักงานาใหญ่) ถ.ศรีนครินทร์ แขวงบางนาใต้ เขตบางนา กรุงเทพฯลฯ 10260</small></div>
</div>
</html>
