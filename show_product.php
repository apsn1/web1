<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>one siameRudee</title>
    <!-- Favicon-->

    
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    <!-- เพิ่มไฟล์ JS ของ Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script
      src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
      crossorigin="anonymous"
    ></script>
    <!-- Google fonts-->
    <link
      href="https://fonts.googleapis.com/css?family=Montserrat:400,700"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic"
      rel="stylesheet"
      type="text/css"
    />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="CssForIndex/index_css.css" />
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
        ['id' => 5, 'name' => 'โซเชียล', 'link' => 'social.php'],
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
    <section id="articles" class="py-5">
        <h2 class="text-center" style='margin-top: 100px'>บทความทั้งหมด</h2>
      <div class="container py-3" >
        
        <div class="row g-4">
          <!-- Article 1 -->
          <div class="col-4">
            <a class="card h-100 text-decoration-none" href='##1'>
              <img
                src=""
                class="card-img-top"
                alt="Article 1"
              />
              <div class="card-body">
                <h5 class="card-title text-center ">บทความ 1</h5>
                
                
              </div>
            </a>
          </div>
          <!-- Article 2 -->
          <div class="col-4">
            <a class="card h-100 text-decoration-none" href='##2'>
              <img
                src="67622cbdd4991.jpg"
                class="card-img-top"
                alt="Article 2"
              />
              <div class="card-body">
                <h5 class="card-title text-center">บทความ 2</h5>
                
              </div>
            </a>
          </div>
          <!-- Article 3 -->
          <div class="col-4 ">
            <a class="card h-100 text-decoration-none">
              <img
                src="67622cbdd4991.jpg"
                class="card-img-top"
                alt="Article 3"
              />
              <div class="card-body">
                <h5 class="card-title text-center">บทความ 3</h5>
                
              </div>
            </a>
          </div>
          <!-- More Articles -->
          <div class="col-4">
            <a class="card h-100 text-decoration-none">
              <img
                src=""
                class="card-img-top"
                alt="Article 4"
              />
              <div class="card-body">
                <h5 class="card-title text-center">บทความ 4</h5>
              </div>
            </a>
          </div>
          <div class="col-4">
            <a class="card h-100 text-decoration-none">
              <img
                src="67622cbdd4991.jpg"
                class="card-img-top"
                alt="Article 5"
              />
              <div class="card-body">
                <h5 class="card-title text-center">บทความ 5</h5>
              </div>
            </a>
          </div>
          <div class="col-4">
            <a class="card h-100 text-decoration-none">
              <img
                src="67622cbdd4991.jpg"
                class="card-img-top"
                alt="Article 6"
              />
              <div class="card-body">
                <h5 class="card-title text-center">บทความ 6</h5>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!------------------------------------------------------------------>
  </body>
  <footer class="footer">
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
                    <p>ที่อยู่บริษัท <?= $row['homeNumber'] ?>    <?= $row['street'] ?> แขวง<?= $row['subDistrict'] ?>
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
            $result = mysqli_query($conn,$sql);
            $row = $result->fetch_assoc();

            echo "<div class='contactsall'>";
        
                    echo "<div class='contactphone my-2 '>";
                    echo "<i class='bi bi-telephone-fill'>"." ". htmlspecialchars($row['phone'])."</i><br>";
                    echo "<i class='bi bi-line'>"." ". htmlspecialchars($row['line'])."</i><br>";
                    echo "<i class='bi bi-envelope-at-fill'>"." ". htmlspecialchars($row['email'])."</i><br>";
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
                    <ul  style='list-style-type: none;'>
                        <?php foreach ($entries as $item): ?>
                            <li ><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></li>
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
                    <a href="<?= $row['facebook'] ?>" style="text-decoration: none; color:#339fff;"><i class="bi bi-facebook fs-3"></i></a>
                </li>
                <il class='mx-2'>
                    <a href="<?= $row['tiktok'] ?>" style='text-decoration: none; color: #ffffff;'><i class="bi bi-tiktok fs-3"></i></a></li>
                <il class='mx-2'>
                    <a href="##?>" style='text-decoration: none;color: #00f31e;'><i class="bi bi-line fs-3"></i></a></li>
                <il class='mx-2'>
                    <a href="##" style='text-decoration: none; color: #f60505;'><i class="bi bi-youtube fs-3"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
  
</html>
