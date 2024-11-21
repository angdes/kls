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
        // จัดการการอัปโหลดรูปโปรไฟล์
        $profile_pic_path = $admin['admin_profile_pic']; // ใช้เส้นทางรูปภาพที่มีอยู่แล้ว
        if (!empty($_FILES['admin_profile_pic']['name'])) {
            $target_dir = "profile_admin/";
            $target_file = $target_dir . basename($_FILES["admin_profile_pic"]["name"]);
            if (move_uploaded_file($_FILES["admin_profile_pic"]["tmp_name"], $target_file)) {
                $profile_pic_path = $target_file;
            } else {
                echo $cls_conn->show_message('อัปโหลดรูปโปรไฟล์ไม่สำเร็จ');
            }
        }

        $sql = "UPDATE tb_admin SET 
                admin_fullname='$admin_fullname', 
                admin_username='$admin_username',
                admin_email='$admin_email',
                admin_tel='$admin_tel',
                admin_profile_pic='$profile_pic_path'"; // เพิ่มการอัปเดตเส้นทางรูปโปรไฟล์

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
            $_SESSION['user']['admin_profile_pic'] = $profile_pic_path; // อัปเดตรูปโปรไฟล์ใน session

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
        display: block;
        margin-bottom: 10px;
    }
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>แก้ไขข้อมูลแอดมิน</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" class="form-horizontal form-label-left" enctype="multipart/form-data">
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
                                <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($admin['admin_email'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <!-- Profile Picture -->
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_profile_pic">รูปโปรไฟล์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if (!empty($admin['admin_profile_pic'])) : ?>
                                    <img src="<?php echo htmlspecialchars($admin['admin_profile_pic'], ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" class="profile-pic">
                                <?php else : ?>
                                    <img src="profile_admin/user.jpg" alt="Default Profile Picture" class="profile-pic">
                                <?php endif; ?>
                                <input type="file" id="admin_profile_pic" name="admin_profile_pic" class="form-control col-md-7 col-xs-12">
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
                                <button type="submit" name="submit" class="btn btn-m">บันทึก</button>
                                <a href="index.php" class="btn btn-d">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
