<form id="editForm17" action="add_member.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <input type="file" name="member_image" accept="images_all/*" required class="form-control mb-2" value="" ?
          >

        <input type="text" name="member_name" placeholder="ชื่อ-นามสกุล" required class="form-control mb-2">

        <select name="position" required class="form-control mb-2">
            <option value="">เลือกตำแหน่ง</option>
            <option value="ผู้บริหาร">ผู้บริหาร</option>
            <option value="รองผู้บริหาร">รองผู้บริหาร</option>
            <option value="ผู้จัดการ">ผู้จัดการ</option>
            <option value="พนักงาน">พนักงาน</option>
        </select>

        <button type="submit" name="submit">
            เพิ่มสมชิก
        </button>
        <a href="edit_member.php">แก้ไขสมาชิก</a>
    </div>
</form>
<style>
    /* Reset Styles */
body, h1, h2, h3, p, a {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background-color: #f5f5f5;
    color: #333;
    font-size: 16px;
    margin-top: 20px;
    margin-left: 20px;

}

/* Container Layout */
.admin_page {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    min-height: 100vh;
    overflow: hidden;
}

.allPage {
    display: flex;
    flex-direction: row;
    gap: 20px;
    margin: 20px;
}

/* Sidebar Menu */
.tabmenuBar {
    width: 250px;
    background-color: #333;
    color: white;
    padding: 20px;
    border-radius: 10px;
}

.tabmenuBar img {
    display: block;
    margin: 0 auto 20px;
    border-radius: 10px;
}

.tabmenuBar .tab {
    background-color: #444;
    margin: 10px 0;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.tabmenuBar .tab:hover {
    background-color: #555;
}

.tabmenuBar .dropdown-menu {
    background-color: #444;
    margin-top: 10px;
    padding: 10px;
    border-radius: 5px;
    display: none;
}

.tabmenuBar .dropdown-item {
    margin: 5px 0;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.tabmenuBar .dropdown-item:hover {
    background-color: #555;
}

/* Main Content Sections */
.ส่วนหน้าเว็บตัวอย่าง, .ส่วนจัดการไฟล์, .ส่วนแสดงผล {
    flex: 1;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.ส่วนหน้าเว็บตัวอย่าง h2, .ส่วนจัดการไฟล์ h2, .ส่วนแสดงผล h2 {
    margin-bottom: 20px;
    color: #444;
}

.ส่วนหน้าเว็บตัวอย่าง a, .ส่วนจัดการไฟล์ a {
    display: block;
    color: #007bff;
    margin: 5px 0;
    text-decoration: none;
    transition: color 0.3s ease;
}

.ส่วนหน้าเว็บตัวอย่าง a:hover, .ส่วนจัดการไฟล์ a:hover {
    color: #0056b3;
}

/* Iframe Styling */
.iframe-content {
    width: 100%;
    height: 400px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Dropdown Animation */
.tab.open + .dropdown-menu {
    display: block;
}

/* Responsive Design */
@media (max-width: 768px) {
    .allPage {
        flex-direction: column;
        gap: 10px;
    }

    .tabmenuBar {
        width: 100%;
    }
}

</style>