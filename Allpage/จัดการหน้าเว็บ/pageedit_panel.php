<?php include('../../db.php') ?>


<!DOCTYPE html>
<html>

<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="cssforpanel/page_blog.css">
    <script src="scripts.js"></script>
    <link rel="stylesheet" href="../../admin/CssForAdmin/admin_panel_css.css">
    <?php
    // Path to the folder
    $folderPath = "../../admin/uploads/";

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
</head>

<body>

    <head>
        <meta charset="UTF-8">
        <title>แสดงไฟล์ใน Iframe</title>
        <script>
            // ฟังก์ชันเพื่อเปลี่ยนแปลง src ของ iframe
            function loadFile(filename) {
                var iframe = document.getElementById('fileIframe');
                iframe.src = filename;
            }

            // โหลดไฟล์แรกเมื่อเปิดหน้าเว็บ
            window.onload = function () {
                var firstLink = document.querySelector('.file-list a');
                if (firstLink) {
                    firstLink.click();
                }
            };
        </script>
    </head>
    <div class="admin_page">
        <div class="tabmenu">
            จัดการหน้าเว็บ
        </div>
        <div class="allPage">
            <!-- ส่วนแสดงหน้าเว็บตัวอย่าง (ซ้าย) -->
            <div class="ส่วนหน้าเว็บตัวอย่าง">
                <h2>หน้าเว็บตัวอย่าง</h2>
                <?php
                // สมมติว่าไฟล์ตัวอย่างหน้าเว็บ hardcoded
                $exampleFiles = [
                    '../../index.php' => 'หน้าแรก',
                    '../../about.php' => 'เกี่ยวกับเรา',
                    '../../social.php' => 'โซเซียล',
                ];

                foreach ($exampleFiles as $filePath => $fileName) {
                    echo '<a onclick="loadFile(\'' . htmlspecialchars($filePath) . '\')">' . htmlspecialchars($fileName) . '</a><br>';
                }

                // ดึงไฟล์เพิ่มเติมจากฐานข้อมูล
                $sql = "SELECT * FROM page_aboutme ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $filePath = '../' . htmlspecialchars($row["name"]) . '.php';
                        echo '<a onclick="loadFile(\'' . $filePath . '\')">' . htmlspecialchars($row["name"]) . '</a><br>';
                    }
                } else {
                    echo "ไม่มีไฟล์ในระบบ<br>";
                }
                ?>

            </div>

            <!-- ส่วนจัดการไฟล์ (กลาง) -->
            <div class="ส่วนจัดการไฟล์">
                <h2>จัดการไฟล์</h2>
                <?php
                // สมมติว่าไฟล์ hardcoded อยู่ในอาร์เรย์
                $hardcodedFiles = [
                    'add_social.php' => 'Social Page',
                    'insertfile.php' => 'เพิ่มไฟล์',
                    '../uploads/file3.php' => 'File 3',
                ];

                foreach ($hardcodedFiles as $filePath => $fileName) {
                    echo '<a onclick="loadFile(\'' . htmlspecialchars($filePath) . '\')">' . htmlspecialchars($fileName) . '</a><br>';
                }

                ?>
            </div>

            <!-- ส่วนแสดง iframe (ขวา) -->
            <div class="ส่วนแสดงผล">
                <h2>แสดงผลไฟล์</h2>
                <iframe id="fileIframe" class="iframe-content" src=""></iframe>
            </div>
        </div>
    </div>

</body>

</html>