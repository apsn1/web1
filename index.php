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

    <link rel="icon" type="image/x-icon" href="/images/logoOld1.png">
    <!-- เพิ่มไฟล์ CSS ของ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

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
</head>

<body id="page-top">

<?php
$sql = "SELECT * FROM navbar ORDER BY parent_id ASC";  // ดึงข้อมูลเมนูทั้งหมดจากฐานข้อมูล
$result = $conn->query($sql);

echo "<nav class='navbar navbar-expand-lg bg-secondary text-uppercase fixed-top' id='mainNav'>";
echo "<div class='container'>";
echo "<a href='#'>";

// แสดงรูปภาพจากโฟลเดอร์ uploads
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
            echo "<img src='$directory$image' alt='รูปภาพล่าสุด' style='height: 75px; width: 97px; margin-right: 50px;'>";
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

echo "<button class='navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded' type='button' data-bs-toggle='collapse' data-bs-target='#navbarResponsive' aria-controls='navbarResponsive' aria-expanded='false' aria-label='Toggle navigation'> Menu <i class='fas fa-bars'></i></button>";

echo "<div class='collapse navbar-collapse' id='navbarResponsive'>";
echo "<ul class='navbar-nav ms-auto'>";

// อาร์เรย์เก็บเมนูจากฐานข้อมูล
$menus = [];
while ($row = $result->fetch_assoc()) {
    $menus[$row['parent_id']][] = $row;  // จัดเก็บเมนูตาม parent_id
}

// แสดงเมนูหลัก (ที่ไม่มี parent_id หรือ parent_id เป็น 0)
if (isset($menus[0]) || isset($menus[NULL])) {  // เพิ่มเงื่อนไขเพื่อรองรับกรณี parent_id เป็น NULL
    foreach ($menus[0] ?? $menus[NULL] as $row) {  // ใช้ค่านี้เมื่อไม่มี parent_id
        echo "<li class='nav-item mx-0 mx-lg-1 dropdown'>";
        echo "<a class='nav-link py-3 px-0 px-lg-3 rounded' href='#' data-bs-toggle='dropdown'>" . $row['name'] . "</a>";

        // หากมีเมนูย่อย (ที่มี parent_id ตรงกับ id ของเมนูหลัก)
        if (isset($menus[$row['id']])) {
            echo "<ul class='dropdown-menu'>";
            foreach ($menus[$row['id']] as $submenu) {
                echo "<li><a class='dropdown-item' href='#'>" . $submenu['name'] . "</a></li>";
            }
            echo "</ul>";
        }

        echo "</li>";
    }
} else {
    echo "<li><a href='#'>ไม่มีเมนู</a></li>";
}

echo "</ul>";
echo "</div>";
echo "</nav>";
?>

    <!-- ลิงก์ไปยัง admin.php -->


    <!-------------------------------------------------------------------------------------------------------------->
    <header class="masthead bg-primary text-white text-center" id="Home">
        <img src="admin/img/banner/ปกเว็ปสีน้ำเงิน.jpg"
            style="width: 100%; height: auto; display: block; margin: 0;"></img>
        <div class=" container d-flex align-items-center flex-column" id="Home">

        </div>


    </header>

    <!--------------------------------------------------------------------------------------------------------------->

    <div class="Colum15year">

        <?php
        $sql = "SELECT * FROM content";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='Colum1'>";
                echo "<h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['body'] . "</p>";
                echo "<div class='action'><a href='admin/delete_content.php?del=" . $row['contentID'] . "'>ลบ</a></div>";
                echo "<td> <div class='action'><a href='admin/update_content.php?edit=" . $row['contentID'] . "'>แก้ไขข้อมูล</a></div>" . "</td>";
                echo "</div>";
            }
        } else {
            echo "<div>ไม่มีข้อมูล</div>";
        }
        ?>
    </div>
    <?php
    $sql = "SELECT * FROM videos";
    $result = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // ตรวจสอบว่ามีลิงก์วิดีโอ
            $currentVideo = $row['video_link'];
            if ($currentVideo) {
                echo "<div class='video-container'>";
                echo "<div class='video'>";
                echo "<iframe src='" . htmlspecialchars($currentVideo) . "' frameborder='0' allowfullscreen></iframe>";
                echo "</div>";
                echo "</div>";

                echo "<div class='Textdownvideo'>";
                echo "<div class='TextVideo' style='margin-top: 10px; margin-bottom: 20px; text-align: center; font-size: 22px;'>";
                echo "<a class='nav-link py-3 px-0 px-lg-3 rounded'>" . $row['video_title'] . "</a>";
                echo "</div>";
                echo "</div>";

            }
        }
    } else {
        echo "<div>ไม่มีข้อมูล</div>";
    }

    ?>

    </div>
</body>

</html>