<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างไฟล์ PHP</title>
</head>

<body>
    <div class="Allfrom">
        <h2>สร้างไฟล์ Blog</h2>
        <div class="FromFilemanager">
            <form action="../../admin/create_file.php" method="post">
                <!-- ฟิลด์สำหรับกรอกชื่อไฟล์ -->
                <label for="filename">ชื่อไฟล์ (เช่น index1.php):</label>
                <input type="text" id="filename" name="filename" placeholder="ชื่อไฟล์ (ไม่ต้องใส่นามสกุล .php)"
                    required>
                <br><br>

                <!-- ใช้ hidden input แทน textarea -->
                <input type="hidden" id="content" name="content" value="
Hello, world!
Welcome to My Page.
This is a default content of the page.
">

                <!-- ปุ่มส่งข้อมูล -->
                <button type="submit">สร้างไฟล์</button>
            </form>
            <iframe src="Templat_page_blog.php" frameborder="0"></iframe>
        </div>
        <br><br>

        <!----------------------------------------------------------------------------------------------------------------------->
        <h2>สร้างไฟล์ contect</h2>
        <div class="FromFilemanager">
            <form action="../../admin/create_file.php" method="post">
                <!-- ฟิลด์สำหรับกรอกชื่อไฟล์ -->
                <label for="filename">ชื่อไฟล์ (เช่น index1.php):</label>
                <input type="text" id="filename" name="filename" placeholder="ชื่อไฟล์ (ไม่ต้องใส่นามสกุล .php)"
                    required>
                <br><br>

                <!-- ใช้ hidden input แทน textarea -->
                <input type="hidden" id="content" name="content" value="
Hello, world!
Welcome to My Page.
This is a default content of the page.
">

                <!-- ปุ่มส่งข้อมูล -->
                <button type="submit">สร้างไฟล์</button>
            </form>
            <iframe src="Templat_page_blog.php" frameborder="0"></iframe>
            <br><br>
            <!-- ปุ่มสำหรับยืนยันการสร้างไฟล์ -->
        </div>
    </div>
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