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
    $sql = "SELECT *, COALESCE(parent_id, 0) AS parent_id FROM navbar ORDER BY parent_id ASC";
    $result = $conn->query($sql);

    $menus = [];
    while ($row = $result->fetch_assoc()) {
        $parentId = $row['parent_id'];
        $menus[$parentId][] = $row;
    }

    echo "<nav class='navbar navbar-expand-lg bg-secondary text-uppercase fixed-top' id='mainNav'>";
    echo "<div class='container'>";
    echo "<a href='#'>";

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

    if (isset($menus[0])) {
        foreach ($menus[0] as $row) {
            echo "<li class='nav-item mx-0 mx-lg-1 dropdown'>";
            echo "<a class='nav-link py-3 px-0 px-lg-3 rounded' href='#' data-bs-toggle='dropdown'>" . htmlspecialchars($row['name']) . "</a>";

            if (isset($menus[$row['id']])) {
                echo "<ul class='dropdown-menu'>";
                foreach ($menus[$row['id']] as $submenu) {
                    echo "<li><a class='dropdown-item' href='#'>" . htmlspecialchars($submenu['name']) . "</a></li>";
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

    <?php
    include("db.php");
    // ดึงข้อมูลรูปแบนเนอร์จากฐานข้อมูล
    $sql = "SELECT * FROM header_images";
    $result = mysqli_query($conn, $sql);
    ?>

    <header id="Home">
        <div class="slider-container">
            <div class="slider">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $imagePath = 'admin/img/header/' . $row['img'];
                    echo '<img src="' . $imagePath . '" alt="header Image">';
                }
                ?>
            </div>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
        </div>

    </header>

    <script>
        // JavaScript สำหรับเลื่อนภาพ slider
        let currentIndex = 0;

        function moveSlide(direction) {
            const slider = document.querySelector('.slider');
            const slides = document.querySelectorAll('.slider img');
            const totalSlides = slides.length;

            currentIndex += direction;

            if (currentIndex >= totalSlides) {
                currentIndex = 0;
            } else if (currentIndex < 0) {
                currentIndex = totalSlides - 1;
            }

            const offset = -currentIndex * 100; // คำนวณตำแหน่งที่ต้องเลื่อน
            slider.style.transform = `translateX(${offset}%)`;
        }

        // เลื่อนอัตโนมัติทุก 10 วินาที
        setInterval(() => {
            moveSlide(1);
        }, 10000); //เปลี่ยนวินาที
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
                            <img class="photo1" src="admin/<?php echo htmlspecialchars($first_image); ?>"
                                alt="ภาพบทความ">

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
</body>
<footer >
    <div class="d-flex justify-content-around align-items-start mt-5">
        <div>
            <div class="text-center">
            <h3>LOCATION</h3>
                <?php
                    $sql = "SELECT * FROM address";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                ?>
                
                <p>ที่อยู่บริษัท <?= $row['homeNumber'] ?> <?= $row['street'] ?> แขวง<?=$row['subDistrict'] ?> เขต<?= $row['district'] ?></p>
                <p><?=$row['province'] ?>, <?=$row['postalCode'] ?></p>
                <?php
                    } else {
                        echo "<p>ไม่มีข้อมูลที่อยู่</p>";
                    }
                ?>
                </div>
                <div class="text-center">
                    <h3>ABOUT US</h3>
                    <p class="text-center">Lorem ipsum dolor sit amet consectetur, </p>
                </div>
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
        
        <div class='ms-5'>
            <div >
                <div class="text-center">
                    <h3>AROUD THE WEB</h3>
                    <ul>
                        <il><a href='##'><i class="bi bi-facebook fs-2" ></i></a></li>
                        <il><a href='##'><i class="bi bi-twitter-x fs-2"></i></a></li>
                        <il><a href='##'></a></li>
                        <il><a href='##'></a></li>
                    </ul>
                </div>
                <div class="text-center">
                    <h3>SOCIAL</h3>
                    <ul>
                        <il><a href='##'><i class="bi bi-facebook fs-3 "></i> สยามรู้ดี ผู้เชี่ยวชาญอันดับ1เรื่องตะแกรงฉีก</a></li>
                        <br>
                        <il><a href='##'><i class="bi bi-tiktok fs-3"></i>ONE SIAM</a></li>
                    </ul>
            </div>
        </div>  
    </div> 
</footer>

</html>