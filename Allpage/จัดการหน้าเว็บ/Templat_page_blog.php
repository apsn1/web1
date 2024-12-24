<?php include('../../db.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>one siameRudee</title>
    <!-- Favicon-->

    <link rel="icon" type="image/x-icon" href="/images/logoOld1.png">
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
    <link rel="stylesheet" href="cssforpanel/page_blog.css">
    <title>หน้าเว็บหลัก</title>
    <script src="scripts.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (สำหรับไอคอน) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Bootstrap JS Bundle (รวม Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body id="page-top">

    <div id="notification-icon">
        <?php
        $directory = '../../admin/img/logo/';
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
        ['id' => 1, 'name' => 'หน้าหลัก'],
        ['id' => 2, 'name' => 'เกี่ยวกับเรา'],
        ['id' => 3, 'name' => 'บริการ'],
        ['id' => 4, 'name' => 'ติดต่อเรา']
    ];

    // 2) เชื่อมต่อฐานข้อมูล (db.php) ถ้ามี
    include('../../db.php');

    // 3) แปลงค่าเมนูหลัก (id) เป็น array เพื่อใช้ใน Query
    $mainIds = array_column($mainMenus, 'id'); // [1,2,3,4]
    $inClause = implode(',', $mainIds);        // "1,2,3,4"
    
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
    echo "<a href='#'>";

    // ตรวจสอบรูปภาพใน 'admin/uploads' (ถ้าไม่ใช้ ก็ลบส่วนนี้ออกได้)
    $directory = '../../admin/uploads/';
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
                echo "<img src='{$directory}{$image}' 
                      alt='รูปภาพล่าสุด' 
                      style='height: 75px; width: 97px; margin-right: 50px;'>";
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
    echo "<button class='navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded' 
      type='button' data-bs-toggle='collapse' data-bs-target='#navbarResponsive' 
      aria-controls='navbarResponsive' aria-expanded='false' 
      aria-label='Toggle navigation'>
      Menu <i class='fas fa-bars'></i>
      </button>";

    // 5.3 ส่วนเนื้อหาของ Navbar
    echo "<div class='collapse navbar-collapse' id='navbarResponsive'>";
    echo "<ul class='navbar-nav ms-auto'>";

    // 6) วนลูปสร้าง “เมนูหลัก” จาก $mainMenus
    foreach ($mainMenus as $main) {
        $mainId = $main['id'];
        $mainName = $main['name'];

        // ตรวจสอบว่ามีเมนูย่อยหรือไม่
        if (isset($subMenus[$mainId])) {
            // มีเมนูย่อย แสดงเป็น Dropdown
            echo "<li class='nav-item dropdown mx-0 mx-lg-1'>";
            echo "<a class='nav-link dropdown-toggle py-3 px-0 px-lg-3 rounded' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>"
                . htmlspecialchars($mainName) . "</a>";
            echo "<ul class='dropdown-menu'>";
            foreach ($subMenus[$mainId] as $submenu) {
                $submenuLink = "Allpage/" . htmlspecialchars($submenu['link_to']);
                $submenuName = htmlspecialchars($submenu['name']);
                echo "<li><a class='dropdown-item' href='{$submenuLink}'>{$submenuName}</a></li>";
            }
            echo "</ul>";
            echo "</li>";
        } else {
            // ไม่มีเมนูย่อย แสดงเป็นปกติ ไม่ใช่ Dropdown
            echo "<li class='nav-item mx-0 mx-lg-1'>";
            echo "<a class='nav-link py-3 px-0 px-lg-3 rounded' href='#'>"
                . htmlspecialchars($mainName) . "</a>";
            echo "</li>";
        }
    }
    echo "</nav>";

    ?>
    <!-------------------------------------------------------------------------------------------------------------------->
    <!-------------------------------------------------------------------------------------------------------------------->
    <!-------------------------------------------------------------------------------------------------------------------->


























    <!-------------------------------------------------------------------------------------------------------------------->
    <!-------------------------------------------------------------------------------------------------------------------->
    <!-------------------------------------------------------------------------------------------------------------------->

    </div>
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
    </div>
</body>
<footer class="footer text-center">
    <div class="d-flex justify-content-around align-items-start mt-5 text-white p-5">
        <div class="all-footer">
            <div class="text-center">
                <h3>LOCATION</h3>
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

                // ดึงข้อมูลทั้งหมด (SELECT)
                $sql = "SELECT * FROM messages ORDER BY id DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <title>หน้าแสดงข้อความทั้งหมด</title>
                <h1>About us</h1>
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
            </div>
            <div class="text-center">
                <!----------------------------------------------------------------------------------------------------------------------------------------->


                <!----------------------------------------------------------------------------------------------------------------------------------------->
                <div class='ms-5'>
                    <?php $sql = 'select * from footer_links';
                    $result = mysqli_query($conn, $sql);
                    $row = $result->fetch_assoc()
                        ?>
                    <div>
                        <div class="text-center">
                            <h3>AROUD THE WEB</h3>
                            <ul>
                                <il><a href="<?= $row['facebook'] ?>"><i class="bi bi-facebook fs-2"
                                            style='color: #339fff;'></i></a></li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <h3>SOCIAL</h3>
                            <ul>
                                <il>
                                    <a href="<?= $row['facebook'] ?>" style="text-decoration: none; color:
                                            #339fff;"><i class="bi bi-facebook fs-3"></i>
                                        สยามรู้ดีผู้เชี่ยวชาญอันดับ1เรื่องตะแกรงฉีก</a>
                                    </li>
                                    <br>
                                    <il><a href="<?= $row['tiktok'] ?>"
                                            style='text-decoration: none; color: #ffffff;'><i
                                                class="bi bi-tiktok fs-3"></i>ONE SIAM</a></li>
                                        <br>
                                        <il><a href="<?= $row['line'] ?>" style='text-decoration: none; #00f31e;'><i
                                                    class="bi bi-line fs-3"></i>ONE SIAM</a></li>
                                            <br>
                                            <il><a href="##" style='text-decoration: none; color: #f60505;'><i
                                                        class="bi bi-youtube fs-3"></i>ONE SIAM</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

</footer>
<div class="copyright py-4 text-center text-white">
    <div class="container"><small>บริษัท วันน์สยาม จำกัด และในเครือ
            ที่อยู่บริษัท 125 (สำนักงานาใหญ่) ถ.ศรีนครินทร์ แขวงบางนาใต้ เขตบางนา กรุงเทพฯลฯ 10260</small></div>
</div>

</html>