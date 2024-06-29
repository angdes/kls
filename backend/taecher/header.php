<?php session_start(); ?>
<?php include('../../class_conn.php'); ?>


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
                                        <li><a href="update_admin.php" style="color: black;" ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-person-plus-fill" viewBox="0 0 640 512">
                                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H322.8c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1H178.3zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"/>
                                    </svg>  แก้ไขข้อมูลส่วนตัว</a></li>
                                        <li><a href="show_admin.php" style="color: black;" ><i class="fa fa-list"></i>แสดงข้อมูลส่วนตัว</a></li>
                                    </ul>
                                </li>
                           

                                

                                <li><a style="color: black;"><i class="fa fa-users"></i>ข้อมูลสมาชิก<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="insert_member.php"style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลนักเรียน</a></li>
                                        <li><a href="show_member.php"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลนักเรียน</a></li>

                                    </ul>
                                </li>

                                <li><a style="color: black;"><i class="fa fa-chalkboard-user"></i>การจัดการห้อง<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="insert_type.php" style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลห้อง</a></li>
                                        <li><a href="show_type.php"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลห้อง</a></li>
                                        <li><a href="#"style="color: black;"><i class="fa fa-plus-square"style="color: black;"></i>เพิ่มข้อมูลรายวิชา</a></li>
                                        <li><a href="#"style="color: black;"><i class="fa fa-list"style="color: black;"></i>แสดงข้อมูลวิชา</a></li>
                                    </ul>
                                </li>

                                <li><a style="color: black;"><i class="fa fa-file-pen"></i>มอบการบ้าน<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="insert_product.php"style="color: black;"><i class="fa fa-desktop"></i>เพิ่มข้อมูลการบ้าน</a></li>
                                        <li><a href="show_product.php"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลการบ้าน</a></li>
                                    </ul>
                                </li>
                                <li><a style="color: black;"><i class="fa fa-bullhorn" ></i>  แจ้งงาน<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="#"style="color: black;"><i class="fa fa-desktop"></i>เพิ่มข้อมูลการแจ้ง</a></li>
                                        <li><a href="#"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลแจ้ง</a></li>
                                    </ul>
                                </li>

                                
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
                        <div class="nav toggle"> <a id="menu_toggle"><i class="fa fa-bars"></i></a> </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <img src="../template/production/images/user.jpg" alt="">ครูผู้สอน <span class=" fa fa-angle-down"></span> </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="update_admin.php">แก้ไขข้อมูลส่วนตัว</a></li>
                                    
                                    

                                    <!-- <li><a href="../admin/show_product.php"><i class="fa fa-sign-out pull-right"></i>แก้ไขข้อมูลสินค้า</a></li> -->
                                    <!-- http://localhost/kls/backend/admin/show_product.php -->

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