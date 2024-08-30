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
        $sql = "UPDATE tb_teacher SET 
                teacher_fullname='$teacher_fullname', 
                teacher_username='$teacher_username', 
                teacher_tel='$teacher_tel'";

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

            // อัปเดตรหัสผ่านใน session ด้วย หากมีการเปลี่ยนแปลง
            if (!empty($teacher_password)) {
                $_SESSION['user']['teacher_password'] = $teacher_password;
            }

            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');

            // เปลี่ยนเส้นทางไปยังหน้าแสดงข้อมูลครู
            echo $cls_conn->goto_page(1, 'show_teacher1.php');
        } else {
            echo $cls_conn->show_message('แก้ไขข้อมูลไม่สำเร็จ');
        }
    }
}
?>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>แก้ไขข้อมูลครู</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" class="form-horizontal form-label-left">
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
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-success">บันทึก</button>
                                <a href="index.php" class="btn btn-danger">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
