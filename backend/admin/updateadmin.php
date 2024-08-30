<?php
include('header.php');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$admin = $_SESSION['user'];

if (isset($_POST['submit'])) {
    $admin_fullname = $_POST['admin_fullname'];
    $admin_username = $_POST['admin_username'];
    $admin_email = $_POST['admin_email'];
    $admin_tel = $_POST['admin_tel'];
    $admin_password = $_POST['admin_password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบการกรอกรหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    if (!empty($admin_password) && $admin_password !== $confirm_password) {
        echo $cls_conn->show_message('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน');
    } else {
        $sql = "UPDATE tb_admin SET 
                admin_fullname='$admin_fullname', 
                admin_username='$admin_username',
                admin_email='$admin_email',
                admin_tel='$admin_tel'";

        // ตรวจสอบว่ามีการกรอกรหัสผ่านใหม่หรือไม่
        if (!empty($admin_password)) {
            // บันทึกรหัสผ่านใหม่เป็น plain text (ไม่แนะนำในทางปฏิบัติ)
            $sql .= ", admin_password='$admin_password'";
        }

        $sql .= " WHERE admin_id={$admin['admin_id']}";

        if ($cls_conn->write_base($sql) == true) {
            // อัปเดตข้อมูลใน session ด้วย
            $_SESSION['user']['admin_fullname'] = $admin_fullname;
            $_SESSION['user']['admin_username'] = $admin_username;
            $_SESSION['user']['admin_email'] = $admin_email;
            $_SESSION['user']['admin_tel'] = $admin_tel;

            // อัปเดตรหัสผ่านใน session ด้วย หากมีการเปลี่ยนแปลง
            if (!empty($admin_password)) {
                $_SESSION['user']['admin_password'] = $admin_password;
            }

            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');

            // เปลี่ยนเส้นทางไปยังหน้าแสดงข้อมูลแอดมินโดยไม่ต้องเข้าสู่ระบบใหม่
            echo $cls_conn->goto_page(1, 'show_admin1.php');
        } else {
            echo $cls_conn->show_message('แก้ไขข้อมูลไม่สำเร็จ');
        }
    }
}
?>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>แก้ไขข้อมูลแอดมิน</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_fullname">ชื่อแอดมิน</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_fullname" name="admin_fullname" value="<?php echo htmlspecialchars($admin['admin_fullname'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_username">ชื่อผู้ใช้งาน</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_username" name="admin_username" value="<?php echo htmlspecialchars($admin['admin_username'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_tel">เบอร์โทรศัพท์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_tel" name="admin_tel" value="<?php echo htmlspecialchars($admin['admin_tel'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_email">อีเมล</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($admin['admin_email'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_password">รหัสผ่านใหม่</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="admin_password" name="admin_password" class="form-control col-md-7 col-xs-12">
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
