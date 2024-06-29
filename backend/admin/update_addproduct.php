<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h3>แก้ไขข้อมูลการเพิ่มห้อง</h3>
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
                        $sql = " select *  from tb_addproduct";
                        $sql .= " where";
                        $sql .= " addproduct_id=$id";
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) 
                        {
                            $addproduct_id = $row['addproduct_id'];
                            $product_id = $row['product_id'];
                            $addproduct_qty = $row['addproduct_qty'];
                            $addproduct_status = $row['addproduct_status'];
                            $addproduct_datetime = $row['addproduct_datetime'];
                        }
                    }
                    ?>
                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                        <input type="hidden" name="addproduct_id" value="<?=$addproduct_id;?>"/>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_id">รหัสห้อง<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <select  id="product_id" name="product_id" value="<?=$product_id;?>" required="required" class="form-control col-md-7 col-xs-12"> 
                                    <?php
                                        $sqld=" select * from tb_product";
                                        $resultd=$cls_conn->select_base($sqld);
                                        while($rowd=mysqli_fetch_array($resultd))
                                        {
                                            ?>
                                        <option value="<?=$rowd['product_id'];?>"><?=$rowd['product_id'];?> : <?=$rowd['product_name'];?></option>
                                         <?php
                                        }
                                    ?>
                                </select> </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="addproduct_qty">ชื่อ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="addproduct_qty" name="addproduct_qty" value="<?=$addproduct_qty;?>" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="addproduct_status">สถานะการเพิ่มห้อง<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="addproduct_status" name="addproduct_status" value="<?=$addproduct_status;?>" required="required"  class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="addproduct_datetime">วันที่การเพิ่มห้อง<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="datetime-local" id="addproduct_datetime" name="addproduct_datetime" value="<?=$addproduct_datetime;?>" required="required"  class="form-control col-md-7 col-xs-12">
                            </div>
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
                    if (isset($_POST['submit'])) {
                        $addproduct_id = $_POST['addproduct_id'];
                        $product_id = $_POST['product_id'];
                        $addproduct_qty = $_POST['addproduct_qty'];
                        $addproduct_status = $_POST['addproduct_status'];
                        $addproduct_datetime = $_POST['addproduct_datetime'];


                        $sql = " update tb_addproduct";
                        $sql .= " set";
                        $sql .= " product_id='$product_id'";
                        $sql .= " ,addproduct_qty='$addproduct_qty'";
                        $sql .= " ,addproduct_status='$addproduct_status'";
                        $sql .= " ,addproduct_datetime='$addproduct_datetime'";

                        $sql .= " where";
                        $sql .= " addproduct_id=$addproduct_id";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_addproduct.php');
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