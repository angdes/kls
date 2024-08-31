<?php 
session_start();
include('../../class_conn.php'); 

$cls_conn = new class_conn; 

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: http://localhost/kls/login.php");
    exit();
}

// ดึงข้อมูลผู้ใช้จาก session
$admin_id = $_SESSION['user']['admin_id'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT admin_fullname, admin_profile_pic FROM tb_admin WHERE admin_id = $admin_id";
$result = $cls_conn->select_base($sql);

// ตรวจสอบว่ามีข้อมูลที่ตรงกันหรือไม่
if ($result && mysqli_num_rows($result) > 0) {
    $admin_data = mysqli_fetch_assoc($result);
    $admin_fullname = $admin_data['admin_fullname'];
    $admin_profile_pic = !empty($admin_data['admin_profile_pic']) ? $admin_data['admin_profile_pic'] : 'user.jpg'; // ถ้าไม่มีรูปภาพให้ใช้รูปภาพเริ่มต้น

    // ตรวจสอบว่ารูปภาพมีอยู่ในโฟลเดอร์ที่ระบุหรือไม่
    if (!file_exists($admin_profile_pic)) {
        $admin_profile_pic = 'user.jpg'; // ถ้ารูปภาพไม่พบ ให้ใช้รูปภาพเริ่มต้น
    }
} else {
    // หากไม่พบข้อมูลในฐานข้อมูล ให้กลับไปหน้าเข้าสู่ระบบ
    header("Location: http://localhost/kls/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLS</title>
    <link rel="icon" type="image/x-icon" href="../../images/123.jpg">
    <link href="../template/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../template/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../template/vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../template/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="../template/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <link href="../template/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <link href="../template/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="../template/build/css/custom.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ec837941fe.js" crossorigin="anonymous"></script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col" style="background-color: white;">
                <div class="left_col scroll-view" style="background-color: white;">
                    <div class="navbar nav_title" style="border: 0; display: flex; align-items: center; background-color: white;">
                        <a href="index.php" class="site_title">
                            <img src="../../images/123.jpg" alt="KPS" class="logo_img" style="max-width: 50px; border-radius: 50%; margin-bottom: 6px;">
                            <span class="site_title_text" style="color: magenta; font-size: 30px; margin-left: 15px; margin: 8px 0 15 0; font-weight: bold;">K.P</span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu" style="background-color: white;">
                        <div class="menu_section">
                            <h3 style="color: black;">menu</h3>
                            <ul class="nav side-menu" style="color: black;">
                                <li><a href="index.php" style="color: black;"><i class="fa fa-house"></i> หน้าแรก </a></li>
                                <li><a style="color: black;"><i class="fa fa-user" style="color: black;"></i>ข้อมูลส่วนตัว<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  #FFC0CB;">
                                        <li><a href="show_admin1.php" style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลส่วนตัว</a></li>
                                    </ul>
                                </li>
                                <li><a style="color: black;"><i class="fa fa-users" style="color: black;"></i>จัดการผู้ดูแลระบบ<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  #FFC0CB;">
                                        <li><a href="insert_admin.php" style="color: black;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
                                                </svg> เพิ่มข้อมูลผู้ดูแล</a></li>
                                        <li><a href="show_admin.php" style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลผู้ดูแล</a></li>
                                    </ul>
                                </li>
                                <li><a style="color: black;"><i class="fa fa-chart-line"></i>จัดการประกาศ<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  #FFC0CB;">
                                        <li><a href="add_announcement.php" style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลประกาศ</a></li>
                                        <li><a href="show_announcements.php" style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลประกาศ</a></li>
                                    </ul>
                                </li>
                                <li><a style="color: black;"><i class="fa fa-users"></i>ข้อมูลสมาชิก<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  #FFC0CB;">
                                        <li><a href="insert_member.php" style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลนักเรียน</a></li>
                                        <li><a href="show_member.php" style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลนักเรียน</a></li>
                                        <li><a href="insert_teacher.php" style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลครู</a></li>
                                        <li><a href="show_teacher.php" style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลครู</a></li>
                                    </ul>
                                </li>
                                <li><a href="logout.php" style="color: black;"><i class="fa fa-sign-out"></i>ออกจากระบบ</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu" style="background-color: #C44AFD;">
                    <nav>
                        <div class="nav toggle"> <a id="menu_toggle"><i class="fa fa-bars"></i></a> </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="color: white;">
                                    <img src="<?= htmlspecialchars($admin_profile_pic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" style="width: 33px; height: 33px; border-radius: 50%;">
                                    <?= htmlspecialchars($admin_fullname, ENT_QUOTES, 'UTF-8'); ?>
                                    <span class="fa fa-angle-down"></span>
                                </a>

                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="show_admin1.php">แก้ไขข้อมูลส่วนตัว</a></li>
                                    <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i>ออกจากระบบ</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
</body>
</html>
