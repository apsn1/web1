<?php
include('../../db.php');

// 1) ดึงข้อมูลจากตาราง product
$sql = "SELECT id, product_name FROM product";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products (Update & Delete)</title>
</head>
<body>
    <h1>Manage Products</h1>
    
    <!-- 
        ฟอร์มเดียว ที่จะส่งไปหน้าประมวลผล manage_products_action.php 
        เพื่อแยก Logic ของ Update กับ Delete ตามปุ่มที่กด
    -->
    <form action="managed_products.php" method="POST">
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Edit Page</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- checkbox สำหรับเลือกลบ -->
                        <td style="text-align: center;">
                            <input type="checkbox" name="delete_ids[]" value="<?= $row['id']; ?>">
                        </td>
                        
                        <!-- แสดง ID -->
                        <td><?= $row['id']; ?></td>
                        
                        <!-- แก้ไข product_name ในตารางได้เลย -->
                        <td>
                            <input type="text"
                                   name="product_name[<?= $row['id']; ?>]"
                                   value="<?= htmlspecialchars($row['product_name']); ?>"
                                   style="width: 200px;">
                        </td>

                        <!-- ลิงก์ไปหน้า edit_product.php (เฉพาะแถวเดียว) -->
                        <td>
                            <a href="edit_product.php?id=<?= $row['id']; ?>">Edit Page</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <br>

        <!-- ปุ่มอัปเดตทั้งหมด (ชื่อสินค้า) -->
        <button type="submit" name="action" value="update">
            Update All Products
        </button>

        <!-- ปุ่มลบเฉพาะสินค้าที่เลือก checkbox -->
        <button type="submit" name="action" value="delete">
            Delete Selected Products
        </button>
    </form>
</body>
</html>
