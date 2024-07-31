<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>รายการประกาศ</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>

                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <p class="text-muted font-13 m-b-30">
                <div align="left">
                            <a href="add_announcement.php"> 
                                <button class="btn btn-success">เพิ่มข้อมูล</button>
                            </a>
                        </div>
                        
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>หัวข้อ</th>
                                <th>รายละเอียด</th>
                                <th>รูปภาพ</th>
                                

                                <th>แก้ไข</th>
                                <th>ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tb_announcements ORDER BY announcement_id ";
                            $result = $cls_conn->select_base($sql);
                            $count = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                $count++;
                            ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo htmlspecialchars($row['announcement_title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['announcement_details']); ?></td>
                                    <td><img src="<?php echo htmlspecialchars($row['announcement_image']); ?>" style="max-width: 100px; max-height: 100px;"></td>
                                    <td>
                                        <a href="edit_announcement.php?id=<?php echo $row['announcement_id']; ?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')"><img src="../../images/edit.png" /></a>
                                    </td>
                                    <td>
                                        <a href="delete_announcement.php?id=<?php echo $row['announcement_id']; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" /></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
