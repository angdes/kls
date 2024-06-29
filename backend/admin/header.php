<?php session_start(); ?>
<?php include('../../class_conn.php'); ?>
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

<body class="nav-md">
    <div class="container body">
        <div class="main_container" >
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

                            <li><a style="color: black;"><i class="fa fa-user"style="color: black;"></i>ผู้ดูแลระบบ<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu"style="background-color:  hotpink;" >
                                        <li><a href="insert_admin.php" style="color: black;" ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
  <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
  <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
</svg>เพิ่มข้อมูลผู้ดูแล</a></li>
                                        <li><a href="show_admin.php" style="color: black;" ><i class="fa fa-list"></i>แสดงข้อมูลผู้ดูแล</a></li>
                                    </ul>
                                </li>

                                <li><a style="color: black;"><i class="fa fa-chart-line"></i>จัดการประกาศ<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="#"style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลประกาศ</a></li>
                                        <li><a href="#"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลประกาศ</a></li>

                                    </ul>
                                </li>

                                <li><a style="color: black;"><i class="fa fa-users"></i>ข้อมูลสมาชิก<span class="fa fa-chevron-down" style="color: black;"></span></a>
                                    <ul class="nav child_menu" style="background-color:  hotpink;" >
                                        <li><a href="insert_member.php"style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลนักเรียน</a></li>
                                        <li><a href="show_member.php"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลนักเรียน</a></li>
                                        <li><a href="insert_member.php"style="color: black;"><i class="fa fa-plus-square"></i>เพิ่มข้อมูลครู</a></li>
                                        <li><a href="show_member.php"style="color: black;"><i class="fa fa-list"></i>แสดงข้อมูลครู</a></li>

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
            <div class="nav toggle"> 
                <a id="menu_toggle"><i class="fa fa-bars"></i></a> 
            </div>
            <ul class="nav navbar-nav navbar-right">

                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> 
                    <?php
// เชื่อมต่อกับฐานข้อมูล
$con = mysqli_connect("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// เตรียมคำสั่ง SQL เพื่อดึงข้อมูล admin_fullname จากฐานข้อมูล
$sql = "SELECT admin_fullname FROM tb_admin WHERE admin_id = 1"; // ตั้งค่า admin_id ที่ต้องการดึงข้อมูล

// ดึงข้อมูลจากฐานข้อมูล
$result = mysqli_query($con, $sql);

// ตรวจสอบว่ามีข้อมูลที่ดึงมาหรือไม่
if (mysqli_num_rows($result) > 0) {
    // วนลูปอ่านข้อมูลที่ดึงมา (แม้ว่าจะมีเพียงรายการเดียวก็ใช้ while เพื่อความเข้ากันได้)
    while ($row = mysqli_fetch_assoc($result)) {
        // กำหนดค่าให้กับตัวแปร $admin_fullname
        $admin_fullname = $row['admin_fullname'];
        
        
    }
} else {
    // หากไม่พบข้อมูล
    echo "0 results";
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($con);
?>

                        
                        <img src="../template/production/images/user.jpg" alt=""><?php echo $admin_fullname; ?> <span class=" fa fa-angle-down"></span> 
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="../admin/show_admin.php">แก้ไขข้อมูลผู้ดูแลระบบ</a></li>
                        <!-- <li><a href="../admin/show_product.php"><i class="fa fa-sign-out pull-right"></i>แก้ไขข้อมูลสินค้า</a></li> -->
                        <!-- http://localhost/bagshop01/backend/admin/show_product.php -->
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