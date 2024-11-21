<?php
ob_start();
include('header.php');
 // เริ่มการทำงานของ session เพื่อดึงค่า session

// ตรวจสอบว่ามีการเข้าสู่ระบบแล้วหรือไม่ และตรวจสอบ admin_id
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // ถ้าไม่มีให้กลับไปหน้า login
    exit;
}

$admin_id = $_SESSION['user']; // รับค่า admin_id ของผู้ใช้ที่ล็อกอินอยู่

// ตรวจสอบว่า $admin_id เป็น array หรือไม่ และแปลงเป็น string ถ้าจำเป็น
if (is_array($admin_id)) {
    $admin_id = implode(',', $admin_id);
}

if (!is_string($admin_id) || empty($admin_id)) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูล admin_id.";
    exit;
}
?>

<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-m:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-d {
        color: white;
        background-color: #808080;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-d:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>เพิ่มประกาศใหม่</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="add_announcement_form" class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_title">หัวข้อประกาศ<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="announcement_title" name="announcement_title" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_details">รายละเอียดประกาศ<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="announcement_details" name="announcement_details" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_image">รูปประกาศ</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="announcement_image" name="announcement_image[]" multiple class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">บันทึก</button>
                                <button type="reset" name="reset" class="btn btn-d">ยกเลิก</button>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['submit'])) {
                        $announcement_title = $_POST['announcement_title'];
                        $announcement_details = $_POST['announcement_details'];

                        // การอัพโหลดรูปภาพหลายไฟล์
                        $announcement_images = [];

                        if (isset($_FILES['announcement_image'])) {
                            $target_dir = "../uploads/";

                            foreach ($_FILES['announcement_image']['name'] as $key => $filename) {
                                if ($_FILES['announcement_image']['size'][$key] > 0) {
                                    $uploadOk = 1;
                                    $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                    $unique_filename = uniqid() . '.' . $imageFileType;
                                    $target_file = $target_dir . $unique_filename;

                                    // ตรวจสอบประเภทไฟล์ที่อนุญาต
                                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                                        echo "ไฟล์ที่อนุญาต: JPG, JPEG, PNG, GIF เท่านั้น.";
                                        $uploadOk = 0;
                                    }

                                    if ($uploadOk == 1) {
                                        if (move_uploaded_file($_FILES["announcement_image"]["tmp_name"][$key], $target_file)) {
                                            $announcement_images[] = $unique_filename;
                                        } else {
                                            echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์.";
                                        }
                                    }
                                }
                            }
                        }

                        $announcement_images_string = implode(',', $announcement_images);

                        // บันทึกข้อมูลลงในตาราง tb_announcements พร้อม admin_id
                        $sql = "INSERT INTO tb_announcements (announcement_title, announcement_details, announcement_image, admin_id)
                                VALUES ('$announcement_title', '$announcement_details', '$announcement_images_string', '$admin_id')";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_announcements.php');
                        } else {
                            echo $cls_conn->show_message('บันทึกข้อมูลไม่สำเร็จ');
                            echo $sql;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
