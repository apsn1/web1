<?php
/********************************************
 * text_process.php
 ********************************************/

// แก้ค่าต่อไปนี้ตามข้อมูลของคุณ
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "website_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8",
                   $username,
                   $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $e->getMessage());
}

// รับ action (add / edit / delete)
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    // รับ textLine[] จาก admin_panel.php
    if (isset($_POST['textLine']) && is_array($_POST['textLine'])) {
        foreach ($_POST['textLine'] as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // INSERT ลงตาราง
                $sql = "INSERT INTO ctpage (text) VALUES (:txt)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':txt', $line);
                $stmt->execute();
            }
        }
    }
    // กลับหน้า admin_panel.php
    header('Location: admin_panel.php');
    exit;
}
elseif ($action === 'delete') {
    // ลบตาม id
    $idToDelete = $_GET['id'] ?? '';
    if (!empty($idToDelete)) {
        $sql = "DELETE FROM ctpage WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
        $stmt->execute();
    }
    header('Location: admin_panel.php');
    exit;
}
elseif ($action === 'edit') {
    // แก้ไขตาม id
    $idToEdit = $_POST['id'] ?? '';
    $newText  = $_POST['editText'] ?? '';

    if (!empty($idToEdit)) {
        $sql = "UPDATE ctpage SET text = :txt WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':txt', $newText, PDO::PARAM_STR);
        $stmt->bindParam(':id', $idToEdit, PDO::PARAM_INT);
        $stmt->execute();
    }
    header('Location: admin_panel.php');
    exit;
}
else {
    // ถ้าไม่มี action ที่รู้จัก -> กลับไปหน้า admin_panel.php
    header('Location: admin_panel.php');
    exit;
}
