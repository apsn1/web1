<?php include('../db.php'); ?>
<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>แอดมิน</title>
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css"> <!-- เพิ่มลิงค์ไปยังไฟล์ CSS -->
</head>

<body>
    <div class="allPage">
        <div class="ส่วนจัดการหน้า">

            <form method="POST" action="add_navbar.php">
                <h1>จัดการหน้าเมนูหน้าเว็บ</h1>
                <div class="form-container">
                    <label for="name">ชื่อเมนูหลัก:</label>
                    <input type="text" name="name" placeholder="ชื่อเมนูหลัก" required>

                    <label for="is_dropdown">ทำให้เมนูเป็น Dropdown?</label>
                    <input type="checkbox" name="is_dropdown" id="is_dropdown" onclick="toggleDropdownFields(this)">

                    <!-- ช่องกรอกเมนูย่อย -->
                    <div class="dropdown-fields" style="display: none;">
                        <label for="dropdown_name">ชื่อเมนูย่อย:</label>
                        <input type="text" name="dropdown_name[]" placeholder="ชื่อเมนูย่อย 1">
                        <div class="additional-dropdown-fields"></div>

                        <!-- ปุ่มเพิ่มเมนูย่อย -->
                        <button type="button" onclick="addDropdownMenu()">เพิ่มเมนูย่อย</button>
                    </div>

                    <input type="submit" value="เพิ่มเมนู">
                </div>

                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อหัวข้อ</th>
                            <th>เป็นหัวข้อย่อยของ</th>
                            <th>ลบ</th>
                            <th>แก้ไข</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM navbar";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["parent_id"] . "</td>";
                                echo "<td> <a href='delete_navbar.php?del=" . $row["id"] . "'>ลบ</a>" . "</td>";
                                echo "<td> <a href='update_navbar.php?edit=" . $row["id"] . "'>แก้ไข</a>" . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>ไม่มีข้อมูล</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>



            <form action="upload_update.php" method="post" enctype="multipart/form-data">
                <h1>อัพโหลด Logo</h1>
                <label for="logo">เลือกรูปภาพใหม่:</label>
                <input type="file" name="logo" id="logo" accept="image/*"></input>
                <button type="submit">อัปโหลดและอัปเดต</button>
                <?php
                $sql = "SELECT * FROM navbar";
                $result = $conn->query($sql);
                $directory = 'uploads/';
                // ตรวจสอบว่าโฟลเดอร์มีอยู่จริง
                if (is_dir($directory)) {
                    $files = scandir($directory);

                    // ตรวจสอบว่า scandir() คืนค่าไม่เป็น false
                    if ($files !== false) {
                        // ลบ . และ .. ออกจากลิสต์
                        $files = array_diff($files, array('.', '..'));

                        // กรองเฉพาะไฟล์ที่เป็นรูปภาพ (เช่น .jpg, .png)
                        $imageFiles = array_filter($files, function ($file) {
                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                            return in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']); // เพิ่มประเภทไฟล์ที่ต้องการ
                        });

                        // ตรวจสอบว่าอาร์เรย์ $imageFiles มีไฟล์รูปภาพ
                        if (count($imageFiles) > 0) {
                            // เลือกรูปภาพแรกจากโฟลเดอร์
                            $image = reset($imageFiles); // เลือกรูปภาพแรกจากอาร์เรย์ที่กรองแล้ว
                            echo "<div class ='imageShow'>";
                            echo "<img src='$directory$image' alt='รูปภาพล่าสุด' ></img>";
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
                ?>
            </form>

            <form method="POST" action="add_content.php" enctype="multipart/form-data">
                <h1>จัดการข้อมูลหน้าเว็บ</h1>
                <div class="form-container">
                    <div class="form-group">
                        <input type="text" name="title" placeholder="ชื่อหัวข้อ" required></input>
                    </div>
                    <div class="form-group">
                        <textarea name="body" placeholder="เนื้อหา" required></textarea>
                    </div>

                    <button type="submit">เพิ่มข้อมูล</button>

                    <?php
                    $sql = "SELECT * FROM content";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='Colum1'>";
                            echo "<h3>" . $row['title'] . "</h3>";
                            echo "<p>" . $row['body'] . "</p>";
                            echo "<td> <div class='action'><a href='update_content.php?edit=" . $row['contentID'] . "'>แก้ไขข้อมูล</a></div>" . "</td>";
                            echo "<td> <div class='action'><a href='delete_content.php?del=" . $row['contentID'] . "'>ลบ</a></div>" . "</td>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div>ไม่มีข้อมูล</div>";
                    }
                    ?>
                </div>
            </form>

            <form action=" update_video.php" method="POST">
                <h1>จัดการข้อมูล วิดิโอ</h1>
                <input type="text" name="video_link" placeholder="YouTube Video Link" required></input>
                <div class="form-group">
                    <input type="text" name="video_title" placeholder="ชื่อหัวข้อ Link" required></input>
                </div>
                <button type="submit">อัปเดตวิดีโอ</button>
                <?php
                $sql = "SELECT * FROM videos";
                $result = $conn->query($sql);
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
            </form>
            <?php
            // ดึงข้อมูลจากฐานข้อมูล (กรณีแก้ไข)
            $sql = "SELECT * FROM about WHERE aboutID = 1"; // หรือกำหนดเงื่อนไขที่ต้องการ
            $result = $conn->query($sql);
            $about = $result->fetch_assoc();
            ?>

            <form method="POST" action="edit_about.php">
                <input type="hidden" name="aboutID" value="<?php echo htmlspecialchars($about['aboutID'] ?? ''); ?>">
                <!-- กำหนด aboutID -->

                <!-- ข้อความองค์กร -->
                <div class="underAbout"></div>
                <div class="row">
                    <div class="col-lg-4 ms-auto">
                        <label for="onesiamText">องค์กร บริษัท วันน์สยาม จำกัด</label><br>
                        <textarea id="onesiamText" name="onesiamText" rows="5" class="form-control">
                <?php echo htmlspecialchars($about['onesiamText'] ?? ''); ?>
            </textarea>
                    </div>
                    <div class="col-lg-4 me-auto">
                        <label for="aboutText">ข้อความเกี่ยวกับบริษัท</label><br>
                        <textarea id="aboutText" name="aboutText" rows="5" class="form-control">
                <?php echo htmlspecialchars($about['aboutText'] ?? ''); ?>
            </textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">อัปเดตข้อมูล</button>
                </div>
            </form>

        </div>
        <div class="ส่วนตัวอย่างหน้า">
            <iframe src="../index.php" class="iframe-content"></iframe>
        </div>
    </div>
    <form method="POST" action="edit_contact.php">
    <input type="hidden" name="id" id="formId" 
           value="<?php echo htmlspecialchars($minicontacts['id'] ?? ''); ?>"> 
    <!-- ส่งค่า id ของข้อมูลที่จะแก้ไข -->

    <label for="type">ประเภท:</label>
    <select name="type" id="formType">
        <option value="phone" <?php echo (isset($minicontacts['type']) && $minicontacts['type'] == 'phone') ? 'selected' : ''; ?>>
            เบอร์โทรศัพท์
        </option>
        <option value="line" <?php echo (isset($minicontacts['type']) && $minicontacts['type'] == 'line') ? 'selected' : ''; ?>>
            ไอดีไลน์
        </option>
    </select>

    <label for="value">ข้อมูล:</label>
    <input type="text" name="value" id="formValue" 
           value="<?php echo htmlspecialchars($minicontacts['value'] ?? ''); ?>" required>

    <button type="submit">บันทึก</button>
</form>


    <!-- Form สำหรับแก้ไขข้อมูล -->


    <script>
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

    </script>

</body>

</html>