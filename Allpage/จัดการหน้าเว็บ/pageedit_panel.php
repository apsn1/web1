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
            <div class="ส่วนจัดการหน้า">
                <?php
                $sql = "SELECT * FROM page_aboutme ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // แสดงชื่อไฟล์เป็นลิงก์ที่สามารถคลิกได้
                    while ($row = $result->fetch_assoc()) {
                        // สมมติว่าไฟล์อยู่ในโฟลเดอร์ 'uploads'
                        $filePath = '../' . htmlspecialchars($row["name"]) . '.php';
                        echo '<a onclick="loadFile(\'' . $filePath . '\')".>' . htmlspecialchars($row["name"]) . '</a>';
                    }
                } else {
                    echo "ไม่มีไฟล์ในระบบ";
                }
                ?>
            </div>
            <div class="ส่วนตัวอย่างหน้า">
                <iframe id="fileIframe" class="iframe-content"  src=""></iframe>

            </div>
        </div>
    </div>
    </div>
</body>

</html>