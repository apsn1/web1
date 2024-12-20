<form id="editForm10" method="POST" action="edit_blogs.php" enctype="multipart/form-data">
                    <label>หัวข้อ:</label>
                    <input type="text" name="title" required><br><br>
                    <label>คำอธิบาย:</label>
                    <textarea name="description" required></textarea><br><br>
                    <label>รูปภาพ:</label>
                    <input type="file" name="images[]" multiple required><br><br>
                    <button type="submit">บันทึก</button>