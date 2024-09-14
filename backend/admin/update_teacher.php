<?php include('header.php');?>
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
    .profile-pic {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>แก้ไขข้อมูลครู</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <?php
                    if(isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $sql = "SELECT * FROM tb_teacher WHERE teacher_id = $id";
                        $result = $cls_conn->select_base($sql);
                        if ($row = mysqli_fetch_array($result)) {
                            $teacher_id = $row['teacher_id'];
                            $teacher_fullname = $row['teacher_fullname'];
                            $teacher_username = $row['teacher_username'];
                            $teacher_password = $row['teacher_password'];
                            $teacher_tel = $row['teacher_tel'];
                            $teacher_profile_pic = $row['teacher_profile_pic'];
                        }
                    }
                    ?>
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="teacher_id" value="<?= $teacher_id; ?>" />
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_fullname">ชื่อผู้ดูแลระบบ<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_fullname" name="teacher_fullname" value="<?= htmlspecialchars($teacher_fullname, ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_username">ชื่อผู้ใช้งาน<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_username" name="teacher_username" value="<?= htmlspecialchars($teacher_username, ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_password">รหัสผ่าน<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="teacher_password" name="teacher_password" value="<?= htmlspecialchars($teacher_password, ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>   
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_tel">เบอร์โทรศัพท์ผู้ดูแลระบบ<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="teacher_tel" name="teacher_tel" value="<?= htmlspecialchars($teacher_tel, ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">รูปโปรไฟล์ปัจจุบัน</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if (!empty($teacher_profile_pic)): ?>
                                    <img src="<?= htmlspecialchars($teacher_profile_pic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" class="profile-pic">
                                <?php else: ?>
                                    <img src="profile_admin/user.jpg" alt="Default Profile Picture" class="profile-pic"> <!-- รูปโปรไฟล์เริ่มต้น -->
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_profile_pic">เปลี่ยนรูปโปรไฟล์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="teacher_profile_pic" name="teacher_profile_pic" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                            
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">แก้ไข</button>
                                <a href="show_teacher.php" class="btn btn-d">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                    <?php
                    if(isset($_POST['submit'])) {
                        $teacher_id = $_POST['teacher_id'];
                        $teacher_fullname = $_POST['teacher_fullname'];
                        $teacher_username = $_POST['teacher_username'];
                        $teacher_password = $_POST['teacher_password'];
                        $teacher_tel = $_POST['teacher_tel'];

                        $upload_dir = '../teacher/profile_teacher/';
                        $uploaded_file = '';
                        
                        // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
                        if (!empty($_FILES['teacher_profile_pic']['name'])) {
                            $uploaded_file = $upload_dir . basename($_FILES['teacher_profile_pic']['name']);
                            move_uploaded_file($_FILES['teacher_profile_pic']['tmp_name'], $uploaded_file);
                        }

                        // อัปเดตรูปภาพโปรไฟล์ถ้ามีการอัปโหลดใหม่
                        $sql = "UPDATE tb_teacher SET 
                                teacher_fullname='$teacher_fullname',
                                teacher_username='$teacher_username',
                                teacher_password='$teacher_password',
                                teacher_tel='$teacher_tel'";

                        if (!empty($uploaded_file)) {
                            $sql .= ", teacher_profile_pic='$uploaded_file'";
                        }

                        $sql .= " WHERE teacher_id=$teacher_id";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1,'show_teacher.php');
                        } else {
                            echo $cls_conn->show_message('แก้ไขข้อมูลไม่สำเร็จ');
                            echo $sql;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php');?>
