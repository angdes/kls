<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>แสดงข้อมูลครู</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="text-muted font-13 m-b-30">
                <div align="left">
                    <a href="insert_teacher.php">
                        <button class="btn btn-success">เพิ่มข้อมูล</button>
                    </a>
                </div>
                <table id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>รหัสครู</th>
                            <th>ชื่อครู</th>
                            <th>ชื่อผู้ใช้งาน</th>
                            <th>รหัสผ่าน</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>แก้ไข</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tb_teacher";
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><?=$row['teacher_id'];?></td>
                                <td><?=$row['teacher_fullname'];?></td>
                                <td><?=$row['teacher_username'];?></td>
                                <td><?=$row['teacher_password'];?></td>
                                <td><?=$row['teacher_tel'];?></td>
                                <td>
                                    <a href="update_teacher.php?id=<?=$row['teacher_id'];?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')"><img src="../../images/edit.png" /></a>
                                </td>
                                <td>
                                    <a href="delete_teacher.php?id=<?=$row['teacher_id'];?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" /></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
