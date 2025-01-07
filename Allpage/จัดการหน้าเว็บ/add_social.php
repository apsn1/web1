<div class="Allfrom">
    <h2>บันทึกข้อมูลหน้า</h2>
    <div class="FromFilemanager">
        <!-- ปุ่มสำหรับเปิด/ปิดฟอร์ม -->
        <form action="edit_social.php" method="POST">

            <label>ชื่อแพลตฟอร์ม:</label><br>
            <input type="text" name="platform_name" required><br><br>

            <label>เลือกโลโก้:</label><br>
            <select name="platform_logo" required>
                <option value="YouTube">YouTube</option>
                <option value="TikTok">TikTok</option>
                <option value="Facebook">Facebook</option>
            </select><br><br>

            <label>ลิงก์แพลตฟอร์ม:</label><br>
            <input type="text" name="platform_link" required><br><br>

            <button type="submit">เพิ่มการ์ด</button>
        </form>

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