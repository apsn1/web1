<?php
include('../db.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content='โ'>
    <meta name='keywords' content='โ'>
    <title>โ</title>
    <style>
        [contenteditable] {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            min-height: 50px;
        }
        [contenteditable]:focus {
            outline: 2px solid #007bff;
        }
    </style>
</head>
<body>
<div id="notification-icon">
    <?php
    $directory = '../admin/img/logo/';
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
    <button id="scrollToTop">↑ ขึ้นบนสุด</button>

    <?php
    // กำหนดเมนูหลัก
    $mainMenus = [
        ['id' => 1, 'name' => 'หน้าหลัก'],
        ['id' => 2, 'name' => 'เกี่ยวกับเรา'],
        ['id' => 3, 'name' => 'บริการ'],
        ['id' => 4, 'name' => 'ติดต่อเรา']
    ];

    // แปลงค่าเมนูหลัก (id) เป็น array เพื่อใช้ใน Query
    $mainIds = array_column($mainMenus, 'id');
    $inClause = implode(',', $mainIds);

    // Query ดึงเมนูย่อยจากตาราง navbar
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

    // แสดงโลโก้
    echo "<a href='#'>";
    $directory = '../admin/uploads/';

echo "<a href='#'>";

if (is_dir($directory)) {
    // สแกนไฟล์ในโฟลเดอร์
    $files = scandir($directory);
    // ตัด . และ .. ออก
    $files = array_diff($files, array('.', '..'));

    // กรองให้เหลือไฟล์ภาพเท่านั้น
    $imageFiles = array_filter($files, function($file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        return in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
    });

    // หากเจอไฟล์ภาพอย่างน้อย 1 ไฟล์
    if (!empty($imageFiles)) {
        $latestImage = reset($imageFiles); // หยิบไฟล์แรกของ array
        echo "<img src='{$directory}{$latestImage}' alt='โลโก้' style='height: 75px; width: 97px; margin-right: 50px;'>";
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

    foreach ($mainMenus as $main) {
        $mainId = $main['id'];
        $mainName = htmlspecialchars($main['name']);

        if (isset($subMenus[$mainId])) {
            echo "<li class='nav-item dropdown mx-0 mx-lg-1'>";
            echo "<a class='nav-link dropdown-toggle py-3 px-0 px-lg-3 rounded' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>"
                . htmlspecialchars($mainName) . "</a>";
            echo "<ul class='dropdown-menu'>";
            foreach ($subMenus[$mainId] as $submenu) {
                $submenuLink = htmlspecialchars($submenu['link_to']);
                $submenuName = htmlspecialchars($submenu['name']);
                echo "<li><a class='dropdown-item' href='$submenuLink'>$submenuName</a></li>";
            }
            echo "</ul></li>";
        } else {
            echo "<li class='nav-item mx-0 mx-lg-1'><a class='nav-link py-3 px-0 px-lg-3 rounded' href='#'>"
                . htmlspecialchars($mainName) . "</a></li>";
        }
    }

    echo "</ul>";
    echo "</div></div></nav>";
    ?>
</div>

<header>
    <h1 >โฺฮโฺ </h1>
    <h2 >ฮฉฺฉฮฺ</h2>
</header>
<div class="content">
    <p >ฉฮฺฉฮฺฉ</p>
    <div class="left">
        <img src="จัดการหน้าเว็บ/images_all/462567104_2075481142880694_1501017219490596836_n.jpg" alt="Left Image" style="max-width: 300px;">
        <p >โฆฏโ</p>
    </div>
    <div class="right">
        <img src="จัดการหน้าเว็บ/images_all/462567104_2075481142880694_1501017219490596836_n.jpg" alt="Right Image" style="max-width: 300px;">
        <p >ฏฆโฏฆโ</p>
    </div>
</div>
<!-------เพิ่มโค๊ดข้างล่าง----------------->
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
                    <p>ที่อยู่บริษัท <?= $row['homeNumber'] ?> <?= $row['street'] ?> แขวง<?= $row['subDistrict'] ?>
                        เขต<?= $row['district'] ?></p>
                    <p><?= $row['province'] ?>, <?= $row['postalCode'] ?></p>
                    <?php
                } else {
                    echo "<p>ไม่มีข้อมูลที่อยู่</p>";
                }
                ?>
            </div>
            <div class="text-center">
                <h3>SOCIAL</h3>
                <?php
                $sql = 'SELECT * FROM footer_links';
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                ?>
                <ul>
                    <li><a href="<?= $row['facebook'] ?>"><i class="bi bi-facebook fs-3" style="color: #339fff;"></i> Facebook</a></li>
                    <li><a href="<?= $row['tiktok'] ?>"><i class="bi bi-tiktok fs-3" style="color: #ffffff;"></i> TikTok</a></li>
                    <li><a href="<?= $row['line'] ?>"><i class="bi bi-line fs-3" style="color: #00f31e;"></i> Line</a></li>
                    <li><a href="#"><i class="bi bi-youtube fs-3" style="color: #f60505;"></i> YouTube</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<div class="copyright py-4 text-center text-white">
    <div class="container">
        <small>บริษัท วันน์สยาม จำกัด และในเครือ ที่อยู่บริษัท 125 (สำนักงานาใหญ่) ถ.ศรีนครินทร์ แขวงบางนาใต้ เขตบางนา กรุงเทพฯลฯ 10260</small>
    </div>
</div>
</body>
</html>