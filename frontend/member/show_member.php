<?php
include('header.php');

// ตรวจสอบว่ามี session user หรือไม่
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// ดึงข้อมูลสมาชิกจาก session
$member = $_SESSION['user'];

?>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>แสดงข้อมูลส่วนตัว</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr>
                            <th>ชื่อสมาชิก</th>
                            <td><?php echo htmlspecialchars($member['member_fullname'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <th>ที่อยู่</th>
                            <td><?php echo htmlspecialchars($member['member_address'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <th>เบอร์โทรศัพท์</th>
                            <td><?php echo htmlspecialchars($member['member_tel'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <th>อีเมล</th>
                            <td><?php echo htmlspecialchars($member['member_email'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <th>ชื่อผู้ใช้งาน</th>
                            <td><?php echo htmlspecialchars($member['member_username'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <th>รหัสผ่าน</th>
                            <td>
                                <?php
                                // แสดงรหัสผ่านเป็นเครื่องหมาย * ตามความยาวของรหัสผ่าน
                                echo str_repeat('*', strlen($member['member_password']));
                                ?>
                            </td>
                        </tr>
                    </table>
                    <div align="right">
                        <a href="update_member.php"><button class="btn btn-success">แก้ไข</button></a>
                        <a href="index.php"><button class="btn btn-danger">ยกเลิก</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
