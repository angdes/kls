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
                            <th>ปีการศึกษา</th>
                            <td><?php echo htmlspecialchars($member['member_year'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <?php
                        // แสดงเฉพาะ 3 ตัวแรกและ 3 ตัวหลังของเบอร์โทรศัพท์ ที่เหลือแสดงเป็น xxx
                        $tel = $member['member_tel'];
                        $masked_tel = substr($tel, 0, 3) . 'xxx' . substr($tel, -3);
                        ?>
                        <tr>
                            <th>เบอร์โทรศัพท์</th>
                            <td><?php echo htmlspecialchars($masked_tel, ENT_QUOTES, 'UTF-8'); ?></td>
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
                        <a href="update_member.php"><button class="btn btn-m">แก้ไข</button></a>
                        <a href="index.php"><button class="btn btn-d">ยกเลิก</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>