<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>one siameRudee</title>
    <!-- Favicon-->


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- เพิ่มไฟล์ JS ของ Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="CssForIndex/index_css.css" />
    <style>
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .lightbox.active {
            display: flex;
        }

        .lightbox-content {
            text-align: center;
            max-width: 80%;
        }

        .lightbox-navigation img {
            max-width: 60px;
            cursor: pointer;
        }

        .lightbox-footer {
            color: white;
            margin-top: 10px;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }
    </style>
    <title>SEO</title>
    <script src="scripts.js"></script>
</head>
< id="page-top">
    <?php
    // 1) กำหนดเมนูหลัก (Hard-coded) ในไฟล์เดียวกัน (ไม่ต้อง include admin_panel.php)
    $mainMenus = [
        ['id' => 1, 'name' => 'หน้าหลัก', 'link' => 'index.php'],
        ['id' => 2, 'name' => 'เกี่ยวกับเรา', 'link' => 'about.php'],
        ['id' => 3, 'name' => 'สินค้า', 'link' => 'products.php'],
        ['id' => 4, 'name' => 'โปรเจค', 'link' => 'projects.php'],
        ['id' => 5, 'name' => 'โซเชียล', 'link' => 'social.all.php'],
        ['id' => 6, 'name' => 'บทความ', 'link' => 'articles.php'],
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
                echo "<img src='{$directory}{$image}' 
                      alt='รูปภาพล่าสุด' 
                      '>";
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
    echo "<button class='navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded' 
      type='button' data-bs-toggle='collapse' data-bs-target='#navbarResponsive' 
      aria-controls='navbarResponsive' aria-expanded='false' 
      aria-label='Toggle navigation'>
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
            echo "<a class='nav-link dropdown-toggle py-3 ' href='{$mainLink}'>"
                . htmlspecialchars($mainName, ENT_QUOTES, 'UTF-8') . "</a>";
            echo "<ul class='dropdown-menu'>";
            foreach ($subMenus[$mainId] as $submenu) {
                $submenuLink = "Allpage/" . htmlspecialchars($submenu['link_to'], ENT_QUOTES, 'UTF-8');
                $submenuName = htmlspecialchars($submenu['name'], ENT_QUOTES, 'UTF-8');
                echo "<li><a class='dropdown-item' href='{$submenuLink}.php'>{$submenuName}</a></li>";
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
    <!------------------------------------------------------------------>
    <?php
    include('db.php'); // เชื่อมต่อฐานข้อมูล
    
    try {
        // คำสั่ง SQL เพื่อดึงข้อมูลสินค้าทั้งหมด
        $sql = "SELECT id, name, product_name, link_to_product, description_product, src_image, src_image_cover FROM product ORDER BY id ASC";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        if ($result->num_rows > 0) {
            // เก็บข้อมูลทั้งหมดในรูปแบบ Array
            while ($row = $result->fetch_assoc()) {
                $row['src_image'] = json_decode($row['src_image'], true); // แปลง JSON เป็น Array
                $products[] = $row;
            }
        } else {
            echo "No products found.";
        }

        $stmt->close();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
    ?>

    <!-- Lightbox HTML -->
    <h1>Product List</h1>
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0; text-align: center;">
                <!-- ชื่อสินค้า -->
                <h2 style="cursor: pointer;"
                    onclick="openLightbox(['<?= implode("','", $product['src_image']) ?>'], '<?= $product['src_image_cover'] ?>', '<?= htmlspecialchars($product['name']) ?>', '<?= htmlspecialchars($product['description_product']) ?>')">
                    <?= htmlspecialchars($product['name']) ?>
                </h2>
                <!-- ภาพหน้าปก -->
                <?php if (!empty($product['src_image_cover'])): ?>
                    <img src="Allpage/จัดการหน้าเว็บ/<?= htmlspecialchars($product['src_image_cover']) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 200px; cursor: pointer;"
                        onclick="openLightbox(['<?= implode("','", $product['src_image']) ?>'], '<?= $product['src_image_cover'] ?>', '<?= htmlspecialchars($product['name']) ?>', '<?= htmlspecialchars($product['description_product']) ?>')">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>

    <!-- Lightbox HTML -->
    <div id="lightbox"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center; z-index: 1000;">
        <div
            style="position: relative; text-align: center; max-width: 80%; background: white; padding: 20px; border-radius: 10px;">
            <span onclick="closeLightbox()"
                style="position: absolute; top: 20px; right: 20px; font-size: 24px; color: black; cursor: pointer;">&times;</span>
            <h2 id="lightbox-name" style="margin-bottom: 20px;"></h2>
            <div id="lightbox-carousel" style="display: flex; overflow-x: auto; gap: 10px;">
                <!-- Images will be appended here -->
            </div>
            <p id="lightbox-description" style="font-size: 16px; margin-top: 10px; color: black;"></p>
        </div>
    </div>

    <script>
        function openLightbox(images, cover, name, description) {
            const lightbox = document.getElementById('lightbox');
            const lightboxName = document.getElementById('lightbox-name');
            const lightboxDescription = document.getElementById('lightbox-description');
            const lightboxCarousel = document.getElementById('lightbox-carousel');

            // ตั้งค่าชื่อและคำอธิบาย
            lightboxName.textContent = name;
            lightboxDescription.textContent = description;

            // เคลียร์ภาพเก่าที่อยู่ใน Carousel
            lightboxCarousel.innerHTML = '';

            // เพิ่มภาพ Cover
            if (cover) {
                const coverImage = document.createElement('img');
                coverImage.src = "Allpage/จัดการหน้าเว็บ/" + cover;
                coverImage.alt = name;
                coverImage.style.maxWidth = '200px';
                coverImage.style.cursor = 'pointer';
                coverImage.style.margin = '5px';
                lightboxCarousel.appendChild(coverImage);
            }

            // เพิ่มภาพทั้งหมดใน images (รับมาเป็น Array)
            if (Array.isArray(images)) {
                images.forEach(image => {
                    const img = document.createElement('img');
                    img.src = "Allpage/จัดการหน้าเว็บ/" + image;
                    img.alt = name;
                    img.style.maxWidth = '200px';
                    img.style.cursor = 'pointer';
                    img.style.margin = '5px';
                    lightboxCarousel.appendChild(img);
                });
            }

            // แสดง Lightbox
            lightbox.style.display = 'flex';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.style.display = 'none';
        }

    </script>




    <!------------------------------------------------------------------>
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
                $sql = "SELECT * FROM contacts";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();

                echo "<div class='contactsall'>";

                echo "<div class='contactphone my-2 '>";
                echo "<i class='bi bi-telephone-fill'>" . " " . htmlspecialchars($row['phone']) . "</i><br>";
                echo "<i class='bi bi-line'>" . " " . htmlspecialchars($row['line']) . "</i><br>";
                echo "<i class='bi bi-envelope-at-fill'>" . " " . htmlspecialchars($row['email']) . "</i><br>";
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
                                    <a href="##" style='text-decoration: none; color: #f60505;'><i
                                            class="bi bi-youtube fs-3"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>

</html>