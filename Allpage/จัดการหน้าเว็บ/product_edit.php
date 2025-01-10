<?php
include('../../db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid Request Method.");
}

$action = $_POST['action'] ?? '';
$id     = $_POST['id']     ?? '';

// ตรวจสอบ id
if (!is_numeric($id)) {
    die("Error: 'id' must be a valid number.");
}
$id = (int)$id;

// ดึงข้อมูลเก่าก่อน
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Product not found for action '$action'.");
}

$oldData = $result->fetch_assoc();
$stmt->close();

// แยกข้อมูลเก่า
$oldCover  = $oldData['src_image_cover'];
$oldImages = json_decode($oldData['src_image'], true) ?: [];

// ถ้าเป็นการกดปุ่ม Delete
if ($action === 'delete') {
    // (1) อ่านค่า delete_images[]
    $deleteArr = $_POST['delete_images'] ?? [];

    // (2) เอาภาพที่ไม่ถูกติ๊ก ออกมาเหลือใน oldImages
    $remain = array_filter($oldImages, function($path) use ($deleteArr) {
        return !in_array($path, $deleteArr);
    });
    $remain = array_values($remain); // re-index

    // (3) จะลบไฟล์จริงด้วยหรือไม่ตามต้องการ
    // foreach ($deleteArr as $delPath) {
    //     if (file_exists($delPath)) {
    //         unlink($delPath);
    //     }
    // }

    // (4) ทำ UPDATE เฉพาะ src_image (ภาพเดิมที่เหลือหลังลบ)
    $jsonRemain = json_encode($remain, JSON_UNESCAPED_SLASHES);
    $sqlDel = "UPDATE product SET src_image = ? WHERE id = ?";
    $stmtDel = $conn->prepare($sqlDel);
    $stmtDel->bind_param("si", $jsonRemain, $id);
    $stmtDel->execute();

    echo "Images deleted successfully. <a href='edit_product.php?id=$id'>Back</a>";
    exit;
}

// ถ้าเป็นปุ่ม Update
if ($action === 'update') {
    // 1) รับค่าใหม่ (name, product_name, description_product)
    $name                = $_POST['name'] ?? '';
    $product_name        = $_POST['product_name'] ?? '';
    $description_product = $_POST['description_product'] ?? '';

    // 2) อัปโหลด Cover ใหม่ (ถ้ามี)
    $newCoverPath = $oldCover;
    if (isset($_FILES['src_image_cover']) && $_FILES['src_image_cover']['error'] === UPLOAD_ERR_OK) {
        $coverDir = "../../uploads/cover/";
        if (!is_dir($coverDir)) {
            mkdir($coverDir, 0777, true);
        }
        $filename = $_FILES['src_image_cover']['name'];
        $tmpPath  = $_FILES['src_image_cover']['tmp_name'];
        $newName  = time() . "_" . preg_replace("/\s+/", "_", $filename);
        $dest     = $coverDir . $newName;

        if (move_uploaded_file($tmpPath, $dest)) {
            $newCoverPath = $dest;
            // unlink($oldCover) ตามต้องการ
        }
    }

    // 3) (Optional) รับ delete_images[] เพื่อ “ลบรูป” ในโหมด Update ด้วยก็ได้
    //    ถ้าต้องการให้ปุ่ม Update ลบด้วย
    //    แต่หากจะแยกปุ่ม delete เฉพาะ ก็ไม่ต้องทำ

    // 4) อัปโหลดรูปใหม่
    $newImages = $oldImages; // ตั้งต้นเป็นภาพเก่าทั้งหมด
    if (!empty($_FILES['src_images']['name'][0])) {
        $imgDir = "../../uploads/images/";
        if (!is_dir($imgDir)) {
            mkdir($imgDir, 0777, true);
        }
        $fileCount = count($_FILES['src_images']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $fn    = $_FILES['src_images']['name'][$i];
            $tmpFn = $_FILES['src_images']['tmp_name'][$i];
            $err   = $_FILES['src_images']['error'][$i];
            if ($err === UPLOAD_ERR_OK && is_uploaded_file($tmpFn)) {
                $newName = time() . "_" . preg_replace("/\s+/", "_", $fn);
                $dest    = $imgDir . $newName;
                if (move_uploaded_file($tmpFn, $dest)) {
                    $newImages[] = $dest;
                }
            }
        }
    }

    // 5) แปลงเป็น JSON
    $jsonImages = json_encode($newImages, JSON_UNESCAPED_SLASHES);

    // 6) UPDATE DB
    $sqlUpd = "UPDATE product
               SET
                  name = ?,
                  product_name = ?,
                  description_product = ?,
                  src_image_cover = ?,
                  src_image = ?
               WHERE id = ?";
    $stmtUpd = $conn->prepare($sqlUpd);
    $stmtUpd->bind_param("sssssi",
        $name,
        $product_name,
        $description_product,
        $newCoverPath,
        $jsonImages,
        $id
    );
    if ($stmtUpd->execute()) {
        echo "Product updated. <a href='edit_product.php?id=$id'>Back</a>";
    } else {
        echo "Error: " . $stmtUpd->error;
    }
    $stmtUpd->close();
    exit;
}

// ถ้า action ไม่ตรง
echo "No valid action. <a href='edit_product.php?id=$id'>Back</a>";
