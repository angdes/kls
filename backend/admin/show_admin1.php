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
    
    .btn-m{
        color: white;
        background-color: #FF00FF;
    }
    .btn-d{
        color: white;
        background-color: #BA55D3;
    }
</style>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title" >
                <h2 style="color: black;">แสดงข้อมูลส่วนตัว</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-bordered" style="color: black;">
                    <tr>
                        <th>ชื่อแอดมิน</th>
                        <td><?php echo htmlspecialchars($user['admin_fullname'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>เบอร์โทรศัพท์</th>
                        <td><?php echo htmlspecialchars($user['admin_tel'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>อีเมล</th>
                        <td><?php echo htmlspecialchars($user['admin_email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>ชื่อผู้ใช้งาน</th>
                        <td><?php echo htmlspecialchars($user['admin_username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>รหัสผ่าน</th>
                        <td><?php echo str_repeat('*', strlen($user['admin_password'])); ?></td>
                    </tr>
                </table>
                <div align="right">
                    <a href="updateadmin.php"><button class="btn btn-m">แก้ไข</button></a>
                    <a href="index.php"><button class="btn btn-d">ยกเลิก</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
</body>
</html>
