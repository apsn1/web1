<?php include('db.php'); ?>
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
        echo '<link rel="icon" type="image/x-icon" href=" ' . $folderPath . $latestFile . '">';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (สำหรับไอคอน) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Bootstrap JS Bundle (รวม Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
        ['id' => 1, 'name' => 'หน้าหลัก', 'link' => 'index.php'],
        ['id' => 2, 'name' => 'เกี่ยวกับเรา', 'link' => 'about.php'],
        ['id' => 3, 'name' => 'สินค้า', 'link' => 'show_product.php'],
        ['id' => 4, 'name' => 'โปรเจค', 'link' => 'projects.php'],
        ['id' => 5, 'name' => 'โซเชียล', 'link' => 'social.all.php'],
        ['id' => 6, 'name' => 'บทความ', 'link' => 'articles.php'],
        ['id' => 7, 'name' => 'ติดต่อเรา', 'link' => 'contact.php']
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

    <?php
    include("db.php");
    // ดึงข้อมูลรูปแบนเนอร์จากฐานข้อมูล
    $sql = "SELECT * FROM header_images LIMIT 4";
    $result = mysqli_query($conn, $sql);

    // Check if data exists
    if ($result->num_rows > 0) {
        // Initialize arrays to store image paths and button text
        $images = [];
        $buttons = [];

        while ($rows = $result->fetch_assoc()) {
            $images[] = 'admin/img/header/' . $rows['img'];
            $buttons[] = $rows['button'];
        }
    }
    ?>

    <header id="Home" class="custom-headerbanner text-center ">
        <div class="custom-position-relative" style="padding-top: 0px; padding-bottom: 0px;">
            <div class="custom-banner-container">
                <img id="bannerImage" src="<?php echo $images[0]; ?>" style="width:100%;">
            </div>
            <!-- ปุ่ม (วางซ้อนบนภาพ) -->
            <div class="custom-position-absolute custom-button-container">
                <?php foreach ($buttons as $index => $buttonText): ?>
                    <button class="custom-btn custom-btn-outline-warning" onclick="changeImageById(<?php echo $index; ?>)">
                        <?php echo $buttonText; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!empty($images)): ?>
            <script>
                const images = <?php echo json_encode($images); ?>;
                let currentIndex = 0;
                const bannerImage = document.getElementById('bannerImage');

                // Function to change image by index
                function changeImageById(index) {
                    currentIndex = index;
                    bannerImage.src = images[currentIndex];
                }

                // Auto Slide Function
                function autoSlide() {
                    currentIndex = (currentIndex + 1) % images.length; // Loop back to the first image
                    bannerImage.src = images[currentIndex];
                }

                // Set Auto Slide Interval (5 seconds)
                setInterval(autoSlide, 3000);
            </script>
        <?php else: ?>
            <p class='text-danger'>ไม่มีข้อมูลแบนเนอร์</p>
        <?php endif; ?>
    </header>
    <!------------------------------------------------------------------------------------------------->



    <div class="Colum15year">

        <?php
        $sql = "SELECT * FROM content";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='Colum1'>";
                echo "<h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['body'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<div>ไม่มีข้อมูล</div>";
        }
        ?>
    </div>

    <?php
    // -- ส่วนดึงข้อมูลจากฐานข้อมูล --
    // สมมติ $conn คือ connection ที่เชื่อมต่อฐานข้อมูลได้เรียบร้อย
    $sql = "SELECT * FROM videos";
    $result = $conn->query($sql);
    ?>

    <div class="videoBar" style="margin-top: 50px;">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $currentVideo = $row['video_link'];
                if ($currentVideo) {
                    // ------------------------------
                    // สกัด YouTube ID
                    // ------------------------------
                    $youtubeId = "";
                    if (strpos($currentVideo, "watch?v=") !== false) {
                        // ตัวอย่าง: https://www.youtube.com/watch?v=xxxxxxx
                        $parts = explode("watch?v=", $currentVideo);
                        $youtubeIdParts = explode("&", $parts[1]);
                        $youtubeId = $youtubeIdParts[0];
                    } elseif (strpos($currentVideo, "youtu.be/") !== false) {
                        // ตัวอย่าง: https://youtu.be/xxxxxxx
                        $parts = explode("youtu.be/", $currentVideo);
                        $youtubeIdParts = explode("?", $parts[1]);
                        $youtubeId = $youtubeIdParts[0];
                    } elseif (strpos($currentVideo, "youtube.com/embed/") !== false) {
                        // ตัวอย่าง: https://www.youtube.com/embed/xxxxxxx
                        $parts = explode("youtube.com/embed/", $currentVideo);
                        $youtubeIdParts = explode("?", $parts[1]);
                        $youtubeId = $youtubeIdParts[0];
                    }

                    // ------------------------------
                    // ถ้าได้ YouTube ID => สร้าง URL Thumbnail
                    // ------------------------------
                    if (!empty($youtubeId)) {
                        $thumbnailUrl = "https://img.youtube.com/vi/" . $youtubeId . "/0.jpg";

                        // -- แสดง Thumbnail แบบเต็มพื้นที่ เป็น background --
                        echo "<div class='video-container' "
                            . "     style='background-image: url(\"" . htmlspecialchars($thumbnailUrl) . "\");' "
                            . "     onclick='openFullVideo(\"" . htmlspecialchars($currentVideo) . "\");'>";

                        echo "  <div class='play-button'></div>";

                        echo "</div>";
                    }
                    // ถ้าไม่ใช่ลิงก์ YouTube (หรือสกัด ID ไม่ได้)
                    // อาจกำหนดรูป placeholder หรือไฟล์ mp4 แล้วแต่ต้องการ
                    else {
                        echo "<div>ไม่ใช่ลิงก์ YouTube หรือรูปแบบไม่ถูกต้อง</div>";
                    }
                }
            }
        } else {
            echo "<div>ไม่มีข้อมูล</div>";
        }
        ?>
    </div>

    <!-- Popup (Overlay) สำหรับแสดงวิดีโอแบบเต็ม -->
    <div id="videoPopup" style="
    display:none; 
    position:fixed; 
    z-index:9999; 
    top:0; 
    left:0; 
    width:100%; 
    height:100%; 
    background-color: rgba(0,0,0,0.7); 
    ">
        <!-- กล่องวิดีโอกลางจอ -->
        <div style="
        position:absolute; 
        top:50%; 
        left:50%; 
        transform: translate(-50%, -50%);
        width: 80%;
        max-width: 800px;
        background: rgba(0,0,0,0.5); 
        padding: 20px;
        box-sizing: border-box;
        border-radius: 8px;">
            <!-- ปุ่มปิด Popup -->
            <div style="text-align:right;">
                <button onclick="closeFullVideo()" style="
                    cursor:pointer; 
                    background: #f00; 
                    color: #fff;
                    border: none; 
                    border-radius: 4px; 
                    padding: 8px 12px;
                ">
                    ปิด ✕
                </button>
            </div>

            <!-- iframe สำหรับแสดงวิดีโอตัวเต็ม -->
            <iframe id="popupIframe" width="100%" height="450px" frameborder="0" allowfullscreen
                style="background:#000;">
            </iframe>
        </div>
    </div>

    <!-- ส่วน JavaScript สำหรับจัดการ Popup & autoplay -->
    <script>
        function openFullVideo(videoUrl) {
            const popup = document.getElementById('videoPopup');
            const iframe = document.getElementById('popupIframe');

            let youtubeId = "";

            // ตรวจสอบรูปแบบลิงก์ YouTube
            if (videoUrl.includes("youtube.com/watch?v=")) {
                youtubeId = videoUrl.split("watch?v=")[1].split("&")[0];
            } else if (videoUrl.includes("youtu.be/")) {
                youtubeId = videoUrl.split("youtu.be/")[1].split("?")[0];
            } else if (videoUrl.includes("youtube.com/embed/")) {
                youtubeId = videoUrl.split("youtube.com/embed/")[1].split("?")[0];
            }

            if (youtubeId) {
                // เพิ่ม autoplay=1 (และ mute=1 หากต้องการเริ่มแบบปิดเสียง)
                const embedUrl = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
                iframe.src = embedUrl;
            } else {
                // กรณีไม่ใช่ลิงก์ YouTube
                iframe.src = videoUrl;
            }

            popup.style.display = 'block';
        }

        function closeFullVideo() {
            const popup = document.getElementById('videoPopup');
            const iframe = document.getElementById('popupIframe');
            popup.style.display = 'none';

            // ล้าง src เพื่อหยุดวิดีโอ
            iframe.src = "";
        }
    </script>
    <br>
    <br>
    <section class="page-section" id="project" style="
    padding-top: 0px;
    padding-bottom: 0px;
">
        <h2 class="Project" style="margin-bottom:40px; text-align: center;">
            การสร้างออกเเบบวันน์สยามเป็นอย่างไร
        </h2>
        <div class="containerPj" style="display:flex;flex-wrap:wrap;margin-bottom: 10px;">
            <?php
            include('db.php'); // เชื่อมต่อฐานข้อมูล
            
            $sql = "SELECT * FROM imgdesign";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="imgContainerpj">';
                    echo '<img class="photo1" src="admin/' . $row['image_path'] . '" style="width:maxpx;">';
                    echo '</div>';
                }
            } else {
                echo '<p>ไม่มีโปรเจกต์ที่แสดง</p>';
            }
            ?>
        </div>
    </section>



    <div class="about">
        <section class="page-section bg-primary2 text-white mb-0" style="
    padding-top: 50px;
    padding-bottom: 50px; " id="about">
            <div class="container">
                <div class="row">
                    <?php
                    include('db.php'); // เชื่อมต่อฐานข้อมูล
                    
                    $sql = "SELECT * FROM about";
                    $result = $conn->query($sql);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        ?>

                        <p> <?= $row['abouthead'] ?> <br>
                            <?= $row['abouttitle'] ?> </p>
                        <?php
                    } else {
                        echo "<p>ไม่มีข้อมูลที่อยู่</p>";
                    }
                    ?>
                    <!-- About Section Button-->
                    <div class="text-center mt-4">
                        <a class="btn btn-xl btn-outline-light" href="/templates/page4.html">อ่านเพิ่มเติม
                        </a>
                    </div>
                </div>
            </div>

        </section>
        <br>
        <br>
        <section class="page-section" id="project">
            <h2 class="Project" style="margin-bottom :70px; text-align: center;">
                Projects โปรเจกต์ออกแบบหน้ากากอาคารและลูกค้าของเรา
            </h2>
            <div class="containerPj" style="display:flex;flex-wrap:wrap;margin-bottom: 10px;">
                <?php
                include('db.php'); // เชื่อมต่อฐานข้อมูล
                
                $sql = "SELECT * FROM projects";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="imgContainerpj1">';
                        echo '<img class="photo1" src="admin/' . $row['image_path'] . '" alt="' . htmlspecialchars($row['alt_text']) . '"></a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>ไม่มีโปรเจกต์ที่แสดง</p>';
                }
                ?>
            </div>
        </section>
        <section class="page-section" id="Blog">
            <div class="huakor">
                <h1>- Architecting Legacy : Stability and Wealth Through Design</h1><br>
                <h2>บทความ</h2>
            </div>
            <div class="อ่านเพิ่มเติม" style="margin-right: 170px; margin-bottom: 50px; font-size: 22px;">
                <a href="/templates/page5.html">อ่านเพิ่มเติม</a>
            </div>
            <div class="allblog">
                <?php
                include('db.php'); // เชื่อมต่อฐานข้อมูล
                
                // ดึงข้อมูลบทความทั้งหมดจากฐานข้อมูล
                $sql = "SELECT * FROM blogs ORDER BY id DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // แปลง JSON ของรูปภาพกลับมาเป็น array
                        $images = json_decode($row['images'], true);

                        // รูปภาพแรก (ถ้ามี)
                        $first_image = isset($images[0]) ? $images[0] : 'default.jpg'; // ใช้รูป default หากไม่มีรูปภาพ
                        ?>
                        <div class="blog-card">
                            <!-- แสดงรูปภาพ -->
                            <img class="photo1" src="admin/<?php echo htmlspecialchars($first_image); ?>" alt="ภาพบทความ"></img>
                            <!-- แสดงหัวข้อ -->
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                            <!-- แสดงคำอธิบาย (ตัดคำที่ 150 ตัวอักษร) -->
                            <p>
                                <?php echo htmlspecialchars(mb_substr($row['description'], 0, 150)) . '...'; ?>
                            </p>

                            <!-- ปุ่มอ่านเพิ่ม -->
                            <a class="btn-read-more" href="/templates/page5.html?id=<?php echo $row['id']; ?>">อ่านเพิ่ม</a>
                        </div>
                        <?php

                    }
                } else {
                    echo "<p>ไม่มีบทความในขณะนี้</p>";
                }
                ?>
            </div>
        </section>
    </div>
    <script>
        // การจัดการพื้นหลัง Navbar
        document.addEventListener("DOMContentLoaded", () => {
            const navbar = document.getElementById("mainNav");

            window.addEventListener("scroll", () => {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

                if (currentScroll < 100) {
                    navbar.classList.add("transparent"); // พื้นหลังโปร่งใส
                } else {
                    navbar.classList.remove("transparent"); // พื้นหลังปกติ
                }
            });
        });

    </script>

    </div>
</body>
<footer class="footer text-center p-4 w-100" style="z-index: 1000;bottom: 0px; left: 0px;">
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
                            <a href="##?>" style='text-decoration: none;color: #00f31e;'><i
                                    class="bi bi-line fs-3"></i></a></li>
                            <il class='mx-2'>
                                <a href="<?= $row['youtube'] ?>" rel="nofollow noopener noreferrer" target="_blank"
                                    style="text-decoration: none; color: #f60505;">
                                    <i class="bi bi-youtube fs-3"></i>
                                </a>
                                </li>
                            </il>
                        </il>


            </ul>
        </div>
    </div>
</footer>
<div class="copyright py-4 text-center text-white">
    <div class="container"><small>บริษัท วันน์สยาม จำกัด และในเครือ
            ที่อยู่บริษัท 125 (สำนักงานาใหญ่) ถ.ศรีนครินทร์ แขวงบางนาใต้ เขตบางนา กรุงเทพฯลฯ 10260</small></div>
</div>

</html>