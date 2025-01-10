<?php include('../../db.php');
?>


<!DOCTYPE html>
<html>

<head>
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
    <title>แอดมิน</title>
    <link rel="stylesheet" href="cssforpanel/pageedit_panel.css">
</head>

<body>
    <div class="admin_page">
        <div class="allPage">
            <div class="tabmenuBar">
                <?php
                $sql = "SELECT *, COALESCE(parent_id, 0) AS parent_id FROM navbar ORDER BY parent_id ASC";
                $result = $conn->query($sql);

                $menus = [];
                while ($row = $result->fetch_assoc()) {
                    $parentId = $row['parent_id'];
                    $menus[$parentId][] = $row;
                }

                echo "<a href='#'>";

                $directory = '../../admin/uploads/';
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
                            echo "<img src='$directory$image' alt='รูปภาพล่าสุด' style='height: 75px; width: 97px; margin-bottom: 50px;'>";
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
                ?>

                <div class="tab" onclick="window.location.href='../../admin/admin_panel.php';">จัดการหน้าหลัก</div>
                <div class="tab" onclick="toggleDropdown(this)">
                    <span>จัดการหน้าเว็บอื่นๆ</span>
                    <span class="icon">&#9660;</span>
                </div>
                <div class="dropdown-menu" style="display: none;">
                    <div class="dropdown-item" onclick="window.location.href='pageedit_panel.php';">หน้าเว็บทั้งหมด
                    </div>
                    <div class="dropdown-item" onclick="window.location.href='images_all.php';">รูปภาพทั้งหมด</div>
                </div>
                <div class="tab" onclick="window.location.href='tab3.html';">แท็บที่ 3</div>
                <div class="tab" onclick="window.location.href='tab4.html';">แท็บที่ 4</div>

            </div>
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
                            '../../social.all.php' => 'โซเซียล',
                            '../../social_youtube.php' => 'youtube',
                            '../../show_product.php' => 'สินค้าทั้งหมด',

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
                        $hardcodedFiles = [
                            'add_social.php' => 'Social Page',
                            'insertfile.php' => 'เพิ่มไฟล์เทม 1',
                            'insertfile_product.php' => 'เพิ่มสินค้า',
                            'from_member.php' => 'เพิ่มและแก้ไข บุคลากร',
                            'from_product.php' => 'เพิ่มตัวสินค้าในหน้าสินค้า'
                        ];

                        foreach ($hardcodedFiles as $filePath => $fileName) {
                            if ($filePath === 'from_product.php') {
                                // 1) แสดงลิงก์หลัก
                                echo '<a onclick="loadFile(\'' . htmlspecialchars($filePath) . '\')">'
                                    . htmlspecialchars($fileName) . '</a><br>';

                                // 2) แสดงเมนูย่อย (Hardcode เพิ่มเอง)
                                echo '<div style="margin-left: 20px;">';
                                echo '  <a onclick="loadFile(\'managed_products.php\')">แก้ไขสินค้า</a><br>';
                                echo '</div>';
                            } else {
                                // แสดงลิงก์ปกติ
                                echo '<a onclick="loadFile(\'' . htmlspecialchars($filePath) . '\')">'
                                    . htmlspecialchars($fileName) . '</a><br>';
                            }
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


            <!-- Form สำหรับแก้ไขข้อมูล -->


            <script>
                function loadFile(filePath) {
                    document.getElementById('fileIframe').src = filePath;
                }

                function toggleDropdownFields(checkbox) {
                    var dropdownFields = document.querySelector('.dropdown-fields');
                    if (checkbox.checked) {
                        dropdownFields.style.display = 'block';
                    } else {
                        dropdownFields.style.display = 'none';
                    }
                }

                function addDropdownMenu() {
                    var newInput = document.createElement('input');
                    newInput.type = 'text';
                    newInput.name = 'dropdown_name[]';
                    newInput.placeholder = 'ชื่อเมนูย่อย';
                    document.querySelector('.additional-dropdown-fields').appendChild(newInput);
                }
                function openForm(id, type, value) {
                    document.getElementById('formId').value = id || '';
                    document.getElementById('formType').value = type || 'phone';
                    document.getElementById('formValue').value = value || '';
                }

                document.addEventListener('mousemove', function (event) {
                    const menu = document.querySelector('.tabmenuBar');

                    // ตรวจจับเมาส์ใกล้ขอบซ้าย (ภายใน 10px)
                    if (event.clientX <= 10) {
                        menu.classList.add('open'); // เปิดเมนู
                    } else if (event.clientX > 200) { // ถ้าเมาส์ออกห่างจากเมนู
                        menu.classList.remove('open'); // ปิดเมนู
                    }
                });

                function toggleDropdown(element) {
                    const isOpen = element.classList.contains('open');

                    // ปิดเมนูอื่นๆ
                    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('open'));
                    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');

                    // เปิด/ปิดเมนูปัจจุบัน
                    if (!isOpen) {
                        element.classList.add('open');
                        element.nextElementSibling.style.display = 'block';
                    }
                }

                // ปิดเมนูเมื่อคลิกพื้นที่อื่น
                document.addEventListener('click', function (event) {
                    if (!event.target.closest('.tab') && !event.target.closest('.dropdown-menu')) {
                        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('open'));
                        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');
                    }
                });

                function toggleForm(formId) {
                    var form = document.getElementById(formId);
                    if (form.style.display === "none") {
                        form.style.display = "block";
                    } else {
                        form.style.display = "none";
                    }
                }

            </script>

</body>

</html>