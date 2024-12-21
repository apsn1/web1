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
</head>

<body id="page-top">

    <?php
    $sql = "SELECT navbar.*, filemanager.filename 
        FROM navbar 
        LEFT JOIN filemanager ON navbar.link_to = filemanager.id 
        ORDER BY navbar.parent_id ASC";

    $result = $conn->query($sql);

    $menus = [];
    while ($row = $result->fetch_assoc()) {
        $parentId = $row['parent_id'] ? $row['parent_id'] : 0; // ตรวจสอบ parent_id ถ้าเป็น NULL ให้ใช้ 0
        $menus[$parentId][] = $row;
    }

    echo "<nav class='navbar navbar-expand-lg bg-secondary text-uppercase fixed-top' id='mainNav'>";
    echo "<div class='container'>";
    echo "<a href='#'>";

    // เช็คและแสดงรูปภาพจากโฟลเดอร์ uploads
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

    // สร้างเมนูหลัก
    if (isset($menus[0])) {
        foreach ($menus[0] as $row) {
            echo "<li class='nav-item mx-0 mx-lg-1 dropdown'>";

            // เชื่อมโยงกับลิงค์ที่มาจาก link_to ซึ่งเป็นชื่อไฟล์
            $filename = "Allpage/" . htmlspecialchars($row['link_to']); // ใช้ link_to แทน filename
            echo "<a class='nav-link py-3 px-0 px-lg-3 rounded' href='" . $filename . "'>" . htmlspecialchars($row['name']) . "</a>";

            // ตรวจสอบเมนูย่อย
            if (isset($menus[$row['id']])) {
                echo "<ul class='dropdown-menu'>";
                foreach ($menus[$row['id']] as $submenu) {
                    // ลิงค์ของเมนูย่อย
                    $submenu_link = "Allpage/" . htmlspecialchars($submenu['link_to']); // ใช้ link_to แทน filename
                    echo "<li><a class='dropdown-item' href='" . $submenu_link . "'>" . htmlspecialchars($submenu['name']) . "</a></li>";
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
    <?php
    include("db.php");
    // ดึงข้อมูลรูปแบนเนอร์จากฐานข้อมูล
    $sql = "SELECT * FROM header_images";
    $result = mysqli_query($conn, $sql);
    ?>

    <header id="Home" class="custom-headerbanner text-center my-4">
        <?php
        include("db.php");

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
            } ?>

            <div class="custom-position-relative" style="padding-top: 0px; padding-bottom: 0px;">
                <div class="custom-banner-container">
                    <img id="bannerImage" src="<?php echo $images[0]; ?>" style="width:100%; ">
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


            <?php echo "<script>const images = " . json_encode($images) . ";</script>";
        } else {
            echo "<p class='text-danger'>ไม่มีข้อมูลแบนเนอร์</p>";
        }
        ?>
    </header>

    <script>
        // JavaScript function to change the image
        function changeImageById(index) {
            const bannerImage = document.getElementById('bannerImage');
            bannerImage.src = images[index]; // Change the image source
        }
    </script>


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
    <div class="videoBar">
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

    <section class="page-section" id="project">
        <h2 class="Project" style="margin-bottom:40px; text-align: center;">
            การสร้างออกเเบบวันน์สยามเป็นอย่างไร
        </h2>
        <div class="containerPj" style="display:flex; flex-wrap:wrap;">
            <?php
            include('db.php'); // เชื่อมต่อฐานข้อมูล
            
            $sql = "SELECT * FROM imgdesign";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="imgContainerpj" style="margin:5px; ">';
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
        <section class="page-section bg-primary2 text-white mb-0" id="about">
            <div class="container">
                <div class="row">
                    <?php
                    include('db.php'); // เชื่อมต่อฐานข้อมูล
                    
                    $sql = "SELECT * FROM about";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc(); // ดึงข้อมูลแถวแรกเก็บใน $row
                        echo "<div class='col-lg-4 ms-auto'>";
                        echo "<h4>องค์กร บริษัท วันน์สยาม จำกัด</h4>";
                        echo "<p>" . htmlspecialchars($row['onesiamText'] ?? 'ไม่มีข้อมูล') . "</p>";
                        echo "</div>";

                        echo "<div class='col-lg-4 me-auto'>";
                        echo "<h4>เกี่ยวกับบริษัท</h4>";
                        echo "<p>" . htmlspecialchars($row['aboutText'] ?? 'ไม่มีข้อมูล') . "</p>";
                        echo "</div>";
                    } else {
                        echo "<div class='col-lg-4 ms-auto'>";
                        echo "<h4>องค์กร บริษัท วันน์สยาม จำกัด</h4>";
                        echo "<p>ข้อมูลยังไม่มี</p>";
                        echo "</div>";

                        echo "<div class='col-lg-4 me-auto'>";
                        echo "<h4>เกี่ยวกับบริษัท</h4>";
                        echo "<p>ข้อมูลยังไม่มี</p>";
                        echo "</div>";
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
        <section class="phoneandidline">
            <h3>ติดต่อสอบถามเพิ่มเติมได้ที่</h3>
            <?php
            include('db.php'); // เชื่อมต่อฐานข้อมูล
            
            $sql = "SELECT * FROM minicontacts WHERE type = 'phone'";
            $resultPhone = $conn->query($sql);

            $sql = "SELECT * FROM minicontacts WHERE type = 'line'";
            $resultLine = $conn->query($sql);
            echo "<div class='contactsall'>";
            if ($resultPhone->num_rows > 0) {
                while ($row = $resultPhone->fetch_assoc()) {
                    echo "<div class='contactphone'>";
                    echo "<p>ข้อมูลโทรศัพท์: " . htmlspecialchars($row['value']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>ไม่มีข้อมูลโทรศัพท์</p>";
            }

            if ($resultLine->num_rows > 0) {
                while ($row = $resultLine->fetch_assoc()) {
                    echo "<div class='contactline'>";
                    echo "<p>ข้อมูลไลน์: " . htmlspecialchars($row['value']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo '<p>ไม่มีข้อมูลไลน์</p>';
            }
            echo "</div>";
            $conn->close();
            ?>

        </section>


        <section class="page-section" id="project">
            <h2 class="Project" style="margin-bottom :70px; text-align: center;">
                Projects โปรเจกต์ออกแบบหน้ากากอาคารและลูกค้าของเรา
            </h2>
            <div class="containerPj">
                <?php
                include('db.php'); // เชื่อมต่อฐานข้อมูล
                
                $sql = "SELECT * FROM projects";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="imgContainerpj">';
                        echo '<img class="photo1" src="admin/' . $row['image_path'] . '" alt="' . htmlspecialchars($row['alt_text']) . '"></a>';
                        echo '<button onclick="deleteImage(' . $row['id'] . ')">ลบ</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>ไม่มีโปรเจกต์ที่แสดง</p>';
                }
                ?>
            </div>
        </section>
        <section class="page-section" id="Blog">
            <div class="huakor" style="margin-left: 80px; margin-bottom: 50px; text-align: left;">
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



    </div>
</body>
<footer class="footer text-center">
    <div class="d-flex justify-content-around align-items-start mt-5 text-white p-5">
        <div>
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
            </div>
            <div class="text-center">

                <h3>ABOUT US</h3>
                <?php
                $sql = "SELECT * FROM textabout";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    ?>

                    <p><?= $row['b1'] ?></p>
                    <p><?= $row['b2'] ?></p>
                    <p><?= $row['b3'] ?></p>
                    <p><?= $row['b4'] ?></p>
                    <p><?= $row['b5'] ?></p>
                    <p><?= $row['b6'] ?></p>
                    <?php
                } else {
                    echo "<p>ไม่มีข้อมูลที่อยู่</p>";
                }
                ?>
            </div>
        </div>

        <div class='ms-5'>
            <?php $sql = 'select * from footer_links';
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc()
                ?>
            <div>
                <div class="text-center">
                    <h3>AROUD THE WEB</h3>
                    <ul>
                        <il><a href="<?= $row['facebook'] ?>"><i class="bi bi-facebook fs-2"></i></a></li>
                    </ul>
                </div>
                <div class="text-center">
                    <h3>SOCIAL</h3>
                    <ul>
                        <il>
                            <a href="<?= $row['facebook'] ?>" style='text-decoration: none;'><i
                                    class="bi bi-facebook fs-3"></i> สยามรู้ดีผู้เชี่ยวชาญอันดับ1เรื่องตะแกรงฉีก</a>
                            </li>
                            <br>
                            <il><a href="<?= $row['tiktok'] ?>" style='text-decoration: none;'><i
                                        class="bi bi-tiktok fs-3"></i>ONE SIAM</a></li>
                                <br>
                                <il><a href="<?= $row['line'] ?>" style='text-decoration: none;'><i
                                            class="bi bi-line fs-3"></i>ONE SIAM</a></li>
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