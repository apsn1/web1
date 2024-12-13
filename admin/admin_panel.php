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
    <link rel="stylesheet" href="CssForadmin/admin_panel_css.css">
    <script src="scripts.js"></script>
</head>

<body>
    <div class="allPage">
        <div class="ส่วนจัดการหน้า">

            <button onclick="toggleForm('editForm6')">ฟอมจัดการหน้าเว็บ</button>
            <form id="editForm6" method="POST" action="add_navbar.php" style="display: none;">
                <input type="hidden" name="id" id="formId" placeholder="Form 6">
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


            <button onclick="toggleForm('editForm5')">ฟอม อัพโหลด logo</button>
            <form id="editForm5" action="upload_update.php" method="post" enctype="multipart/form-data"
                style="display: none;">
                <input type="hidden" name="id" id="formId" placeholder="Form 5">
                <h1>อัพโหลด Logo</h1>
                <label for="logo">เลือกรูปภาพใหม่:</label>
                <input type="file" name="logo" id="logo" accept="image/*">
                <button type="submit">อัปโหลดและอัปเดต</button>

                <?php
                $directory = 'uploads/';
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
                            echo "<div class='imageShow'>";
                            echo "<img src='$directory$image' alt='รูปภาพล่าสุด' style='max-width: 100%; height: auto;'>";
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

            <button onclick="toggleForm('editForm8')">ฟอมเพิ่มรูปแบนเนอร์</button>
            <form id="editForm8" action="add_header.php" enctype="multipart/form-data" style="display: none;">
                <h1>เพิ่มรูปแบนเนอร์</h1>
                <div class="form-container">
                    <div class="form-group">
                        <input type="file" name="header" required>
                        <button type="submit">อัปโหลด</button>
                    </div>
                    <a class="btn btn-info" href='edit_header.php'>แก้ไข</a>
                </div>
            </form>
            <button onclick="toggleForm('editForm4')">ฟอมจัดการ video</button>
            <form id="editForm4" method="POST" action="add_contact.php" style="display: none;">
                <input type="hidden" name="id" id="formId" placeholder="Form 4"></input>
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

            <button onclick="toggleForm('editForm3')">ฟอมจัดการ video</button>
            <form id="editForm3" method="POST" action="edit_contact.php" style="display: none;">
                <input type="hidden" name="id" id="formId" placeholder="Form 3"></input>
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

            <button onclick="toggleForm('editForm2')">ฟอม ข้อมูลเกี่ยวกับฉัน</button>
            <form id="editForm2" method="POST" action="edit_contact.php" style="display: none;">
                <input type="hidden" name="id" id="formId" placeholder="Form 2">
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


            <button onclick="toggleForm('editForm1')">ฟอมข้อมูลติดต่อ</button>
            <form id="editForm1" method="POST" action="edit_contact.php" style="display: none;">

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

            <button onclick="toggleForm('editForm7')">ฟอมเพิ่มรูปโปรเจค</button>
            <form id="editForm7" method="POST" action="add_imgproject.php" enctype="multipart/form-data"
                style="display: none;">
                <label for="image">เลือกภาพ:</label>
                <input type="file" name="image" id="image" required>

                <label for="alt_text">คำอธิบายภาพ (Alt Text):</label>
                <input type="text" name="alt_text" id="alt_text" required>

                <button type="submit">อัปโหลด</button>
            </form>



        </div>
        <div class="ส่วนตัวอย่างหน้า">
            <iframe src="../index.php" class="iframe-content"></iframe>
        </div>
    </div>

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


        // ซ่อนฟอร์มทั้งหมดเมื่อโหลดหน้า
        window.onload = function () {
            const formCount = 6;
            for (let i = 1; i <= formCount; i++) {
                const form = document.getElementById(`editForm${i}`);
                if (form) {
                    form.style.display = 'none'; // ซ่อนฟอร์มทั้งหมดตอนเริ่มต้น
                }
            }
        };

        // ฟังก์ชันสำหรับ toggle การแสดงผลฟอร์มทีละตัว
        function toggleForm(formId) {
            const formToToggle = document.getElementById(formId);

            if (formToToggle) {
                // ตรวจสอบสถานะการแสดงผลของฟอร์ม
                const isFormVisible = formToToggle.style.display === 'block';

                // ซ่อนฟอร์มทั้งหมด
                const formCount = 6;
                for (let i = 1; i <= formCount; i++) {
                    const form = document.getElementById(`editForm${i}`);
                    if (form) {
                        form.style.display = 'none';
                    }
                }

                // แสดงหรือซ่อนฟอร์มที่เลือก
                formToToggle.style.display = isFormVisible ? 'none' : 'block';
            }
        }



    </script>

</body>

</html>