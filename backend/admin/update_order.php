<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h3>แก้ไขข้อมูลการจอง</h3>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <?php
                    if (isset($_GET['id'])) 
                    {
                        $id = $_GET['id'];
                        $sql = " select *  from tb_order";
                        $sql .= " where";
                        $sql .= " order_id=$id";
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) 
                        {
                            $order_id = $row['order_id'];
                            $order_no = $row['order_no'];
                            $order_total = $row['order_total'];
                            $order_status = $row['order_status'];
                            $order_datetime = $row['order_datetime'];
                            $orderdetail_id = $row['orderdetail_id'];
                        }
                    }

                    ?>

                    <form no="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                        <input type="hidden" name="order_id" value="<?= $order_id; ?>"/>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="order_no">หมายเลขการจอง<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="order_no" name="order_no" value="<?=$order_no;?>"required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="order_total">ราคา<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="number" id="order_total" name="order_total" value="<?=$order_total;?>"required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="order_status">สถานะการจอง<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="order_status" name="order_status" value="<?= $order_status; ?>" required="required" class="form-control col-md-7 col-xs-12">
                                    <option value="">--สถานะการจอง--</option>
                                    <option value="0">ลูกค้ากำลังดำเนินการ</option>
                                    <option value="1">ดำเนินการเสร็จสิ้น</option>
                                </select>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="order_datetime">วันที่สั่งซื้อ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="datetime-local" id="order_datetime" name="order_datetime" value="<?=$order_datetime;?>"required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                       

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-success">แก้ไข</button>
                                <button type="reset" name="reset" class="btn btn-danger">ยกเลิก</button>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) 
                    {
                        $order_id = $_POST['order_id'];
                        $order_no = $_POST['order_no'];
                        $order_total = $_POST['order_total'];
                        $order_status = $_POST['order_status'];
                        $order_datetime = $_POST['order_datetime'];



                        $sql = " update tb_order";
                        $sql .= " set";
                        $sql .= " order_no='$order_no'";
                        $sql .= " ,order_total='$order_total'";
                        $sql .= " ,order_status='$order_status'";
                        $sql .= " ,order_datetime='$order_datetime'";

                        $sql .= " where";
                        $sql .= " order_id=$order_id";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_order.php');
                        } else {
                            echo $cls_conn->show_message('แก้ไขข้อมูลไม่สำเร็จ');
                            echo $sql;
                        }
                    }

                    ?>



                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>