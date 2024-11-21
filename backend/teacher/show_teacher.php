<?php
include('header.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user'])) {
    echo "กรุณาเข้าสู่ระบบก่อน";
    exit();
}

// ดึงข้อมูลผู้ใช้จาก session
$user = $_SESSION['user'];
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
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>แสดงข้อมูลส่วนตัว</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-bordered">
                    <tr>
                        <th>ชื่อครู</th>
                        <td><?php echo htmlspecialchars($user['teacher_fullname'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>ชื่อผู้ใช้งาน</th>
                        <td><?php echo htmlspecialchars($user['teacher_username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>รหัสผ่าน</th>
                        <!-- แทนที่จะโชว์รหัสผ่านจริง ใช้ดาวแทน -->
                        <td><?php echo str_repeat('*', strlen($user['teacher_password'])); ?></td>
                    </tr>
                    <tr>
                        <th>เบอร์โทรศัพท์</th>
                        <td><?php echo htmlspecialchars($user['teacher_tel'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                </table>
                <div align="right">
                    <a href="update_teacher.php"><button class="btn btn-m">แก้ไข</button></a>
                    <a href="index.php"><button class="btn btn-d">ยกเลิก</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
</body>

</html>