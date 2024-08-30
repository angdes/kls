<?php
include('header.php');

// ตรวจสอบว่ามี session user หรือไม่
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// ดึงข้อมูลสมาชิกจาก session
$member = $_SESSION['user'];

// ตรวจสอบการ submit แบบ POST
if (isset($_POST['submit'])) {
    // รับค่าจากฟอร์ม
    $member_fullname = $_POST['member_fullname'];
    $member_address = $_POST['member_address'];
    $member_tel = $_POST['member_tel'];
    $member_email = $_POST['member_email'];
    $member_username = $_POST['member_username'];
    $member_password = $_POST['member_password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ารหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    if (!empty($member_password) && $member_password !== $confirm_password) {
        echo $cls_conn->show_message('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน');
    } else {
        // สร้างคำสั่ง SQL สำหรับการอัปเดตข้อมูล
        $sql = "UPDATE tb_member SET 
                member_fullname='$member_fullname', 
                member_address='$member_address',
                member_tel='$member_tel',
                member_email='$member_email',
                member_username='$member_username'";
        
        // ตรวจสอบว่ามีการเปลี่ยนแปลงรหัสผ่านหรือไม่
        if (!empty($member_password)) {
            // บันทึกรหัสผ่านใหม่เป็น plain text (ไม่แนะนำในทางปฏิบัติ)
            $sql .= ", member_password='$member_password'";
        }
        
        // เติมเงื่อนไข WHERE เพื่ออัปเดตเฉพาะสมาชิกที่เป็นเจ้าของ session ปัจจุบัน
        $sql .= " WHERE member_id={$member['member_id']}";

        // ทำการ execute คำสั่ง SQL
        if ($cls_conn->write_base($sql) == true) {
            // อัปเดตข้อมูลใน session ด้วย
            $_SESSION['user']['member_fullname'] = $member_fullname;
            $_SESSION['user']['member_address'] = $member_address;
            $_SESSION['user']['member_tel'] = $member_tel;
            $_SESSION['user']['member_email'] = $member_email;
            $_SESSION['user']['member_username'] = $member_username;

            // อัปเดตรหัสผ่านใน session ด้วย หากมีการเปลี่ยนแปลง
            if (!empty($member_password)) {
                $_SESSION['user']['member_password'] = $member_password;
            }

            // แสดงข้อความแจ้งเตือนและ redirect ไปยังหน้าแสดงข้อมูลส่วนตัว
            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
            echo $cls_conn->goto_page(1, 'show_member.php'); // เปลี่ยนเส้นทางไปยังหน้าแสดงข้อมูลสมาชิกโดยไม่ต้องล็อกอินใหม่
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
                    <h2>แก้ไขข้อมูลส่วนตัว</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_fullname">ชื่อสมาชิก</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_fullname" name="member_fullname" value="<?php echo htmlspecialchars($member['member_fullname'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_address">ที่อยู่</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_address" name="member_address" value="<?php echo htmlspecialchars($member['member_address'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_tel">เบอร์โทรศัพท์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_tel" name="member_tel" value="<?php echo htmlspecialchars($member['member_tel'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_email">อีเมล</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_email" name="member_email" value="<?php echo htmlspecialchars($member['member_email'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_username">ชื่อผู้ใช้งาน</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_username" name="member_username" value="<?php echo htmlspecialchars($member['member_username'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_password">รหัสผ่านใหม่</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="member_password" name="member_password" class="form-control col-md-7 col-xs-12">
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
                                <a href="show_member.php" class="btn btn-danger">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
