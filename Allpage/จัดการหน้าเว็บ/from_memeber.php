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