<?php session_start(); ?>
<?php include('class_conn.php'); ?>
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
    <link rel="icon" type="image/x-icon" href="images/123.jpg">

    

    <!-- Bootstrap -->
    <link href="backend/template/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="backend/template/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- NProgress -->
    <link href="backend/template/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="backend/template/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="backend/template/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="backend/template/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="backend/template/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="backend/template/build/css/custom.min.css" rel="stylesheet">

    <script src="https://kit.fontawesome.com/ec837941fe.js" crossorigin="anonymous"></script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container" >
            <div class="col-md-3 left_col"style="background-color: white;">
                <div class="left_col scroll-view" style="background-color: white;">
                    <div class="navbar nav_title" style="border: 0; display: flex; align-items: center;background-color: white; ">
                        <a href="index.php" class="site_title">
                        <img src="images/123.jpg" alt="KPS" class="logo_img" style="max-width: 50px; border-radius: 50%;  margin-bottom: 6px;"> <!-- เพิ่ม style="border-radius: 50%;" เพื่อทำให้รูปเป็นวงกลม -->

                            <span class="site_title_text" style="color: magenta; font-size: 30px; margin-left: 15px; margin: 8px 0 15 0; font-weight: bold;">K.P</span>

                        </a>
                    </div>
                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->

                    <!-- /menu profile quick info -->
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu"style="background-color: white;">
                        <div class="menu_section">
                        <h3 style="color: black;">Menu</h3>

                            <ul class="nav side-menu">
                            <li><a href="index.php" style="color: black;"><i class="fa fa-house"></i> หน้าแรก</a></li>

                            <li><a href="login.php" style="color: black;"><i class="fa fa-user"style="color: black;"></i>เข้าสู๋ระบบ
                                    


                                

                               

                    
                                <li><a style="color: black;"><i class="fa fa-bullhorn" ></i>  การแจ้งเตือน<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        
                                        <li><a href="#"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลแจ้งเตือน</a></li>
                                    </ul>
                                </li>

                                
                               


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
                    <a href="login.php" class="user-profile dropdown-toggle" aria-expanded="false">
                        <img src="images/log1.jpg" alt="">กดเข้าสู่ระบบ
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

            <!-- /top navigation -->
            <!-- page content -->
</body>