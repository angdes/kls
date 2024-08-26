<?php session_start(); 

include('../../class_conn.php'); ?>
<?php $cls_conn = new class_conn; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
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

<body class="nav-md"style="background-color: white;">
    <div class="container body"style="background-color: white;">
        <div class="main_container"style="background-color: white;" >
            <div class="col-md-3 left_col"style="background-color: white;">
                <div class="left_col scroll-view" style="background-color: white;">
                    <div class="navbar nav_title" style="border: 0; display: flex; align-items: center;background-color: white; ">
                        <a href="index.php" class="site_title">
                        <img src="../../images/123.jpg" alt="KPS" class="logo_img" style="max-width: 50px; border-radius: 50%;  margin-bottom: 6px;"> <!-- เพิ่ม style="border-radius: 50%;" เพื่อทำให้รูปเป็นวงกลม -->

                            <span class="site_title_text" style="color: magenta; font-size: 30px; margin-left: 15px; margin: 8px 0 15 0; font-weight: bold;">K.P.</span>

                        </a>
                    </div>
                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->

                    <!-- /menu profile quick info -->
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu"style="background-color: white;">
                        <div class="menu_section">
                        <h3 style="color: black;">menu</h3>

                            <ul class="nav side-menu">
                            <li><a href="index.php" style="color: black;"><i class="fa fa-house"></i> หน้าแรก</a></li>
                            <li><a style="color: black;"><i class="fa fa-user"style="color: black;"></i>ข้อมูลส่วนตัว<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu"style="background-color:  hotpink;" >
                                        
                                        <li><a href="show_teacher.php" style="color: black;" ><i class="fa fa-list"></i>แสดงข้อมูลส่วนตัว</a></li>
                                    </ul>
                                </li>
                           

                                

                                <!-- <li><a style="color: black;"><i class="fa fa-users"></i>ข้อมูลสมาชิก<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="insert_member.php"style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลนักเรียน</a></li>
                                        <li><a href="show_member.php"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลนักเรียน</a></li>

                                    </ul>
                                </li> -->

                                <li><a style="color: black;"><i class="fa fa-chalkboard-user"></i>จัดการรายวิชา<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;">
                                        
                                        <li><a href="insert_subject.php" style="color: black;"><i class="fa fa-plus-square" style="color: black;"></i>เพิ่มข้อมูลรายวิชา</a></li>
                                        <li><a href="show_subject.php" style="color: black;"><i class="fa fa-list" style="color: black;"></i>แสดงข้อมูลรายวิชา</a></li>
                                    </ul>
                                </li>

                                <li><a style="color: black;"><i class="fa fa-file-pen"></i>มอบการบ้าน<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="add_homework.php"style="color: black;"><i class="fa fa-desktop"></i>เพิ่มข้อมูลการบ้าน</a></li>
                                        <li><a href="show_homework.php"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลการบ้าน</a></li>
                                    </ul>
                                </li>
                                <!-- <li><a style="color: black;"><i class="fa fa-bullhorn" ></i>  แจ้งงาน<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="#"style="color: black;"><i class="fa fa-desktop"></i>เพิ่มข้อมูลการแจ้ง</a></li>
                                        <li><a href="#"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลแจ้ง</a></li>
                                    </ul>
                                </li> -->

                                
                                <li><a href="logout.php" style="color: black;"><i class="fa fa-sign-out"></i>ออกจากระบบ</a></li>


                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                    <!-- /menu footer buttons -->
                    <!-- /menu footer buttons -->
                </div>
            </div>
            

<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu" style="background-color: #C44AFD;">
        <nav>
            <div class="nav toggle"> 
                <a id="menu_toggle"><i class="fa fa-bars"></i></a> 
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="color: white;"> 
                        <?php
                        // ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
                        if (isset($_SESSION['user'])) {
                            // ดึงข้อมูลผู้ใช้จาก session
                            $user = $_SESSION['user'];
                            $teacher_fullname = $user['teacher_fullname'];
                        } else {
                            // หากผู้ใช้ยังไม่ได้เข้าสู่ระบบ
                            $teacher_fullname = "ครู";
                        }
                        ?>
                        <img src="../template/production/images/user.jpg" alt=""><?php echo htmlspecialchars($teacher_fullname, ENT_QUOTES, 'UTF-8'); ?>
                        <span class="fa fa-angle-down"></span>
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