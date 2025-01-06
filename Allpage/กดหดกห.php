<?php
include('../db.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content='ดดด'>
    <meta name='keywords' content='ดดด'>

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
</div>
    <title>ดดด</title>
    
</head>
<body>  
      <link rel="stylesheet" href="จัดการหน้าเว็บ/cssforpanel/page_about.css">

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
    
</div>
<button id="scrollToTop">↑ ขึ้นบนสุด</button>
    <?php
    // กำหนดเมนูหลัก
    $mainMenus = [
        ['id' => 1, 'name' => 'หน้าหลัก'],
        ['id' => 2, 'name' => 'เกี่ยวกับเรา'], 
        ['id' => 3, 'name' => 'สินค้า'],
        ['id' => 4, 'name' => 'โปรเจค'],
        ['id' => 5, 'name' => 'โซเซี่ยล'],
        ['id' => 6, 'name' => 'บทความ'],
        ['id' => 7, 'name' => 'ติดต่อเรา']
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

echo "</a>"?>

 <?php   // ปุ่ม Toggle สำหรับ Mobile
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

<div class="content">
    <p >เกี่ยวกับเรา</p>
    <div class="left">
        <img src="จัดการหน้าเว็บ/images_all/462567104_2075481142880694_1501017219490596836_n.jpg" alt="Left Image" style="max-width: 300px;">
        <p>องค์กรบริษัท วันน์สยาม จำกัด และในเครือ <br>
            บริษัทวันน์สยามและในเครือพัฒนาอย่างต่อเนื่องเราเริ่มดำเนินการตั้งแต่2547 จนถึงปัจจุบันโดยคณะผู้ก่อตั้งที่มีคุณวุฒิ ประกอบด้วยCEO PHAKKACHAPHIMON B<br>
            เริ่มเปิดดำเนินการ เมื่อปี 2547 โรงงานผลิตตะแกรงครบวงจร<br>
            ผลิตภัณฑ์ โลหะเหล็ก สแตนเลส อลูมิเนียม<br>
            ตะแกรงเหล็กฉีก ตะแกรงแผ่นเจาะรู ตะแกรงแผ่นลายกันลื่น<br>
            อบสีฝุ่น ชุลกัลวาไนซ์<br>
            ผลิต-ส่งออกและมีตัวแทนจำหน่ายทั่วประเทศ<br>
            ผู้บริหารทรงคุณวุฒิ พัฒนาสู่การบริหารครบวงจร ด้านการออกแบบสถาปัตยกรรม<br>
            งานตบแต่งภายใน ทุกรูปแบบ ด้วยการพัฒนาการอย่างไม่หยุดยั้ง<br>
            เราจึงมี ทีมสถาปนิก วิศวกร ช่างผู้เชี่ยวชาญ ประสบการณ์ตรง<br>
            เป็นบริษัทที่ให้บริการด้านการก่อสร้างอย่างครบวงจรผลงานเรามีมานานกว่า 10 ปี ให้กับลูกค้าในทุกกลุ่มธุรกิจ รวมเป็นจำนวนมากกว่า 300 โปรเจคโดยแยกเป็น โรงงาน ฝ่ายผลิต ทีมออกแบบ สถาปนิก วิศวกร ช่างชำนาญการ สาขาหน้าร้าน ทีมประสานงานและทีมที่ปรึกษา ดังนั้นไม่ว่าความต้องการของคุณจะยากหรือซับซ้อนขนาดไหนทีมงานของเราสามารถช่วยให้คุณก้าวข้ามผ่านไปได้อย่างแน่นอน<br>
            ดำเนินงานที่ชัดเจน ส่งมอบงานได้ตรงเวลา : ความรับผิดชอบเวลาเป็นเรื่องสำคัญที่เรายึดถือปฏิบัติมาโดยตลอดการทำงานทุกครั้งจะมีเจ้าหน้าที่คอยให้คำแนะนำงานใช้<br>
            วัตถุดิบในการออกแบบที่ถูกกฏหมาย : เพื่อประสิทธิภาพสูงสุดในการออกแบบ ซื่อสัตย์งานออกแบบทุกชิ้น<br>
            เกิดจากการพูดคุยถามตอบถึงความต้องการของลูกค้าและนำมาสร้างสรรค์จนเป็นชิ้นงานอย่างที่ปรากฏมาตลอด 10 ปี<br>
            เอกสารรับรอง มาตรฐานอุตสาหกรรมญี่ปุ่น JIS G 3505 3351 3101-2004</p>
    </div>
    <div class="right">
        <img src="จัดการหน้าเว็บ/images_all/462578066_1269579590828770_2829080610683077338_n.jpg" alt="Right Image" style="max-width: 300px;">
        <p >ปี2547 ได้เปิดดำเนินการ ค้าปลีกส่งสินค้าและอิเล็กโทนิก<br>
        ปี2552 ได้เปิดดำเนินการ ผลิตจำหน่ายตะแกรงโลหะ<br>
        ปี2556 ได้เปิดดำเนินการ ผลิตจำหน่ายติดตั้งงานตะแกรงโลหะ จนถึงปัจจุบัน<br>
        สินค้าและบริการของเรา ประกอบด้วย ... ผู้ผลิตจำหน่าย ตะแกรงโลหะ เหล็ก สแตนเลส อลูมิเนียม<br>
        ผลิตปั้มขึ้นรูปเป็นตะแกรงเหล็กฉีก ตะแกรงเหล็กเจาะรู ตะแกรงเหล็กตีนเป็ด ตะแกรงเหล็กตีนไก่<br>
        อลูมิเนียมฉีก อลูมิเนียมเจาะรู อลูมิเนียมตีนเป็ด อลูมิเนียมตีนไก่<br>
        สแตนเลสฉีก สแตนเลสเจาะรู สแตนเลสตีนเป็ด สแตนเลสตีนไก่<br>
        การควบคุมการผลิต<br>
        มาตรฐานการผลิต JIS ISO ASTM<br>
        มาตรฐานควบคุม คุณภาพ ด้วยหนังสือรับรองการดูแล<br>
        มาตรฐานหนังสือรับรองโลหะการผลิตทุกประเภท<br>
        บริการเสริมการทำสีกันสนิม<br>
        ชุบกัลวาไนท์<br>
        อบสีฝุ่น powder<br>
        พ่นสีอุสาหกรรม<br>
        ทาสีอุตสาหกรรม<br>
        บริการจัดส่ง 76 จังหวัดทั่วไทย<br>
        การจัดส่งให้ทุกมือลูกค้าทั่วไทย<br>
        ตัวแทนจำหน่าย 4 ภาคทั่วประเทศ<br>
        ควบคุมการจัดส่งด้วยระบบมาตรฐาน<br>
        เกรดโลหะใช้ในการผลิต<br>
        เหล็กแผ่นรีดร้อนชั้นคุณภาพ SS400 เหล็กแผ่นรีดร้อนชนิดม้วน แผ่นแถบ แผ่นหนา - บาง<br>
        ตามมาตรฐานอุตสาหกรรม มอก. 1479-2541<br>
        อลูมิเนียม AL1100 พับขึ้นรูปได้ดี เนื้อเหนียว มีความมันเงาไม่เป็นสนิม มีน้ำหนักเบากว่าแผ่นอลูมิเนียม<br>
        สแตนเลสชนิดหนึ่งในกลุ่มออสเตนนิติค (Austenitic) ซึ่งสแตนเลสชนิดนี้<br>
        ทนทาน มีความเหนียว ทนกัดกร่อน ทนความร้อนได้สูง ขึ้นรูปได้ดี ไม่เป็นสนิม ไม่ดูดซึมสาร กล่น<br>
        อย่างยาวนานเกิดจากการผสมโลหะระหว่างเหล็ก และคาร์บอน โครเมียม นิกเกิล เป็นส่วนผสมหลัก</p>
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