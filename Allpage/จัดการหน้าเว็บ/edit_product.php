<?php
include('../../db.php');

// --- ตรวจสอบ id และ Query จาก DB เหมือนเดิม ---
if (!isset($_GET['id'])) {
    die("Error: 'id' parameter is missing.");
}

$id = $_GET['id'];
if (!is_numeric($id)) {
    die("Error: 'id' must be a valid number.");
}
$id = (int)$id;

$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Product with ID $id not found.");
}
$product = $result->fetch_assoc();
$stmt->close();

// แปลง JSON เป็น array
$images = json_decode($product['src_image'], true) ?: [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 600px;
        }
        label {
            font-weight: bold;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        button {
            background-color: #007BFF; /* ปรับสีตามต้องการ */
            color: white;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #dc3545; /* สีแดง */
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        img {
            max-width: 150px;
            margin: 5px;
        }
        .image-list {
            display: flex;
            flex-wrap: wrap;
        }
        .image-item {
            margin-right: 10px;
            text-align: center;
        }
        .image-item input[type="checkbox"] {
            margin-top: 5px;
        }
    </style>
</head>
<body>

<h1>Edit Product (ID: <?= $product['id']; ?>)</h1>

<form action="product_edit.php" method="POST" enctype="multipart/form-data">
    <!-- ส่งค่า id และ action -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']); ?>">

    <!-- ชื่อสินค้า -->
    <label for="name">Name:</label>
    <input 
        type="text" 
        id="name" 
        name="name" 
        value="<?= htmlspecialchars($product['name']); ?>" 
        required
    >

    <!-- Product Name -->
    <label for="product_name">Product Name:</label>
    <input 
        type="text" 
        id="product_name" 
        name="product_name" 
        value="<?= htmlspecialchars($product['product_name']); ?>" 
        required
    >

    <!-- Description -->
    <label for="description_product">Description:</label>
    <textarea 
        id="description_product" 
        name="description_product" 
        rows="4" 
        required
    ><?= htmlspecialchars($product['description_product']); ?></textarea>

    <!-- Cover Image -->
    <label>Current Cover Image:</label>
    <?php if (!empty($product['src_image_cover'])): ?>
        <div>
            <img src="<?= htmlspecialchars($product['src_image_cover']); ?>" alt="Cover Image">
        </div>
    <?php else: ?>
        <p style="color:#999;">No cover image.</p>
    <?php endif; ?>

    <label for="src_image_cover">Change Cover Image:</label>
    <input 
        type="file" 
        id="src_image_cover" 
        name="src_image_cover" 
        accept="image/*"
    >

    <!-- Current Images -->
    <label>Current Images:</label>
    <?php if (!empty($images)): ?>
        <div class="image-list">
            <?php foreach ($images as $imgPath): ?>
                <div class="image-item">
                    <img src="<?= htmlspecialchars($imgPath); ?>" alt="Product Image">
                    <br>
                    <!-- ติ๊กเลือกภาพที่จะลบ -->
                    <input 
                        type="checkbox" 
                        name="delete_images[]" 
                        value="<?= htmlspecialchars($imgPath); ?>"
                    >
                    ลบ
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color:#999;">No additional images found.</p>
    <?php endif; ?>

    <!-- Add New Images -->
    <label for="src_images">Add New Images:</label>
    <input 
        type="file" 
        id="src_images" 
        name="src_images[]" 
        accept="image/*" 
        multiple
    >

    <!-- ปุ่ม Update และ ปุ่ม Delete -->
    <!-- ใส่ name="action" เพื่อบอกฝั่ง server ว่ากดปุ่มไหน -->
    <button 
        type="submit" 
        name="action" 
        value="update"
    >
        Update
    </button>

    <button 
        type="submit" 
        class="delete-btn"
        name="action" 
        value="delete"
    >
        Delete Selected Images
    </button>

</form>
</body>
</html>
