<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>one siameRudee</title>
    <!-- Favicon-->

    
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    <!-- เพิ่มไฟล์ JS ของ Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script
      src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
      crossorigin="anonymous"
    ></script>
    <!-- Google fonts-->
    <link
      href="https://fonts.googleapis.com/css?family=Montserrat:400,700"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic"
      rel="stylesheet"
      type="text/css"
    />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="CssForIndex/index_css.css" />
    <title>SEO</title>
    <script src="scripts.js"></script>
</head>
<body>
    <?php
    include('../db.php');

    $sql = "SELECT * FROM members";
    $result = mysqli_query($conn, $sql);
    ?>

    <div class="my-5">
        <a class="btn btn-primary ms-5"  href="admin_panel.php"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-center my-3">แก้ไขข้อมูลสมาชิก</h1>
        <div class="row justify-content-center">
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="../Allpage/จัดการหน้าเว็บ/images_all/<?php echo $row['member_image']; ?>" 
                                 class="rounded-circle mb-3" 
                                 alt="<?php echo $row['member_name']; ?>"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            
                            <form action="update_member.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $row['memberID']; ?>">
                                
                                <div class="mb-3">
                                    <input type="file" 
                                           name="member_image" 
                                           class="form-control">
                                </div>

                                <div class="mb-3">
                                    <input type="text" 
                                           name="member_name" 
                                           class="form-control" 
                                           value="<?php echo $row['member_name']; ?>" 
                                           placeholder="ชื่อ-นามสกุล">
                                </div>

                                <div class="mb-3">
                                    <select name="position" class="form-select">
                                        <option value='<?php echo $row['position']; ?>'>เลือกตำแหน่ง</option>
                                        <option value="ผู้บริหาร" <?php echo ($row['position'] == 'ผู้บริหาร') ? 'selected' : ''; ?>>
                                            ผู้บริหาร
                                        </option>
                                        <option value="รองผู้บริหาร" <?php echo ($row['position'] == 'รองผู้บริหาร') ? 'selected' : ''; ?>>
                                            รองผู้บริหาร
                                        </option>
                                        <option value="ผู้จัดการ" <?php echo ($row['position'] == 'ผู้จัดการ') ? 'selected' : ''; ?>>
                                            ผู้จัดการ
                                        </option>
                                        <option value="พนักงน" <?php echo ($row['position'] == 'พนักงาน') ? 'selected' : ''; ?>>
                                            พนักงาน
                                        </option>
                                    </select>
                                </div>

                                <div class="btn-group">
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fas fa-save"></i> บันทึก
                                    </button>
                                    <a href="delete_member.php?id=<?php echo $row['memberID']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('ต้องการลบข้อมูลนี้หรือไม่?')">
                                        <i class="fas fa-trash"></i> ลบ
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        
    </div>

    <style>
    .card {
        transition: transform 0.2s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .btn-group {
        gap: 5px;
    }

    .rounded-circle {
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    </style>
</body>
</html>