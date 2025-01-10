<?php

include('db.php');

$sql = "SELECT * FROM members";
$result = mysqli_query($conn, $sql);
// เก็บข้อมูลแยกตามตำแหน่ง
$members = array(
    'ผู้บริหาร' => array(),
    'รองผู้บริหาร' => array(),
    'ผู้จัดการ' => array(),
    'พนักงาน' => array()
);
while($row = mysqli_fetch_assoc($result)) {
    $members[$row['position']][] = $row;
}

?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content='ทีมงานคุณภาพ'>
    <meta name='keywords' content='วันสยาม,เกี่ยวกับฉัน'>

    <?php
    // Path to the folder
    $folderPath = "../admin/uploads/";

    // Get all files in the folder
    $files = glob($folderPath . "*");

    // Sort files by modified time, newest first
    usort($files, function ($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    // Get the latest file
    $latestFile = !empty($files) ? basename($files[0]) : null;

    if ($latestFile) {
        echo '<link rel="icon" type="image/x-icon" href="' . $folderPath . $latestFile . '">';
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
    <script src="scripts.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (สำหรับไอคอน) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Bootstrap JS Bundle (รวม Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </div>

    <style>
/* Organization Chart Styles */
.org-chart {
        text-align: center;
      }
      .org-node {
        display: inline-block;
        padding: 10px;
        margin: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
      }
      .org-connection {
        height: 20px;
        border-left: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        margin: 0 auto;
        width: 50%;
      }
.org-chart-section {
    padding: 20px;
}

.org-level {
    display: flex;
    justify-content: center;
    margin: 20px 0;
    position: relative;
}

.org-box-container {
    display: flex;
    gap: 30px;
    position: relative;
}

.org-box {
    min-width: 400px;
}

.org-box .card {
    transition: transform 0.3s ease;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.org-box .card:hover {
    transform: translateY(-5px);
}

.org-box img {
    margin-top: 10px;
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.org-line {
    width: 2px;
    height: 30px;
    background-color: #fff;
    margin: 0 auto;
}

.position {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .org-box-container {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .org-box {
        min-width: 180px;
    }
}
</style>

    <title>เกี่ยวกับ วันสยาม</title>

</head>

<body>
    <link rel="stylesheet" href="Allpage/จัดการหน้าเว็บ/cssforpanel/page_about.css">

    <div id="notification-icon">
        <?php
        $directory = 'admin/img/logo/';
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

    <div class="content">
        <p><?php echo htmlspecialchars("เกี่ยวกับฉัน", ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="left">
            <img src="<?php echo htmlspecialchars('Allpage/จัดการหน้าเว็บ/images_all/462567104_2075481142880694_1501017219490596836_n.jpg', ENT_QUOTES, 'UTF-8'); ?>"
                alt="Left Image" style="max-width: 300px;">
            <p><?php echo htmlspecialchars("องค์กรบริษัท วันน์สยาม จำกัด และในเครือ <br> บริษัทวันน์สยามและในเครือพัฒนาอย่างต่อเนื่องเราเริ่มดำเนินการตั้งแต่2547 จนถึงปัจจุบันโดยคณะผู้ก่อตั้งที่มีคุณวุฒิ ประกอบด้วยCEO PHAKKACHAPHIMON B<br> เริ่มเปิดดำเนินการ เมื่อปี 2547 โรงงานผลิตตะแกรงครบวงจร<br> ผลิตภัณฑ์ โลหะเหล็ก สแตนเลส อลูมิเนียม<br> ตะแกรงเหล็กฉีก ตะแกรงแผ่นเจาะรู ตะแกรงแผ่นลายกันลื่น<br> อบสีฝุ่น ชุลกัลวาไนซ์<br> ผลิต-ส่งออกและมีตัวแทนจำหน่ายทั่วประเทศ<br> ผู้บริหารทรงคุณวุฒิ พัฒนาสู่การบริหารครบวงจร ด้านการออกแบบสถาปัตยกรรม<br> งานตบแต่งภายใน ทุกรูปแบบ ด้วยการพัฒนาการอย่างไม่หยุดยั้ง<br> เราจึงมี ทีมสถาปนิก วิศวกร ช่างผู้เชี่ยวชาญ ประสบการณ์ตรง<br> เป็นบริษัทที่ให้บริการด้านการก่อสร้างอย่างครบวงจรผลงานเรามีมานานกว่า 10 ปี ให้กับลูกค้าในทุกกลุ่มธุรกิจ รวมเป็นจำนวนมากกว่า 300 โปรเจคโดยแยกเป็น โรงงาน ฝ่ายผลิต ทีมออกแบบ สถาปนิก วิศวกร ช่างชำนาญการ สาขาหน้าร้าน ทีมประสานงานและทีมที่ปรึกษา ดังนั้นไม่ว่าความต้องการของคุณจะยากหรือซับซ้อนขนาดไหนทีมงานของเราสามารถช่วยให้คุณก้าวข้ามผ่านไปได้อย่างแน่นอน<br> ดำเนินงานที่ชัดเจน ส่งมอบงานได้ตรงเวลา : ความรับผิดชอบเวลาเป็นเรื่องสำคัญที่เรายึดถือปฏิบัติมาโดยตลอดการทำงานทุกครั้งจะมีเจ้าหน้าที่คอยให้คำแนะนำงานใช้<br> วัตถุดิบในการออกแบบที่ถูกกฏหมาย : เพื่อประสิทธิภาพสูงสุดในการออกแบบ ซื่อสัตย์งานออกแบบทุกชิ้น<br> เกิดจากการพูดคุยถามตอบถึงความต้องการของลูกค้าและนำมาสร้างสรรค์จนเป็นชิ้นงานอย่างที่ปรากฏมาตลอด 10 ปี<br> เอกสารรับรอง มาตรฐานอุตสาหกรรมญี่ปุ่น JIS G 3505 3351 3101-2004</p>", ENT_QUOTES, 'UTF-8'); ?>
            </p>
        </div>
        <div class="right">
            <img src="<?php echo htmlspecialchars('Allpage/จัดการหน้าเว็บ/images_all/462574549_504611285935765_4198341142524739233_n.jpg', ENT_QUOTES, 'UTF-8'); ?>"
                alt="Right Image" style="max-width: 300px;">
            <p><?php echo htmlspecialchars("ปี2547 ได้เปิดดำเนินการ ค้าปลีกส่งสินค้าและอิเล็กโทนิก<br> ปี2552 ได้เปิดดำเนินการ ผลิตจำหน่ายตะแกรงโลหะ<br> ปี2556 ได้เปิดดำเนินการ ผลิตจำหน่ายติดตั้งงานตะแกรงโลหะ จนถึงปัจจุบัน<br> สินค้าและบริการของเรา ประกอบด้วย ... ผู้ผลิตจำหน่าย ตะแกรงโลหะ เหล็ก สแตนเลส อลูมิเนียม<br> ผลิตปั้มขึ้นรูปเป็นตะแกรงเหล็กฉีก ตะแกรงเหล็กเจาะรู ตะแกรงเหล็กตีนเป็ด ตะแกรงเหล็กตีนไก่<br> อลูมิเนียมฉีก อลูมิเนียมเจาะรู อลูมิเนียมตีนเป็ด อลูมิเนียมตีนไก่<br> สแตนเลสฉีก สแตนเลสเจาะรู สแตนเลสตีนเป็ด สแตนเลสตีนไก่<br> การควบคุมการผลิต<br> มาตรฐานการผลิต JIS ISO ASTM<br> มาตรฐานควบคุม คุณภาพ ด้วยหนังสือรับรองการดูแล<br> มาตรฐานหนังสือรับรองโลหะการผลิตทุกประเภท<br> บริการเสริมการทำสีกันสนิม<br> ชุบกัลวาไนท์<br> อบสีฝุ่น powder<br> พ่นสีอุสาหกรรม<br> ทาสีอุตสาหกรรม<br> บริการจัดส่ง 76 จังหวัดทั่วไทย<br> การจัดส่งให้ทุกมือลูกค้าทั่วไทย<br> ตัวแทนจำหน่าย 4 ภาคทั่วประเทศ<br> ควบคุมการจัดส่งด้วยระบบมาตรฐาน<br> เกรดโลหะใช้ในการผลิต<br> เหล็กแผ่นรีดร้อนชั้นคุณภาพ SS400 เหล็กแผ่นรีดร้อนชนิดม้วน แผ่นแถบ แผ่นหนา - บาง<br> ตามมาตรฐานอุตสาหกรรม มอก. 1479-2541<br> อลูมิเนียม AL1100 พับขึ้นรูปได้ดี เนื้อเหนียว มีความมันเงาไม่เป็นสนิม มีน้ำหนักเบากว่าแผ่นอลูมิเนียม<br> สแตนเลสชนิดหนึ่งในกลุ่มออสเตนนิติค (Austenitic) ซึ่งสแตนเลสชนิดนี้<br> ทนทาน มีความเหนียว ทนกัดกร่อน ทนความร้อนได้สูง ขึ้นรูปได้ดี ไม่เป็นสนิม ไม่ดูดซึมสาร กล่น<br> อย่างยาวนานเกิดจากการผสมโลหะระหว่างเหล็ก และคาร์บอน โครเมียม นิกเกิล เป็นส่วนผสมหลัก", ENT_QUOTES, 'UTF-8'); ?>
            </p>
        </div>
    </div>


    <!-- Organization Chart Section -->
<div class="org-chart-section my-5" style="background-color: #232F4C;">
    <h1 class="text-center py-4 text-white">แผนผังองค์กร</h1>
    
    <!-- CEO Level -->
    <div class="org-level" id="ผู้บริหาร">
        <?php if(!empty($members['ผู้บริหาร'])): ?>
            <?php foreach($members['ผู้บริหาร'] as $ceo): ?>
                <div class="org-box">
                    <div class="card">
                        <div class="card-body d-flex justify-content-around">
                            <img src="Allpage/จัดการหน้าเว็บ/images_member/<?php echo $ceo['member_image']; ?>" 
                                 class="rounded-circle mb-3" 
                                 alt="CEO"
                                 style="width: 100px; height: 100px; object-fit: cover;"
                                 onerror="this.src='Allpage/จัดการหน้าเว็บ/images_member/person.jpg'">
                            <div>
                                <h5><?php echo $ceo['member_name']; ?></h5>
                                <p class="position"><?php echo $ceo['position']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Vertical Line -->
    <div class="org-line"></div>

    <!-- CO-CEO Level -->
    <div class="org-level" id="รองผู้บริหาร">
        <?php if(!empty($members['รองผู้บริหาร'])): ?>
            <?php foreach($members['รองผู้บริหาร'] as $coCeo): ?>
                <div class="org-box mx-3">
                    <div class="card">
                        <div class="card-body d-flex justify-content-around">
                            <img src="Allpage/จัดการหน้าเว็บ/images_member/<?php echo $coCeo['member_image']; ?>" 
                                 class="rounded-circle mb-3" 
                                 alt="CO-CEO"
                                 style="width: 100px; height: 100px; object-fit: cover;"
                                 onerror="this.src='Allpage/จัดการหน้าเว็บ/images_member/person.jpg'">
                            <div>
                                <h5><?php echo $coCeo['member_name']; ?></h5>
                                <p class="position"><?php echo $coCeo['position']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Vertical Line -->
    <div class="org-line"></div>

    <!-- Managers Level -->
    <div class="org-level" id="ผู้จัดการ">
        <div class="org-box-container">
            <?php if(!empty($members['ผู้จัดการ'])): ?>
                <?php foreach($members['ผู้จัดการ'] as $manager): ?>
                    <div class="org-box">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around">
                                <img src="Allpage/จัดการหน้าเว็บ/images_member/<?php echo $manager['member_image']; ?>" 
                                     class="rounded-circle mb-3" 
                                     alt="Manager"
                                     style="width: 100px; height: 100px; object-fit: cover;"
                                     onerror="this.src='Allpage/จัดการหน้าเว็บ/images_member/person.jpg'">
                                <div>
                                    <h5><?php echo $manager['member_name']; ?></h5>
                                    <p class="position"><?php echo $manager['position']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Vertical Line -->
    <div class="org-line"></div>

    
    <div class="org-level mb-5" id="พนักงาน">
        <div class="org-box-container row">
            <?php if(!empty($members['พนักงาน'])): ?>
                <?php foreach($members['พนักงาน'] as $employee): ?>
                    <div class="org-box col-md-3">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around">
                                <img src="Allpage/จัดการหน้าเว็บ/images_member/<?php echo $employee['member_image']; ?>" 
                                     class="rounded-circle mb-3" 
                                     alt="Employee"
                                     style="width: 100px; height: 100px; object-fit: cover;"
                                     onerror="this.src='Allpage/จัดการหน้าเว็บ/images_member/person.jpg'">
                                <div>
                                    <h5><?php echo $employee['member_name']; ?></h5>
                                    <p class="position"><?php echo $employee['position']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
 



    <!-------เพิ่มโค๊ดข้างล่าง (Footer)----------------->
    <footer class="footer text-center position-fixed p-4 w-100" style="z-index: 1000;bottom: 0px; left: 0px;">
        <div class='d-flex justify-content-evenly'>
            <!---Location---->
            <div>
                <h4 class='text-center'>LOCATION</h4>
                <?php
                $sql = "SELECT * FROM address";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <p>
                        ที่อยู่บริษัท <?= htmlspecialchars($row['homeNumber']) ?>
                        <?= htmlspecialchars($row['street']) ?>
                        แขวง<?= htmlspecialchars($row['subDistrict']) ?>
                        เขต<?= htmlspecialchars($row['district']) ?>
                    </p>
                    <p>
                        <?= htmlspecialchars($row['province']) ?>,
                        <?= htmlspecialchars($row['postalCode']) ?>
                    </p>
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
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = $result->fetch_assoc();

                    echo "<div class='contactsall'>";
                    echo "<div class='contactphone my-2 '>";
                    echo "<i class='bi bi-telephone-fill'> " . htmlspecialchars($row['phone']) . "</i><br>";
                    echo "<i class='bi bi-line'> " . htmlspecialchars($row['line']) . "</i><br>";
                    echo "<i class='bi bi-envelope-at-fill'> " . htmlspecialchars($row['email']) . "</i><br>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p>ไม่พบข้อมูลติดต่อ</p>";
                }
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
                <?php
                $sql = 'SELECT * FROM footer_links';
                $result = mysqli_query($conn, $sql);
                $row = ($result && mysqli_num_rows($result) > 0) ? $result->fetch_assoc() : null;
                ?>
                <h4 class='text-center'>SOCIAL</h4>

                <ul class='d-flex justify-content-evenly'>
                    <li class='mx-2'>
                        <a href="<?php echo $row ? htmlspecialchars($row['facebook']) : '#'; ?>"
                            style="text-decoration: none; color:#339fff;">
                            <i class="bi bi-facebook fs-3"></i>
                        </a>
                    </li>
                    <li class='mx-2'>
                        <a href="<?php echo $row ? htmlspecialchars($row['tiktok']) : '#'; ?>"
                            style='text-decoration: none; color: #ffffff;'>
                            <i class="bi bi-tiktok fs-3"></i>
                        </a>
                    </li>
                    <li class='mx-2'>
                        <a href="#" style='text-decoration: none;color: #00f31e;'>
                            <i class="bi bi-line fs-3"></i>
                        </a>
                    </li>
                    <li class='mx-2'>
                        <a href="#" style='text-decoration: none; color: #f60505;'>
                            <i class="bi bi-youtube fs-3"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <div class="copyright py-4 text-center text-white">
        <div class="container">
            <small>
                บริษัท วันน์สยาม จำกัด และในเครือ
                ที่อยู่บริษัท 125 (สำนักงานาใหญ่) ถ.ศรีนครินทร์ แขวงบางนาใต้ เขตบางนา กรุงเทพฯลฯ 10260
            </small>
        </div>
    </div>

    </footer>

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
</body>

</html>