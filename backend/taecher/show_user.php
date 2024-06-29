<?php include('header.php');?>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h3>ข้อมูลผู้ใช้งานระบบ </h3>
                    <!--<ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>

                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>-->
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                        <div align="right">
                            <a href="insert_user.php">
                                <button>เพิ่มข้อมูล</button>
                            </a>
                        </div>
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>รหัส</th>
                                    <th>ชื่อ</th>
                                    <th>นามสกุล</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>E-mail</th>
                                    <th>สถานะผู้ใช้งาน</th>
                                    <th>ชื่อผู้ใช้</th>
                                    <th>รหัสผ่าน</th>
                                    <th>วันที่บันทึก</th>
                        
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
                             $sql=" select * from tb_user";
                             $result=$cls_conn->select_base($sql);
                             while($row=mysqli_fetch_array($result))
                             {
                                 ?>
                                    <tr>
                                        <td>
                                            <?=$row['user_id'];?>
                                        </td>
                                        <td>
                                            <?=$row['user_fname'];?>
                                        </td>
                                        <td>
                                            <?=$row['user_lname'];?>
                                        </td>
                                        <td>
                                            <?=$row['user_tel'];?>
                                        </td>
                                        <td>
                                            <?=$row['user_email'];?>
                                        </td>
                                        <td>
                                            <?=$row['user_type'];?>
                                        </td>
                                        <td>
                                            <?=$row['username'];?>
                                        </td>
                                        <td>
                                            <?=$row['password'];?>
                                        </td>
                                        <td>
                                            <?=$row['user_date'];?>
                                        </td>
                                        
                                        <td>
                                            <a href="update_user.php?id=<?=$row['user_id'];?>" onclick><img src="../../images/edit.jpg" /></a>
                                        </td>
                                        <td>
                                            <a href="delete_user.php?id=<?=$row['user_id'];?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.jpg" /></a>
                                        </td>
                                    </tr>
                                    <?php
                             }
                          ?>
                            </tbody> 
                        </table>
                </div>
                <h4> หมายเหตุ : </h4>
                <h5> Admin = เจ้าของร้าน/แคชเชียร์ </h5>
            </div>
        </div>
    </div>


    <?php include('footer.php');?>
