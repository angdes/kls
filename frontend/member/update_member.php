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
    $member_year = $_POST['member_year'];
    $member_tel = $_POST['member_tel'];

    $member_username = $_POST['member_username'];
    $member_password = $_POST['member_password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ารหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    if (!empty($member_password) && $member_password !== $confirm_password) {
        echo $cls_conn->show_message('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน');
    } else {
        // จัดการการอัปโหลดรูปภาพ
        if (isset($_FILES['member_profile_pic']) && $_FILES['member_profile_pic']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['member_profile_pic']['tmp_name'];
            $fileName = $_FILES['member_profile_pic']['name'];
            $fileSize = $_FILES['member_profile_pic']['size'];
            $fileType = $_FILES['member_profile_pic']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('jpg', 'jpeg', 'png');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = 'profile_member/';
                $dest_path = $uploadFileDir . $fileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $member_profile_pic = $dest_path;
                } else {
                    echo $cls_conn->show_message('เกิดข้อผิดพลาดในการย้ายไฟล์ไปยังไดเรกทอรีอัปโหลด');
                }
            } else {
                echo $cls_conn->show_message('การอัปโหลดล้มเหลว สามารถอัปโหลดได้เฉพาะไฟล์ JPG, JPEG และ PNG เท่านั้น');
            }
        }

        // สร้างคำสั่ง SQL สำหรับการอัปเดตข้อมูล
        $sql = "UPDATE tb_member SET 
                member_fullname='$member_fullname', 
                member_year='$member_year',
                member_tel='$member_tel',
                
                member_username='$member_username'";

        // ตรวจสอบว่ามีการเปลี่ยนแปลงรหัสผ่านหรือไม่
        if (!empty($member_password)) {
            $sql .= ", member_password='$member_password'";
        }

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
        if (isset($member_profile_pic)) {
            $sql .= ", member_profile_pic='$member_profile_pic'";
        }

        // เติมเงื่อนไข WHERE เพื่ออัปเดตเฉพาะสมาชิกที่เป็นเจ้าของ session ปัจจุบัน
        $sql .= " WHERE member_id={$member['member_id']}";

        // ทำการ execute คำสั่ง SQL
        if ($cls_conn->write_base($sql) == true) {
            // อัปเดตข้อมูลใน session ด้วย
            $_SESSION['user']['member_fullname'] = $member_fullname;
            $_SESSION['user']['member_year'] = $member_year;
            $_SESSION['user']['member_tel'] = $member_tel;

            $_SESSION['user']['member_username'] = $member_username;

            // อัปเดตรหัสผ่านใน session ด้วย หากมีการเปลี่ยนแปลง
            if (!empty($member_password)) {
                $_SESSION['user']['member_password'] = $member_password;
            }

            // อัปเดตรูปภาพโปรไฟล์ใน session ด้วย หากมีการอัปโหลดใหม่
            if (isset($member_profile_pic)) {
                $_SESSION['user']['member_profile_pic'] = $member_profile_pic;
            }

            // แสดงข้อความแจ้งเตือนและ redirect ไปยังหน้าแสดงข้อมูลส่วนตัว
            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
            echo $cls_conn->goto_page(1, 'show_member.php');
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
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>แก้ไขข้อมูลส่วนตัว</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" class="form-horizontal form-label-left" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_fullname">ชื่อสมาชิก</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_fullname" name="member_fullname" value="<?php echo htmlspecialchars($member['member_fullname'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_year">ปีการศึกษา</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_year" name="member_year" value="<?php echo htmlspecialchars($member['member_year'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control col-md-7 col-xs-12" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_tel">เบอร์โทรศัพท์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_tel" name="member_tel" value="<?php echo htmlspecialchars($member['member_tel'], ENT_QUOTES, 'UTF-8'); ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_username">ชื่อผู้ใช้งาน</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_username" name="member_username" value="<?php echo htmlspecialchars($member['member_username'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control col-md-7 col-xs-12" readonly>
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
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_profile_pic">รูปโปรไฟล์</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="member_profile_pic" name="member_profile_pic" class="form-control col-md-7 col-xs-12">
                                <?php if (!empty($member['member_profile_pic'])): ?>
                                    <img src="<?php echo htmlspecialchars($member['member_profile_pic'], ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" style="max-width: 100px; margin-top: 10px;">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">บันทึก</button>
                                <a href="show_member.php" class="btn btn-d">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>