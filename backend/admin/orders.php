<?php include('header.php');?>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>แสดงข้อมูลที่อยู่คำสั่งซื้อ </h2>
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
                         
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>รหัสการสั่งซื้อ</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>ชื่อ-สกุล</th>
                                    <th>ที่อยู่</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>E-mail</th>


                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
                             $sql=" select * from orders";
                             $result=$cls_conn->select_base($sql);
                             while($row=mysqli_fetch_array($result))
                             {
                                 ?>
                                    <tr>
                                        <td>
                                            <?=$row['OrderID'];?>
                                        </td>
                                        <td>
                                            <?=$row['OrderDate'];?>
                                        </td>
                                        <td>
                                            <?=$row['Name'];?>
                                        </td>
                                        <td>
                                            <?=$row['Address'];?>
                                        </td>
                                        <td>
                                            <?=$row['Tel'];?>
                                        </td>
                                        <td>
                                            <?=$row['Email'];?>
                                        </td>
                                        
                                        
                                        
                                       
                                        <td>
                                            <a href="delete_orders.php?id=<?=$row['OrderID'];?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" /></a>
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


    <?php include('footer.php');?>