<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>ลบข้อมูลประกาศ</h2>
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
                        $sql = "DELETE FROM tb_announcements WHERE announcement_id = $id";

                        // ทำการลบข้อมูล
                        if (mysqli_query($con, $sql)) {
                            echo $cls_conn->show_message('ลบข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_announcements.php'); // นำผู้ใช้ไปยังหน้า show_member.php
                            exit;
                        } else {
                            echo $cls_conn->show_message('ลบข้อมูลไม่สำเร็จ');
                            echo "Error: " . $sql . "<br>" . mysqli_error($con);
                        }

                        // ปิดการเชื่อมต่อฐานข้อมูล
                        mysqli_close($con);
                    } else {
                        echo $cls_conn->show_message('ไม่พบ ID ที่ต้องการลบ');
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
