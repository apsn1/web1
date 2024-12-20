<?php include('../../db.php') ?>


<!DOCTYPE html>
<html>

<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="cssforpanel.css/pageedit_css.css">
    <script src="scripts.js"></script>
</head>

<body>
    <div class="admin_page">
        <div class="tabmenu">
            จัดการหน้าเว็บ
        </div>
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
                <div class="tab" onclick="window.location.href='editallpage.php';">สร้างหน้าเว็บเพิ่ม</div>
                <div class="tab" onclick="toggleDropdown(this)">
                    <span>จัดการหน้าเว็บอื่นๆ</span>
                    <span class="icon">&#9660;</span>
                </div>
                <div class="dropdown-menu" style="display: none;">
                    <div class="dropdown-item" onclick="window.location.href='pageedit_panel.php';">หน้าเว็บทั้งหมด
                    </div>
                    <div class="dropdown-item" onclick="window.location.href='images_all.php';">รูปภาพทั้งหมด</div>
                    <div class="dropdown-item" onclick="window.location.href='blogs_all.php';">บทความทั้งหมด</div>
                </div>
                <div class="tab" onclick="window.location.href='tab3.html';">แท็บที่ 3</div>
                <div class="tab" onclick="window.location.href='tab4.html';">แท็บที่ 4</div>
            </div>
            <div class="ส่วนจัดการหน้า">
                <?php
                include('../../db.php'); // เชื่อมต่อฐานข้อมูล
                
                // ดึงข้อมูลบทความทั้งหมดจากฐานข้อมูล
                $sql = "SELECT * FROM filemanager ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    echo '<ul>';
                    while ($row = $result->fetch_assoc()):
                        // สร้างเส้นทางของไฟล์จากชื่อไฟล์ในฐานข้อมูล
                        $filePath = '../' . $row['filename'];

                        // ตรวจสอบว่าไฟล์มีอยู่ใน directory หรือไม่
                        if (file_exists($filePath)) {
                            // หากไฟล์มีอยู่ แสดงลิงก์ที่สามารถคลิกเพื่อเปลี่ยน iframe
                            echo '<li><strong>' . htmlspecialchars($row['filename']) . '</strong> - <a href="#" onclick="updateIframe(\'' . $filePath . '\')">ดูเนื้อหา</a></li>';
                        } else {
                            // หากไฟล์ไม่พบ แสดงข้อความว่า "ไฟล์ไม่พบ"
                            echo '<li><strong>' . htmlspecialchars($row['filename']) . '</strong> - <span>ไฟล์ไม่พบ</span></li>';
                        }
                    endwhile;
                    echo '</ul>';
                else:
                    echo '<p>ยังไม่มีไฟล์ที่ถูกบันทึกไว้</p>';
                endif;

                $conn->close();
                ?>
            </div>
            <div class="ส่วนตัวอย่างหน้า">
                <iframe src="../../index.php" class="iframe-content" id="contentFrame"></iframe>
            </div>

            <script>

                // ฟังก์ชันสำหรับเปลี่ยน src ของ iframe
                function updateIframe(filePath) {
                    document.getElementById('contentFrame').src = filePath;
                }

                function toggleDropdownFields(checkbox) {
                    var dropdownFields = document.querySelector('.dropdown-fields');
                    if (checkbox.checked) {
                        dropdownFields.style.display = 'block';
                    } else {
                        dropdownFields.style.display = 'none';
                    }
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

                    // ปิดเมนูอื่นที่เปิดอยู่
                    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('open'));
                    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');

                    // แสดงหรือซ่อนเมนูที่คลิก
                    if (!isOpen) {
                        element.classList.add('open');
                        element.nextElementSibling.style.display = 'block';
                    }
                }

                // ปิดเมนู dropdown เมื่อคลิกพื้นที่อื่น
                document.addEventListener('click', function (event) {
                    const isDropdown = event.target.closest('.tab, .dropdown-menu');
                    if (!isDropdown) {
                        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('open'));
                        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');
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
            </script>
        </div>
    </div>
</body>

</html>