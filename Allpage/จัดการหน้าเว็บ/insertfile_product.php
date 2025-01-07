<?php include('../../db.php'); // เชื่อมต่อฐานข้อมูล ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างโปรดักเพิ่มเติม</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="Allfrom">
        <h2>บันทึกข้อมูลหน้า</h2>
        <div class="FromFilemanager">
            <!-- ฟอร์มที่ต้องการพับเก็บ -->
            <form action="path_to_your_php_file.php" method="post" enctype="multipart/form-data">
                <!-- ชื่อสินค้า -->
                <label for="name">ชื่อสินค้า:</label><br>
                <input type="text" id="name" name="name" maxlength="500" required><br><br>

                <!-- คำอธิบาย -->
                <label for="text_description">คำอธิบาย:</label><br>
                <input type="text" id="text_description" name="text_description" maxlength="500" required><br><br>

                <!-- รายละเอียดสินค้า -->
                <label for="text_nameproduct">รายละเอียดสินค้า:</label><br>
                <textarea id="text_nameproduct" name="text_nameproduct" rows="5" required></textarea><br><br>

                <!-- อัปโหลดภาพหลักของสินค้า -->
                <div class="dropdown">
                    <label for="img_product">เลือกรูปภาพ Banner บน :</label>
                    <input type="text" id="searchInputonTop" class="form-control mb-2"
                        placeholder="ค้นหาชื่อไฟล์ (บน)...">
                    <select id="fileDropdownproduct" name="img_product[]" class="form-select" multiple>
                        <option value="">เลือกไฟล์รูปภาพ</option>
                        <?php foreach ($image_options as $option): ?>
                            <option value="<?php echo htmlspecialchars($option['url']); ?>">
                                <?php echo htmlspecialchars($option['filename']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="previewimgproduct" style="margin-top: 10px;">
                        <p>ยังไม่ได้เลือกรูปภาพด้านบน</p>
                    </div>
                </div><br><br>

                <!-- อัปโหลดภาพเพิ่มเติม -->
                <label for="img_others">อัปโหลดภาพเพิ่มเติม:</label><br>
                <input type="file" id="img_others" name="img_others[]" accept="image/*" multiple><br><br>

                <!-- ลิงก์ไปยังโซเชียล -->
                <label for="link_Social">ลิงก์ไปยังโซเชียลมีเดีย:</label><br>
                <input type="url" id="link_Social" name="link_Social" maxlength="500"><br><br>

                <!-- ปุ่มส่งข้อมูล -->
                <button type="submit">บันทึกข้อมูล</button>
            </form>

        </div>

        <!----------------------------------------------------------------------------------------------------------------------->

        <!----------------------------------->
        <script>
            // ฟังก์ชันสำหรับการค้นหาและเลือก (ซ้าย)
            // ฟังก์ชันสำหรับการค้นหาและเลือก (ซ้าย)
            const searchInputLeft = document.getElementById('searchInputLeft');
            const fileDropdownLeft = document.getElementById('fileDropdownLeft');
            const previewLeft = document.getElementById('previewLeft');

            searchInputLeft.addEventListener('input', function () {
                const filter = this.value.toLowerCase();
                for (let i = 0; i < fileDropdownLeft.options.length; i++) {
                    const option = fileDropdownLeft.options[i];
                    // ถ้าข้อความภายใน option ตรงกับฟิลเตอร์ (filter) หรือถ้าเป็น option แรก
                    // ก็ให้แสดงผล ไม่เช่นนั้นซ่อน
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

            // ฟังก์ชันสำหรับการค้นหาและเลือก (บน)
            const searchInputonTop = document.getElementById('searchInputonTop');
            const fileDropdownonTop = document.getElementById('fileDropdownonTop');
            const previewonTop = document.getElementById('previewonTop');

            searchInputonTop.addEventListener('input', function () {
                const filter = this.value.toLowerCase();
                for (let i = 0; i < fileDropdownonTop.options.length; i++) {
                    const option = fileDropdownonTop.options[i];
                    option.style.display = option.text.toLowerCase().indexOf(filter) > -1 || i === 0 ? '' : 'none';
                }
            });

            fileDropdownonTop.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    previewonTop.innerHTML = `<img src="${selectedOption.value}" alt="Preview onTop" style="max-width: 300px;">`;
                } else {
                    previewonTop.innerHTML = '<p>ยังไม่ได้เลือกรูปภาพ (บน)</p>';
                }
            });


            function toggleForm(formId) {
                var form = document.getElementById(formId);
                if (form.style.display === "none" || form.style.display === "") {
                    form.style.display = "block";
                } else {
                    form.style.display = "none";
                }
            }

            // เพิ่มฟังก์ชันสำหรับการค้นหาไฟล์ใน dropdown
            document.addEventListener('DOMContentLoaded', function () {
                // ฟังก์ชันสำหรับค้นหาใน dropdown
                function filterDropdown(searchInputId, dropdownId) {
                    var input, filter, select, options, i, txtValue;
                    input = document.getElementById(searchInputId);
                    filter = input.value.toUpperCase();
                    select = document.getElementById(dropdownId);
                    options = select.getElementsByTagName('option');
                    for (i = 0; i < options.length; i++) {
                        txtValue = options[i].textContent || options[i].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1 || options[i].value === "") {
                            options[i].style.display = "";
                        } else {
                            options[i].style.display = "none";
                        }
                    }
                }

                // ตั้งค่าการค้นหาสำหรับแต่ละ dropdown
                document.getElementById('searchInputonTop').addEventListener('keyup', function () {
                    filterDropdown('searchInputonTop', 'fileDropdownonTop');
                });

                document.getElementById('searchInputLeft').addEventListener('keyup', function () {
                    filterDropdown('searchInputLeft', 'fileDropdownLeft');
                });

                document.getElementById('searchInputRight').addEventListener('keyup', function () {
                    filterDropdown('searchInputRight', 'fileDropdownRight');
                });

                // ตั้งค่าการแสดงภาพตัวอย่างเมื่อเลือกไฟล์
                function updatePreview(dropdownId, previewId) {
                    var select = document.getElementById(dropdownId);
                    var preview = document.getElementById(previewId);
                    var selectedOption = select.options[select.selectedIndex];
                    if (selectedOption.value) {
                        preview.innerHTML = '<img src="' + selectedOption.value + '" alt="Preview" style="max-width: 200px;">';
                    } else {
                        preview.innerHTML = '<p>ยังไม่ได้เลือกรูปภาพ</p>';
                    }
                }

                document.getElementById('fileDropdownonTop').addEventListener('change', function () {
                    updatePreview('fileDropdownonTop', 'previewonTop');
                });

                document.getElementById('fileDropdownLeft').addEventListener('change', function () {
                    updatePreview('fileDropdownLeft', 'previewLeft');
                });

                document.getElementById('fileDropdownRight').addEventListener('change', function () {
                    updatePreview('fileDropdownRight', 'previewRight');
                });
            });

            document.getElementById('fileDropdownonTop').addEventListener('change', function () {
                const preview = document.getElementById('previewonTop');
                preview.innerHTML = ''; // ล้างการแสดงผลก่อนหน้า

                const selectedOptions = Array.from(this.selectedOptions).filter(option => option.value !== '');
                if (selectedOptions.length === 0) {
                    preview.innerHTML = '<p>ยังไม่ได้เลือกรูปภาพด้านบน</p>';
                    return;
                }

                selectedOptions.forEach(option => {
                    const img = document.createElement('img');
                    img.src = option.value;
                    img.alt = option.text;
                    img.style.width = '100px';
                    img.style.marginRight = '10px';
                    preview.appendChild(img);
                });
            });
        </script>

</body>

</html>
<style>
    /* CSS ที่คุณให้มา */
    .FromFilemanager {
        display: flex;
        flex-direction: column;
        /* เปลี่ยนเป็น column เพื่อให้ปุ่มอยู่บนและฟอร์มอยู่ล่าง */
        align-items: flex-start;
        gap: 10px;
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
        width: 100%;
        /* ปรับเป็น 100% เพื่อให้ฟอร์มใช้พื้นที่ทั้งหมดในคอนเทนเนอร์ */
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

    input[type="text"],
    textarea {
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

    /* สไตล์เพิ่มเติมสำหรับปุ่มพับเก็บ */
    .toggle-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-bottom: 10px;
    }

    .toggle-button:hover {
        background-color: #0056b3;
    }
</style>