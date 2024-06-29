<?php include('header.php');?>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h3>แสดงข้อมูลธนาคาร </h3>
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
                            <a href="insert_bank.php">
                                <button>เพิ่มข้อมูล</button>
                            </a>
                        </div>
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>รหัสธนาคาร</th>
                                    <th>ประเภทธนาคาร</th>
                                    <th>ชื่อ-สกุล</th>
                                    <th>ชื่อธนาคาร</th>
                                    <th>เลขบัญชี</th>
                                    <th>สถานะธนาคาร</th>
                                    <th>รหัสผู้ดูแลระบบ</th>
                        
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
                             $sql=" select * from tb_bank";
                             $result=$cls_conn->select_base($sql);
                             while($row=mysqli_fetch_array($result))
                             {
                                 ?>
                                    <tr>
                                        <td>
                                            <?=$row['bank_id'];?>
                                        </td>
                                        <td>
                                            <?=$row['bank_type'];?>
                                        </td>
                                        <td>
                                            <?=$row['bank_name'];?>
                                        </td>
                                        <td>
                                            <?=$row['bank_bankname'];?>
                                        </td>
                                        <td>
                                            <?=$row['bank_accountno'];?>
                                        </td>
                                        <td>
                                            <?=$row['bank_status'];?>
                                        </td>
                                        <td>
                                            <?=$row['admin_id'];?>
                                        </td>

                                        
                                        <td>
                                            <a href="update_bank.php?id=<?=$row['bank_id'];?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')"><img src="../../images/edit.png" /></a>
                                        </td>
                                        <td>
                                            <a href="delete_bank.php?id=<?=$row['bank_id'];?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" /></a>
                                        </td>
                                    </tr>
                                    <?php
                             }
                          ?>
                            </tbody> 
                        </table>
                </div>
                <h4> หมายเหตุ : </h4>
                <h5> Admin = เจ้าของรีสอร์ท/แคชเชียร์</h5>
            </div>
        </div>
    </div>


    <?php include('footer.php');?>
