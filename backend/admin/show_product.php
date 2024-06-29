<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>แสดงข้อมูลห้อง </h2>
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
                <div align="right">
                    <a href="insert_product.php">
                        <button>เพิ่มข้อมูล</button>
                    </a>
                </div>
                <table id="datatable-buttons" class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <th>รหัสห้อง</th>
                            <th>serial no.</th>
                            <th>รหัสประเภทห้อง</th>
                            <th>ชื่อห้อง</th>
                            <th>รายละเอียดห้อง</th>
                            <th>สี</th>
                            <th>จำนวนห้อง</th>
                            <th>ราคาห้อง</th>
                            <th>รูปห้อง</th>
                            <th>สถานะห้อง</th>
                            <th>วันที่</th>
                            <th>รหัสผู้ดูแลระบบ</th>



                            <th>แก้ไข</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>


                    <tbody>
                                <?php
$sql=" select 
tb_product.product_id,
tb_product.product_serial_no,
tb_type.type_id,
tb_product.product_name,
tb_product.product_detail,
tb_product.pro_colour,
tb_product.product_qty,
tb_product.product_min_qty,
tb_product.product_price,
tb_product.product_wieght,
tb_product.product_pic,
tb_product.product_status,
tb_product.product_date,
tb_admin.admin_id

FROM
tb_product
INNER JOIN tb_type ON tb_product.type_id = tb_type.type_id
INNER JOIN tb_admin ON tb_product.admin_id = tb_admin.admin_id
";

if(isset($_GET['type_id']))
{
    $sql.=" where";
    $sql.=" tb_type.type_id='".$_GET['type_id']."'";
}
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td>
                                    <?= $row['product_id']; ?>
                                </td>
                                <td>
                                    <?= $row['product_serial_no']; ?>
                                </td>
                                <td>
                                    <?= $row['type_id']; ?>
                                </td>
                                <td>
                                    <?= $row['product_name']; ?>
                                </td>
                                <td>
                                    <?= $row['product_detail']; ?>
                                </td>
                                <td>
                                    <?= $row['pro_colour']; ?>
                                </td>
                                <td>
                                    <?= $row['product_qty']; ?>
                                </td>
                                <td>
                                    <?= $row['product_min_qty']; ?>
                                </td>
                                <td>
                                    <?= $row['product_price']; ?>
                                </td>
                                <td>
                                    <?= $row['product_wieght']; ?>
                                </td>

                                <td>
                                    <img src="../../upload/<?= $row['product_pic'];?>" style="width:50px;height:50px;border-radius: 50%;">
                                </td>

                                <td>
                                            <?php
                                            switch($row['product_status']){
                                                case '0':
                                                    echo '<span style="color:green;">preorder</span>';
                                                    break;
                                                case '1':
                                                    echo '<span style="color:green;">on stock</span>';
                                                    break;
                                            }
                                            ?>
                                </td>

                                <td>
                                    <?= $row['product_date']; ?>
                                </td>
                                <td>
                                    <?= $row['admin_id']; ?>
                                </td>


                                <td>
                                    <a href="update_product.php?id=<?= $row['product_id']; ?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')"><img src="../../images/edit.png" /></a>
                                </td>
                                <td>
                                    <a href="delete_product.php?id=<?= $row['product_id']; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" /></a>
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