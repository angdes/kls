<?php include('header.php'); ?>
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
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>เพิ่มข้อมูลครู</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_fullname">ชื่อครู<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_fullname" name="teacher_fullname" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_username">ชื่อผู้ใช้<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_username" name="teacher_username" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_password">รหัสผ่าน<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="teacher_password" name="teacher_password" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_tel">เบอร์โทรศัพท์<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="teacher_tel" name="teacher_tel" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_profile_pic">รูปโปรไฟล์<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="teacher_profile_pic" name="teacher_profile_pic" class="form-control col-md-7 col-xs-12">
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
                        $teacher_fullname = $_POST['teacher_fullname'];
                        $teacher_username = $_POST['teacher_username'];
                        $teacher_password = $_POST['teacher_password'];
                        $teacher_tel = $_POST['teacher_tel'];
                        $teacher_profile_pic = '';

                        // ตรวจสอบว่ามีการอัปโหลดไฟล์รูปโปรไฟล์หรือไม่
                        if (isset($_FILES['teacher_profile_pic']) && $_FILES['teacher_profile_pic']['error'] === UPLOAD_ERR_OK) {
                            $upload_dir = '../teacher/profile_teacher/'; // โฟลเดอร์สำหรับเก็บไฟล์อัปโหลด
                            $upload_file = $upload_dir . basename($_FILES['teacher_profile_pic']['name']);
                            $imageFileType = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

                            // ตรวจสอบว่าเป็นไฟล์ภาพหรือไม่
                            $check = getimagesize($_FILES['teacher_profile_pic']['tmp_name']);
                            if ($check !== false) {
                                // ตรวจสอบว่ารูปภาพมีนามสกุลที่ถูกต้องหรือไม่
                                if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    // ย้ายไฟล์อัปโหลดไปยังโฟลเดอร์ที่ระบุ
                                    if (move_uploaded_file($_FILES['teacher_profile_pic']['tmp_name'], $upload_file)) {
                                        $teacher_profile_pic = $upload_file; // เก็บที่อยู่ไฟล์ไว้ในตัวแปร
                                    } else {
                                        echo $cls_conn->show_message('เกิดข้อผิดพลาดในการอัปโหลดไฟล์รูปภาพ');
                                    }
                                } else {
                                    echo $cls_conn->show_message('รองรับเฉพาะไฟล์รูปภาพประเภท JPG, JPEG, PNG, และ GIF เท่านั้น');
                                }
                            } else {
                                echo $cls_conn->show_message('ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ');
                            }
                        }

                        $sql = "INSERT INTO tb_teacher (teacher_fullname, teacher_username, teacher_password, teacher_tel, teacher_profile_pic) 
                                VALUES ('$teacher_fullname', '$teacher_username', '$teacher_password', '$teacher_tel', '$teacher_profile_pic')";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_teacher.php');
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
