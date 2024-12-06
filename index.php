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

<body>

    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
        <div class="container">
            <a href="############################################">
                <img class="logoinbanner" src="admin/img/logo/logoOld1.png"
                    style="height: 75px; width: 97px; margin-right: 50px;" alt="Logo"></a>
            <!-- <a class="navbar-brand" href="#page-top">One Siame</a>-->
            <button class="navbar-toggler text-uppercase font-weight-bold bg-primary1 text-white rounded" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive"
                aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin/admin_panel.php">จัดการข้อมูล</a> 
                        <!-- ลิงก์ไปยัง admin.php -->
                    </li>
                </ul>
            </div>
        </div>

    </nav>

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
    <div class="from_on_top">
        <h1>ข้อมูลในหน้าเว็บ</h1>
        <a href="admin/admin_panel.php">สร้างโพส</a>
    </div>
    <?php
    $sql = "SELECT * FROM content";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<a href='admin/edit_content.php'> edit </a>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<p>" . $row['body'] . "</p>";
        }
    } else {
        echo "ไม่มีข้อมูล";
    }
    ?>


</body>

</html>