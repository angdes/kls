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
$teacher_id = $_SESSION['user']['teacher_id']; // สมมติว่าคุณมี teacher_id ใน session

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT teacher_fullname, teacher_profile_pic FROM tb_teacher WHERE teacher_id = $teacher_id";
$result = $cls_conn->select_base($sql);

// ตรวจสอบว่ามีข้อมูลที่ตรงกันหรือไม่
if ($result && mysqli_num_rows($result) > 0) {
    $teacher_data = mysqli_fetch_assoc($result);
    $teacher_fullname = $teacher_data['teacher_fullname'];
    $teacher_profile_pic = !empty($teacher_data['teacher_profile_pic']) ? $teacher_data['teacher_profile_pic'] : 'uploads/default_teacher.jpg'; // ถ้าไม่มีรูปภาพให้ใช้รูปภาพเริ่มต้น

    // ตรวจสอบว่ารูปภาพมีอยู่ในโฟลเดอร์ที่ระบุหรือไม่
    if (!file_exists($teacher_profile_pic)) {
        $teacher_profile_pic = 'uploads/default_teacher.jpg'; // ถ้ารูปภาพไม่พบ ให้ใช้รูปภาพเริ่มต้น
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
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KLS</title>
    <link rel="icon" type="image/x-icon" href="../../images/123.jpg">

    <!-- Bootstrap -->
    <link href="../template/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../template/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../template/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../template/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../template/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../template/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="../template/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../template/build/css/custom.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ec837941fe.js" crossorigin="anonymous"></script>
</head>

<body class="nav-md" style="background-color: white;">
    <div class="container body" style="background-color: white;">
        <div class="main_container" style="background-color: white;">
            <div class="col-md-3 left_col" style="background-color: white;">
                <div class="left_col scroll-view" style="background-color: white;">
                    <div class="navbar nav_title" style="border: 0; display: flex; align-items: center;background-color: white;">
                        <a href="index.php" class="site_title">
                            <img src="../../images/123.jpg" alt="KPS" class="logo_img" style="max-width: 50px; border-radius: 50%;  margin-bottom: 6px;">
                            <span class="site_title_text" style="color: magenta; font-size: 30px; margin-left: 15px; margin: 8px 0 15 0; font-weight: bold;">K.P.</span>
                        </a>
                    </div>
                    <div class="clearfix"></div>

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu" style="background-color: white;">
                        <div class="menu_section">
                            <h3 style="color: black;">menu</h3>
                            <ul class="nav side-menu">
                                <li><a href="index.php" style="color: black;"><i class="fa fa-house"></i> หน้าแรก</a></li>
                                <li style="color: black;"><a style="color: black;"><i class="fa fa-user" style="color: black;"></i>ข้อมูลส่วนตัว<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  #FFC0CB;">
                                        <li><a href="show_teacher.php" style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลส่วนตัว</a></li>
                                    </ul>
                                </li>
                                <li><a style="color: black;"><i class="fa fa-chalkboard-user"></i>จัดการรายวิชา<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  #FFC0CB;">
                                        <li><a href="insert_subject.php" style="color: black;"><i class="fa fa-plus-square" style="color: black;"></i>เพิ่มข้อมูลรายวิชา</a></li>
                                        <li><a href="show_subject.php" style="color: black;"><i class="fa fa-list" style="color: black;"></i>แสดงข้อมูลรายวิชา</a></li>
                                    </ul>
                                </li>
                                <li><a style="color: black;"><i class="fa fa-file-pen"></i>มอบการบ้าน<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  #FFC0CB;">
                                        <li><a href="show_subjectandwork.php" style="color: black;"><i class="fa fa-list"></i>แสดงรายวิชาที่สอน</a></li>
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
                <div class="nav_menu" style="background-color: #EE82EE;">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="color: white;">
                                    <img src="<?= htmlspecialchars($teacher_profile_pic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" style="width: 33px; height: 33px; border-radius: 50%;">
                                    <span style="color: white;"><?= htmlspecialchars($teacher_fullname, ENT_QUOTES, 'UTF-8'); ?>
                                    <span class="fa fa-angle-down" style="color: white;"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="show_teacher.php">แก้ไขข้อมูลส่วนตัว</a></li>
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
