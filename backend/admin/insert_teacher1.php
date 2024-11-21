<?php include('header.php'); ?>
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
                    <h2>เพิ่มข้อมูลครูแบบแนบไฟล์</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <!-- คำอธิบายก่อนปุ่ม -->
                            <p>ใช้ปุ่มด้านล่างเพื่อจัดการข้อมูลครู:</p>
                            <ul>
                                <li><strong>สร้างไฟล์ Excel:</strong> ดาวน์โหลดไฟล์แม่แบบเพื่อกรอกข้อมูลครู</li>
                                <li><strong>เพิ่มข้อมูลจากไฟล์ Excel:</strong> อัปโหลดไฟล์ Excel ที่มีข้อมูลครูเพื่อเพิ่มในระบบ</li>
                            </ul>
                            <!-- ปุ่มสำหรับสร้างไฟล์ Excel และอัปโหลดจากไฟล์ Excel -->
                            <button onclick="window.location.href='generate_teacher_excel.php'" class="btn btn-m">สร้างไฟล์ Excel</button>
                            <button onclick="window.location.href='upload_teacher_excel.php'" class="btn btn-d">เพิ่มข้อมูลจากไฟล์ Excel</button>
                        </div>
                    </div>

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
                                if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    if (move_uploaded_file($_FILES['teacher_profile_pic']['tmp_name'], $upload_file)) {
                                        $teacher_profile_pic = $upload_file;
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

                        // บันทึกข้อมูลครู
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