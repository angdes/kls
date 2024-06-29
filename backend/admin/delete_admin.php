<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>ลบข้อมูลผู้ดูแลระบบ</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />

                    <?php
                    // ตรวจสอบว่ามีการส่งค่า id มาจาก URL หรือไม่
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        // เชื่อมต่อกับฐานข้อมูล
                        $con = mysqli_connect("localhost", "root", "", "myproject");

                        // ตรวจสอบการเชื่อมต่อ
                        if (mysqli_connect_errno()) {
                            echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        }

                        // เตรียมคำสั่ง SQL เพื่อลบข้อมูล
                        $sql = "DELETE FROM tb_admin WHERE admin_id = $id";

                        // ทำการลบข้อมูล
                        if (mysqli_query($con, $sql)) {
                            echo "ลบข้อมูลสำเร็จ";
                            // ส่งกลับไปยังหน้าที่ต้องการหลังจากลบข้อมูลเสร็จสิ้น
                            echo $cls_conn->goto_page(1,'show_admin.php');
                            exit;
                        } else {
                            echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . mysqli_error($con);
                        }

                        // ปิดการเชื่อมต่อฐานข้อมูล
                        mysqli_close($con);
                    } else {
                        echo "ไม่พบ ID ที่ต้องการลบ";
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
