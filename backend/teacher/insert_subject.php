<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมี user อยู่ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อเพิ่มรายวิชา";
    exit();
}

// ดึงค่า teacher_id จากเซสชัน
$teacher_id = $_SESSION['user']['teacher_id'];
?>

<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
        border: 2px solid #E0E0E0;
        /* ขอบสีเทาอ่อน */
        border-radius: 5px;
        /* ทำให้ขอบมนเล็กน้อย */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* เงาเบาบางใต้ปุ่ม */
        transition: box-shadow 0.3s ease;
        /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
    }

    .btn-m:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        /* เงาชัดเจนขึ้นเมื่อ hover */
    }

    .btn-d {
        color: white;
        background-color: #808080;
        border: 2px solid #E0E0E0;
        /* ขอบสีเทาอ่อน */
        border-radius: 5px;
        /* ทำให้ขอบมนเล็กน้อย */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* เงาเบาบางใต้ปุ่ม */
        transition: box-shadow 0.3s ease;
        /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
    }

    .btn-d:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        /* เงาชัดเจนขึ้นเมื่อ hover */
    }
</style>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>เพิ่มรายวิชา</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form id="insert_subject_form" class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_pass">รหัสรายวิชา<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="subject_pass" name="subject_pass" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_name">ชื่อรายวิชา<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="subject_name" name="subject_name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_detail">รายละเอียดรายวิชา</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="subject_detail" name="subject_detail" class="form-control col-md-7 col-xs-12"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_cover">รูปปกรายวิชา</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" id="subject_cover" name="subject_cover" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id"><span class="required"></span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <!-- ใช้ type="hidden" เพื่อซ่อน input field -->
                            <input type="hidden" id="teacher_id" name="teacher_id" value="<?= htmlspecialchars($teacher_id); ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" class="btn btn-m">บันทึก</button>
                            <button type="button" name="reset" class="btn btn-d" onclick="window.location.href='show_subject.php';">ยกเลิก</button>

                        </div>
                    </div>
                </form>

                <?php
                if (isset($_POST['submit'])) {
                    $subject_pass = $_POST['subject_pass'];
                    $subject_name = $_POST['subject_name'];
                    $subject_detail = $_POST['subject_detail'];
                    $teacher_id = $_POST['teacher_id']; // ใช้ค่า teacher_id จากฟอร์ม

                    // การอัปโหลดรูปภาพ
                    $subject_cover = '';
                    if (!empty($_FILES['subject_cover']['name'])) {
                        $target_dir = "uploads/"; // ระบุไดเรกทอรีสำหรับเก็บรูปภาพ
                        $subject_cover = $target_dir . basename($_FILES["subject_cover"]["name"]);
                        move_uploaded_file($_FILES["subject_cover"]["tmp_name"], $subject_cover);
                    }

                    // Insert data into tb_subject table
                    $sql = "INSERT INTO tb_subject (subject_pass, subject_name, subject_detail, subject_cover, teacher_id)
                            VALUES ('$subject_pass', '$subject_name', '$subject_detail', '$subject_cover', '$teacher_id')";

                    if ($cls_conn->write_base($sql)) {
                        echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
                        echo $cls_conn->goto_page(1, 'show_subject.php');
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
<?php include('footer.php'); ?>
