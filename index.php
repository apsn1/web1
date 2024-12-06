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
    $sql = "SELECT * FROM navbar";
    $result = $conn->query($sql);
    echo "<nav class='navbar navbar-expand-lg bg-secondary text-uppercase fixed-top' id='mainNav'>";
    echo "<div class='container'>";
    echo "<a href='############################################'>";
    echo "<img class='logoinbanner' src='admin/img/logo/logoOld1.png'style='height: 75px; width: 97px; margin-right: 50px;' alt='Logo'>";
    echo "</a>";
    echo "<button class='navbar-toggler text-uppercase font-weight-bold bg-primary1 text-white rounded' type='button'data-bs-toggle='collapse' data-bs-target='#navbarResponsive' aria-controls='navbarResponsive'aria-expanded='false' aria-label='Toggle navigation'> Menu";
    echo "<i class='fas fa-bars'>";
    echo "</i>";
    echo "</button>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='collapse navbar-collapse' id='navbarResponsive'>";
            echo "<ul class='navbar-nav ms-auto'>";
            echo "<li class='nav-item mx-0 mx-lg-1'>";
            echo "<a class='nav-link py-3 px-0 px-lg-3 rounded'>" . $row['name'] . "</a>";
            echo "</li>";
            echo "</ul>";
            echo "</div>";
        }
    } else {
        echo "<li><a href='#'>ไม่มีเมนู</a></li>";
    }
    echo "<a class='nav-link' href='admin/admin_panel.php'>จัดการข้อมูล</a>";
    echo "</nav>";
    echo "</div>";

    ?>
    
    <!-- ลิงก์ไปยัง admin.php -->


    <!-------------------------------------------------------------------------------------------------------------->
    <header class="masthead bg-primary text-white text-center" id="Home" style="
  background-image: url('admin/img/banner/ปกเว็ปสีน้ำเงิน.jpg'); /* ลิงก์ไปยังภาพ */
  background-size: cover; /* ปรับขนาดให้เต็มจอ */
  background-position: center; /* จัดตำแหน่งให้กึ่งกลาง */
  background-repeat: no-repeat; /* ไม่ให้ภาพซ้ำ */
  height: 120vh; /* ความสูงเต็มจอ */
  width: 220vh;
  padding: 0; /* ลบระยะ padding */
  padding-top: 20px;
">
        <div class="container d-flex align-items-center flex-column" id="Home">

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
                echo "</div>";
            }
        } else {
            echo "<div>ไม่มีข้อมูล</div>";
        }
        ?>

        <div class="from_on_top">
            <a href="admin/admin_panel.php">สร้างโพส + </a>
        </div>
    </div>

</body>

</html>