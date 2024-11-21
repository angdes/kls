<?php
include('header.php');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$teacher = $_SESSION['user'];

if (isset($_POST['submit'])) {
    $teacher_fullname = $_POST['teacher_fullname'];
    $teacher_username = $_POST['teacher_username'];
    $teacher_tel = $_POST['teacher_tel'];
    $teacher_password = $_POST['teacher_password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ารหัสผ่านใหม่กับยืนยันรหัสผ่านตรงกันหรือไม่
    if (!empty($teacher_password) && $teacher_password !== $confirm_password) {
        echo $cls_conn->show_message('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน');
    } else {
        // ตรวจสอบว่ามีการอัปโหลดไฟล์รูปโปรไฟล์หรือไม่
        if (isset($_FILES['teacher_profile_pic']) && $_FILES['teacher_profile_pic']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'profile_teacher/'; // โฟลเดอร์สำหรับเก็บไฟล์อัปโหลด
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
        } else {
            $teacher_profile_pic = $teacher['teacher_profile_pic']; // ใช้รูปเดิมหากไม่มีการอัปโหลดใหม่
        }

        $sql = "UPDATE tb_teacher SET 
                teacher_fullname='$teacher_fullname', 
                teacher_username='$teacher_username', 
                teacher_tel='$teacher_tel', 
                teacher_profile_pic='$teacher_profile_pic'";

        // ตรวจสอบว่ามีการกรอกรหัสผ่านใหม่หรือไม่
        if (!empty($teacher_password)) {
            // Store the new password as plain text (not recommended)
            $sql .= ", teacher_password='$teacher_password'";
        }

        $sql .= " WHERE teacher_id={$teacher['teacher_id']}";

        if ($cls_conn->write_base($sql) == true) {
            // อัปเดตข้อมูลใน session ด้วย
            $_SESSION['user']['teacher_fullname'] = $teacher_fullname;
            $_SESSION['user']['teacher_username'] = $teacher_username;
            $_SESSION['user']['teacher_tel'] = $teacher_tel;
            $_SESSION['user']['teacher_profile_pic'] = $teacher_profile_pic;

            // อัปเดตรหัสผ่านใน session ด้วย หากมีการเปลี่ยนแปลง
            if (!empty($teacher_password)) {
                $_SESSION['user']['teacher_password'] = $teacher_password;
            }

            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');

            // เปลี่ยนเส้นทางไปยังหน้าแสดงข้อมูลครู
            echo $cls_conn->goto_page(1, 'show_teacher.php');
        } else {
            echo $cls_conn->show_message('แก้ไขข้อมูลไม่สำเร็จ');
        }
    }
}
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
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>แก้ไขข้อมูลครู</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_fullname">ชื่อครู</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_fullname" name="teacher_fullname" value="<?php echo htmlspecialchars($teacher['teacher_fullname'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_username">ชื่อผู้ใช้งาน</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_username" name="teacher_username" value="<?php echo htmlspecialchars($teacher['teacher_username'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_tel">เบอร์โทรศัพท์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_tel" name="teacher_tel" value="<?php echo htmlspecialchars($teacher['teacher_tel'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_password">รหัสผ่านใหม่</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="teacher_password" name="teacher_password" class="form-control col-md-7 col-xs-12">
                                <small>หากไม่ต้องการเปลี่ยนรหัสผ่าน ให้เว้นว่างไว้</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="confirm_password">ยืนยันรหัสผ่านใหม่</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_profile_pic">รูปโปรไฟล์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="teacher_profile_pic" name="teacher_profile_pic" class="form-control col-md-7 col-xs-12">
                                <small>อัปโหลดรูปภาพใหม่ หากต้องการเปลี่ยน</small>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">บันทึก</button>
                                <a href="show_teacher.php" class="btn btn-d">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
