<?php include('../db.php');
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
                <div class="tab" onclick="window.location.href='admin_panel.php';">จัดการหน้าหลัก</div>
                <div class="tab" onclick="window.location.href='../Allpage/จัดการหน้าเว็บ/insertfile.php';">
                    สร้างหน้าเว็บเพิ่ม</div>
                <div class="tab" onclick="toggleDropdown(this)">
                    <span>จัดการหน้าเว็บอื่นๆ</span>
                    <span class="icon">&#9660;</span>
                </div>
                <div class="dropdown-menu" style="display: none;">
                    <div class="dropdown-item"
                        onclick="window.location.href='../Allpage/จัดการหน้าเว็บ/pageedit_panel.php';">หน้าเว็บทั้งหมด
                    </div>
                    <div class="dropdown-item"
                        onclick="window.location.href='../Allpage/จัดการหน้าเว็บ/images_all.php';">รูปภาพทั้งหมด</div>
                </div>
                <div class="tab" onclick="window.location.href='tab3.html';">แท็บที่ 3</div>
                <div class="tab" onclick="window.location.href='tab4.html';">แท็บที่ 4</div>

            </div>
            <div class="ส่วนจัดการหน้า">

                <?php
                // 1) เชื่อมต่อฐานข้อมูล
                include('../db.php');

                // 2) สมมติยังต้องการเลือกไฟล์จากตาราง filemanager
                $sqlFile = "SELECT id, filename FROM filemanager";
                $resultFile = $conn->query($sqlFile);

                // 3) ดึงข้อมูลเมนูย่อยทั้งหมดจาก navbar
                $sqlNavbar = "SELECT * FROM navbar ORDER BY id ASC";
                $resultNavbar = $conn->query($sqlNavbar);

                // 4) กำหนดเมนูหลัก (Main Menu) แบบ Hard-coded
                $mainMenus = [
                    ['id' => 1, 'name' => 'หน้าหลัก'],
                    ['id' => 2, 'name' => 'เกี่ยวกับเรา'],
                    ['id' => 3, 'name' => 'บริการ'],
                    ['id' => 4, 'name' => 'ติดต่อเรา']
                ];

                // 4.1 สร้าง mapping แบบง่าย: id => name
                $mainMenusMap = [];
                foreach ($mainMenus as $m) {
                    // เช่น 1 => "หน้าหลัก"
                    $mainMenusMap[$m['id']] = $m['name'];
                }
                ?>

                <!-- ฟอร์มจัดการเมนู -->
                <button onclick="toggleForm('editForm6')">ฟอร์มจัดการเมนู</button>

                <form id="editForm6" method="POST" action="add_navbar.php" style="display: none;">
                    <h1>จัดการเมนูย่อยหน้าเว็บ</h1>

                    <!-- เลือกเมนูหลัก (Hard-coded) -->
                    <label for="parent_id">เลือกเมนูหลัก:</label>
                    <select name="parent_id" id="parent_id" required>
                        <option value="">-- เลือกเมนูหลัก --</option>
                        <?php
                        foreach ($mainMenus as $menu) {
                            echo "<option value='" . $menu['id'] . "'>" . $menu['name'] . "</option>";
                        }
                        ?>
                    </select>

                    <!-- ชื่อเมนูย่อย -->
                    <label for="sub_name">ชื่อเมนูย่อย:</label>
                    <input type="text" name="sub_name" id="sub_name" placeholder="ชื่อเมนูย่อย" required>

                    <!-- เลือกไฟล์ที่จะลิงก์ (ดึงจาก table filemanager) -->
                    <label for="link_to">เลือกไฟล์ที่จะลิงค์:</label>
                    <select name="link_to" id="link_to" required>
                        <option value="">-- เลือกไฟล์ --</option>
                        <?php
                        if ($resultFile->num_rows > 0) {
                            while ($rowFile = $resultFile->fetch_assoc()) {
                                echo "<option value='" . $rowFile['filename'] . "'>"
                                    . htmlspecialchars($rowFile['filename'])
                                    . "</option>";
                            }
                        } else {
                            echo "<option value=''>ไม่มีไฟล์ในระบบ</option>";
                        }
                        ?>
                    </select>

                    <input type="submit" value="เพิ่มเมนูย่อย">

                    <!-- ตารางแสดงข้อมูล -->
                    <table border="1">
                        <thead>
                            <tr>
                                <th>ชื่อหัวข้อหลัก (จาก $mainMenus)</th>
                                <th>ชื่อหัวข้อย่อย (name)</th>
                                <th>ลิงค์ไปยัง</th>
                                <th>แก้ไข</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultNavbar->num_rows > 0) {
                                while ($row = $resultNavbar->fetch_assoc()) {
                                    // $row["parent_id"] จะเป็น 1,2,3,4 ถ้าเป็นเมนูย่อยของเมนูหลัก
                                    // ถ้าเป็นค่าอื่น / null / 0 ก็จะแสดง "-"
                                    $parentName = isset($mainMenusMap[$row["parent_id"]])
                                        ? $mainMenusMap[$row["parent_id"]]
                                        : '-';

                                    // แสดงผลเป็น row
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($parentName) . "</td>";  // ชื่อเมนูหลัก
                                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>"; // ชื่อเมนูย่อย
                                    echo "<td>" . htmlspecialchars($row["link_to"]) . "</td>"; // ลิงก์
                            
                                    echo "<td><a href='delete_navbar.php?del=" . $row["id"] . "'>ลบ</a>";
                                    echo "<a href='update_navbar.php?edit=" . $row["id"] . "'>แก้ไข</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>ไม่มีข้อมูล</td></tr>";
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
                <form id="editForm8" action="add_header.php" enctype="multipart/form-data" style="display: none;"
                    method="post">
                    <h1>เพิ่มรูปแบนเนอร์</h1>
                    <div class="form-container">
                        <div class="form-group">
                            <input type="file" name="header" required>
                            <input type='text' name='header_button' placeholder="ข้อความในปุ่ม" required />
                            <button type="submit">อัปโหลด</button>
                        </div>
                        <a class="btn btn-info" href='edit_header.php'>แก้ไข</a>
                    </div>
                </form>
                <button onclick="toggleForm('editForm4')">ฟอมจัดการส่วนแสดงหลังจาก banner</button>
                <form id="editForm4" method="POST" action="add_content.php" style="display: none;">
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
                <form id="editForm3" method="POST" action="update_video.php" style="display: none;">
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


                <button onclick="toggleForm('editForm12')">ฟอมเพิ่มรูป design</button>
                <form id="editForm12" action="edit_upload_design.php" method="post" enctype="multipart/form-data"
                    style="display: none;">
                    <label for="image_file">เลือกรูปภาพ:</label>
                    <input type="file" name="image_file" id="image_file" required>
                    <br><br>
                    <button type="submit" name="submit">อัปโหลดรูป</button>
                </form>


                <button onclick="toggleForm('editForm2')">ฟอม ข้อมูลเกี่ยวกับฉัน</button>
                <form id="editForm2" method="POST" action="edit_contact.php" style="display: none;">
                    <input type="hidden" name="id" id="formId" placeholder="Form 2">
                    <input type="hidden" name="aboutID"
                        value="<?php echo htmlspecialchars($about['aboutID'] ?? ''); ?>">
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

                <button onclick="toggleForm('editForm10')">ฟอมข้อมูลบทความ</button>
                <form id="editForm10" method="POST" action="edit_blogs.php" enctype="multipart/form-data"
                    style="display: none;">
                    <label>หัวข้อ:</label>
                    <input type="text" name="title" required><br><br>
                    <label>คำอธิบาย:</label>
                    <textarea name="description" required></textarea><br><br>
                    <label>รูปภาพ:</label>
                    <input type="file" name="images[]" multiple required><br><br>
                    <button type="submit">บันทึก</button>
                    <div class="imageblogall">
                        <?php
                        include('../db.php'); // เชื่อมต่อฐานข้อมูล
                        
                        // ดึงข้อมูลบทความทั้งหมดจากฐานข้อมูล
                        $sql = "SELECT * FROM blogs ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // แปลง JSON ของรูปภาพกลับมาเป็น array
                                $images = json_decode($row['images'], true);

                                // รูปภาพแรก (ถ้ามี)
                        
                                $first_image = isset($images[0]) ? $images[0] : 'default.jpg'; // ใช้รูป default หากไม่มีรูปภาพ
                                ?>
                                <div class="blog-card">
                                    <!-- แสดงรูปภาพ -->
                                    <img class="photo1" src="<?php echo htmlspecialchars($first_image); ?>"
                                        alt="ภาพบทความ"></img>

                                    <!-- แสดงหัวข้อ -->
                                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                                    <!-- แสดงคำอธิบาย (ตัดคำที่ 150 ตัวอักษร) -->
                                    <p>
                                        <?php echo htmlspecialchars(mb_substr($row['description'], 0, 150)) . '...'; ?>
                                    </p>

                                    <!-- ปุ่มอ่านเพิ่ม -->
                                    <a href="edit_from_blog.php?id=<?php echo $row['id']; ?>" class="btn-edit">แก้ไข</a>
                                    <a href="delete_blog.php?id=<?php echo $row['id']; ?>" class="btn-delete"
                                        onclick="return confirm('คุณต้องการลบบทความนี้หรือไม่?')">ลบ</a>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>ไม่มีบทความในขณะนี้</p>";
                        }
                        ?>
                    </div>

                </form>



                <button onclick="toggleForm('editForm14')">จัดการข้อความ footer</button>
                <form id="editForm14" method="POST" action="text_process.php" style="display: none;">
                    <?php

                    try {
                        // สร้างตัวแปร PDO เพื่อเชื่อมต่อ
                        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                        // กำหนดโหมด error
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (PDOException $e) {
                        die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $e->getMessage());
                    }

                    // ดึงข้อมูลทั้งหมดจากตาราง messages (เพื่อแสดงรายการ)
                    $sql = "SELECT * FROM messages ORDER BY id DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // ตรวจสอบว่ามี ?edit_id=... ส่งมาไหม (แปลว่าต้องการแก้ไข)
                    $editId = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;
                    $editText = "";

                    // ถ้ามี edit_id ให้ค้นข้อมูลเดิมมาแสดงในฟอร์มแก้ไข
                    if ($editId) {
                        $sqlEdit = "SELECT * FROM messages WHERE id = :id";
                        $stmtEdit = $pdo->prepare($sqlEdit);
                        $stmtEdit->bindParam(':id', $editId, PDO::PARAM_INT);
                        $stmtEdit->execute();
                        $row = $stmtEdit->fetch(PDO::FETCH_ASSOC);

                        if ($row) {
                            $editText = $row['text'];
                        }
                    }
                    ?>


                    <title>Admin - เพิ่ม/แก้ไข/ลบข้อความ</title>
                    <h1>เพิ่มข้อความ</h1>
                    <!-- ฟอร์มเพิ่มข้อความ -->

                    <?php for ($i = 1; $i <= 1; $i++): ?>
                        <div>
                            <label>เพิ่มข้อความ <?php echo $i; ?>:</label>
                            <input type="text" name="textLine[]">
                        </div>
                    <?php endfor; ?>
                    <br>
                    <button type="submit" name="action" value="add">บันทึกข้อความ</button>


                    <hr>
                    <h2>ข้อความที่มีอยู่ footer</h2>
                    <?php if (!empty($entries)): ?>
                        <table border="1" cellpadding="5" cellspacing="0">
                            <tr>
                                <th>ข้อความ</th>
                                <th>จัดการ</th>
                            </tr>
                            <?php foreach ($entries as $item): ?>
                                <tr>
                                    <!-- แสดงข้อความ -->
                                    <td><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <!-- ปุ่มแก้ไข -> ไปที่ admin_panel.php?edit_id=... -->
                                        <a href="admin_panel.php?edit_id=<?php echo $item['id']; ?>">แก้ไข</a> |
                                        <!-- ปุ่มลบ -> ไปที่ text_process.php?action=delete&id=... -->
                                        <a href="text_process.php?action=delete&id=<?php echo $item['id']; ?>"
                                            onclick="return confirm('ต้องการลบข้อความนี้หรือไม่?');">ลบ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>ยังไม่มีข้อความ</p>
                    <?php endif; ?>
                    <hr>
                    <!-- ถ้ามีค่าจาก ?edit_id=... แสดงฟอร์มแก้ไข -->
                    <?php if ($editId): ?>
                        <h3>แก้ไขข้อความ (ID = <?php echo $editId; ?>)</h3>
                        <form action="text_process.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $editId; ?>">
                            <textarea name="editText" rows="4" cols="60"><?php
                            echo htmlspecialchars($editText, ENT_QUOTES, 'UTF-8');
                            ?></textarea>
                            <br><br>
                            <button type="submit" name="action" value="edit">บันทึกการแก้ไข</button>
                        </form>
                    <?php endif; ?>


                </form>
                <!---------------------------------------------------------------------------------------------------------------------->


                <?php
                $sql = 'select * from address where addressID = 1';
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                ?>
                <button onclick="toggleForm('editForm11')">เพิ่มที่อยู่บริษัท</button>
                <form id="editForm11" method="POST" action="address.php" style="display: none;">
                    <div class="mb-3">
                        <label for="homeNumber" class="form-label">บ้านเลขที่</label>
                        <input type="text" class="form-control" name="homeNumber" placeholder="กรอกบ้านเลขที่"
                            value="<?php echo isset($row['homeNumber']) ? $row['homeNumber'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="street" class="form-label">ถนน</label>
                        <input type="text" class="form-control" name="street" placeholder="กรอกชื่อถนน (ถ้ามี)"
                            value="<?php echo isset($row['street']) ? $row['street'] : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="subDistrict" class="form-label">ตำบล/แขวง</label>
                        <input type="text" class="form-control" name="subDistrict" placeholder="กรอกตำบล/แขวง"
                            value="<?php echo isset($row['subDistrict']) ? $row['subDistrict'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="district" class="form-label">อำเภอ/เขต</label>
                        <input type="text" class="form-control" name="district" placeholder="กรอกอำเภอ/เขต"
                            value="<?php echo isset($row['district']) ? $row['district'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="province" class="form-label">จังหวัด</label>
                        <input type="text" class="form-control" name="province"
                            value="<?php echo isset($row['province']) ? $row['province'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="postalCode" class="form-label">รหัสไปรษณีย์</label>
                        <input type="text" class="form-control" name="postalCode" placeholder="กรอกรหัสไปรษณีย์"
                            value="<?php echo isset($row['postalCode']) ? $row['postalCode'] : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">เพิ่มและแก้ไขข้อมูล</button>
                </form>


                <button onclick="toggleForm('editForm13')">อัพเดทลิ้งในส่วนท้าย</button>
                <form id="editForm13" action="footer_links.php" style="display: none;" method="post">
                    <h1>อัพเดทลิ้งในส่วนท้าย</h1>
                    <?php
                    $sql = 'SELECT * FROM footer_links LIMIT 1';
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="form-container">
                        <div class="form-group">
                            <label for="facebook">Facebook Link</label>
                            <input type="text" name="facebook" id="facebook" placeholder="URL"
                                value="<?= htmlspecialchars($row['facebook']) ?>" />
                            <label for="tiktok">Tiktok Link</label>
                            <input type="text" name="tiktok" id="tiktok" placeholder="URL"
                                value="<?= htmlspecialchars($row['tiktok']) ?>" />
                            <label for="line">Line Add</label>
                            <input type="text" name="line" id="line" placeholder="URL"
                                value="<?= htmlspecialchars($row['line']) ?>" />
                            <button type="submit">อัพเดท</button>
                        </div>
                    </div>
                </form>

            </div>

            <div class="ส่วนตัวอย่างหน้า">
                <iframe src="../index.php" class="iframe-content"></iframe>
            </div>
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
            const formCount = 12;
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
                const formCount = 12;
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