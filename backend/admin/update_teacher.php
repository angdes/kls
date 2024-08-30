<?php include('header.php');?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <div class="x_title">
                        <h3>แก้ไขข้อมูลครู</h3>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <?php
                        if(isset($_GET['id']))
                        {
                            $id=$_GET['id'];
                            $sql=" select *  from tb_teacher";
                            $sql.=" where";
                            $sql.=" teacher_id=$id";
                            $result=$cls_conn->select_base($sql);
                            while($row=mysqli_fetch_array($result))
                            {
                                $teacher_id=$row['teacher_id'];
                                $teacher_fullname=$row['teacher_fullname'];
                                $teacher_username=$row['teacher_username'];
                                $teacher_password=$row['teacher_password'];
                                $teacher_tel=$row['teacher_tel'];
                            }
                        }
                        ?>
                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                            <input type="hidden" name="teacher_id" value="<?=$teacher_id;?>" />
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_fullname">ชื่อผู้ดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_fullname" name="teacher_fullname" value="<?=$teacher_fullname;?>" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>


                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_username">ชื่อผู้ใช้งาน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="teacher_username" name="teacher_username" value="<?=$teacher_username;?>" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_password">รหัสผ่าน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="teacher_password" name="teacher_password" value="<?=$teacher_password;?>" required="required"class="form-control col-md-7 col-xs-12"> </div>
                        </div>   
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_tel">เบอร์โทรศัพท์ผู้ดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="teacher_tel" name="teacher_tel" value="<?=$teacher_tel;?>" required="required" class="form-control col-md-7 col-xs-12"> </div>
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
                        if(isset($_POST['submit']))
                        {
                            $teacher_id=$_POST['teacher_id'];
                            $teacher_fullname=$_POST['teacher_fullname'];
                            
                            $teacher_username=$_POST['teacher_username'];
                            $teacher_password=$_POST['teacher_password'];
                            $teacher_tel=$_POST['teacher_tel'];
                            
                            $sql=" update tb_teacher";
                            $sql.=" set";
                            $sql.=" teacher_fullname='$teacher_fullname'";
                            
                            
                            $sql.=" ,teacher_username='$teacher_username'";
                            $sql.=" ,teacher_password='$teacher_password'";
                            $sql.=" ,teacher_tel='$teacher_tel'";
                            $sql.=" where";
                            $sql.=" teacher_id=$teacher_id";
                             
                            if($cls_conn->write_base($sql)==true)
                            {
                                echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                                echo $cls_conn->goto_page(1,'show_teacher.php');
                            }
                            else
                            {
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
    <?php include('footer.php');?>