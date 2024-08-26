<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>ลบข้อมูลวิชา</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />

                    <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        
                        // ตรวจสอบว่ามีการระบุ ID วิชาหรือไม่
                        if (!empty($id)) {
                            // เตรียมคำสั่ง SQL สำหรับการลบข้อมูล
                            $sql = "DELETE FROM tb_subject WHERE subject_id = $id";
                          
                            if ($cls_conn->write_base($sql) == true) {
                                echo $cls_conn->show_message('ลบข้อมูลสำเร็จ');
                                echo $cls_conn->goto_page(1, 'show_subject.php');
                            } else {
                                echo $cls_conn->show_message('ลบข้อมูลไม่สำเร็จให้ลบข้อมูลรายวิชาในการบ้านก่อน');
                                echo $sql;
                            }
                        } else {
                            echo $cls_conn->show_message('ไม่พบข้อมูลวิชาที่จะลบ');
                        }
                    } else {
                        echo $cls_conn->show_message('ไม่มีข้อมูลวิชาที่ระบุ');
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
