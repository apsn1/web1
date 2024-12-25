<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างไฟล์ PHP</title>
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
    <div class="Allfrom">
        <h2>บันทึกข้อมูลหน้า</h2>
        <div class="FromFilemanager">
            <form method="post" action="save_page.php">
                <!-- ฟิลด์สำหรับกรอกชื่อหน้า -->
                <label for="name">ชื่อหน้า:</label>
                <input type="text" id="name" name="name" placeholder="ชื่อหน้า (เช่น about หรือ contact)" required>
                <br><br>

                <!-- ฟิลด์สำหรับกรอกชื่อหัวข้อ -->
                <label for="title">ชื่อ title:</label>
                <input type="text" id="title" name="title" placeholder="ส่วน title ของหน้า" required>
                <br><br>

                <label for="headder">ชื่อหัวข้อ:</label>
                <input type="text" id="headder" name="headder" placeholder="หัวข้อหลักของหน้า" required>
                <br><br>

                <!-- ฟิลด์สำหรับกรอกข้อความด้านบน -->
                <label for="body_top">ข้อความด้านบน:</label>
                <textarea id="body_top" name="body_top" rows="4" cols="50"
                    placeholder="ข้อความด้านบนของหน้า"></textarea>
                <br><br>

                <!-- ฟิลด์สำหรับกรอกเนื้อหาด้านซ้าย -->
                <label for="text_left">เนื้อหาด้านซ้าย:</label>
                <textarea id="text_left" name="text_left" rows="4" cols="50"
                    placeholder="เนื้อหาที่แสดงด้านซ้าย"></textarea>
                <br><br>

                <label for="text_right">เนื้อหาด้านขวา</label>
                <textarea id="text_right" name="text_right" rows="4" cols="50"
                    placeholder="เนื้อหาที่แสดงด้านขวา"></textarea>
                <br><br>


                <?php
                // เชื่อมต่อฐานข้อมูล
                include('../../db.php'); // แก้ไขเป็นไฟล์เชื่อมต่อฐานข้อมูลของคุณ
                
                // ดึงข้อมูลรูปภาพจากฐานข้อมูล
                $query = "SELECT id, filename FROM images_all"; // แทนที่ images_all ด้วยชื่อตารางจริง
                $result = mysqli_query($conn, $query);

                $image_options = [];
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $image_url = "images_all/" . $row['filename']; // ระบุ path ที่เก็บภาพ
                        $image_options[] = ['filename' => $row['filename'], 'url' => $image_url];
                    }
                }
                ?>
                <!-- ฟิลด์สำหรับกรอก URL รูปภาพด้านซ้าย -->
                <div class="dropdown">
                    <label for="img_left">เลือกรูปภาพด้านซ้าย:</label>
                    <input type="text" id="searchInputLeft" class="form-control mb-2"
                        placeholder="ค้นหาชื่อไฟล์ (ซ้าย)...">
                    <select id="fileDropdownLeft" name="img_left" class="form-select">
                        <option value="">เลือกไฟล์รูปภาพ</option>
                        <?php foreach ($image_options as $option): ?>
                            <option value="<?php echo $option['url']; ?>"><?php echo $option['filename']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="previewLeft" style="margin-top: 10px;">
                        <p>ยังไม่ได้เลือกรูปภาพ (ซ้าย)</p>
                    </div>
                </div>
                <br><br>

                <!-- ฟิลด์สำหรับกรอก URL รูปภาพด้านขวา -->
                <div class="dropdown">
                    <label for="img_right">เลือกรูปภาพด้านขวา:</label>
                    <input type="text" id="searchInputRight" class="form-control mb-2"
                        placeholder="ค้นหาชื่อไฟล์ (ขวา)...">
                    <select id="fileDropdownRight" name="img_right" class="form-select">
                        <option value="">เลือกไฟล์รูปภาพ</option>
                        <?php foreach ($image_options as $option): ?>
                            <option value="<?php echo $option['url']; ?>"><?php echo $option['filename']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="previewRight" style="margin-top: 10px;">
                        <p>ยังไม่ได้เลือกรูปภาพ (ขวา)</p>
                    </div>
                </div>
                <br><br>

                <!-- ฟิลด์สำหรับกรอก SEO Title -->
                <label for="seo_title">SEO Title:</label>
                <input type="text" id="seo_title" name="seo_title" placeholder="ชื่อสำหรับ SEO">
                <br><br>

                <!-- ฟิลด์สำหรับกรอก SEO Description -->
                <label for="seo_description">SEO Description:</label>
                <textarea id="seo_description" name="seo_description" rows="4" cols="50"
                    placeholder="คำอธิบายสำหรับ SEO"></textarea>
                <br><br>

                <!-- ฟิลด์สำหรับกรอก SEO Keyword -->
                <label for="seo_keyword">SEO Keyword:</label>
                <input type="text" id="seo_keyword" name="seo_keyword" placeholder="คีย์เวิร์ดสำหรับ SEO">
                <br><br>

                <!-- ปุ่มส่งข้อมูล -->
                <button type="submit" class="btn btn-primary">บันทึก</button>
            </form>

            <iframe src="Templat_page_blog.php" frameborder="0"></iframe>
        </div>
        <br><br>
    </div>

    <!----------------------------------------------------------------------------------------------------------------------->
   
    <script>
        // ฟังก์ชันสำหรับการค้นหาและเลือก (ซ้าย)
        const searchInputLeft = document.getElementById('searchInputLeft');
        const fileDropdownLeft = document.getElementById('fileDropdownLeft');
        const previewLeft = document.getElementById('previewLeft');

        searchInputLeft.addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            for (let i = 0; i < fileDropdownLeft.options.length; i++) {
                const option = fileDropdownLeft.options[i];
                option.style.display = option.text.toLowerCase().indexOf(filter) > -1 || i === 0 ? '' : 'none';
            }
        });

        fileDropdownLeft.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                previewLeft.innerHTML = `<img src="${selectedOption.value}" alt="Preview Left" style="max-width: 300px;">`;
            } else {
                previewLeft.innerHTML = '<p>ยังไม่ได้เลือกรูปภาพ (ซ้าย)</p>';
            }
        });

        // ฟังก์ชันสำหรับการค้นหาและเลือก (ขวา)
        const searchInputRight = document.getElementById('searchInputRight');
        const fileDropdownRight = document.getElementById('fileDropdownRight');
        const previewRight = document.getElementById('previewRight');

        searchInputRight.addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            for (let i = 0; i < fileDropdownRight.options.length; i++) {
                const option = fileDropdownRight.options[i];
                option.style.display = option.text.toLowerCase().indexOf(filter) > -1 || i === 0 ? '' : 'none';
            }
        });

        fileDropdownRight.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                previewRight.innerHTML = `<img src="${selectedOption.value}" alt="Preview Right" style="max-width: 300px;">`;
            } else {
                previewRight.innerHTML = '<p>ยังไม่ได้เลือกรูปภาพ (ขวา)</p>';
            }
        });
    </script>
</body>

</html>
<style>
    .FromFilemanager {
        display: flex;
    }

    /* รีเซ็ต Margin และ Padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        margin: 20px;
    }

    h2 {
        text-align: center;
        color: #007bff;
        margin-bottom: 20px;
    }

    .container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    form {
        background: #fff;
        padding: 20px;
        width: 48%;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
        color: #555;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    button {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #218838;
    }

    iframe {
        width: 48%;
        height: 400px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>