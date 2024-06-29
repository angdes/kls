<?php include('header.php');?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3>เพิ่มข้อมูลผู้ใช้งาน</h3>
                        <!--<ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                        </ul>-->
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_fname">ชื่อ<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_fname" name="user_fname" required="required" placeholder="นาย/นาง/น.ส._ _ _ _" class="form-control col-md-7 col-xs-12"> </div>
                            </div>
                            
                             <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_lname">นามสกุล<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_lname" name="user_lname" required="required" placeholder="นามสกุล" class="form-control col-md-7 col-xs-12"> </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_tel">เบอร์โทรศัพท์<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_tel" name="user_tel" required="required" placeholder="0xx-xxx-xxxx" class="form-control col-md-7 col-xs-12"> </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_email">อีเมล์ <!-- <span class="required">*</span> บังคับใส่ข้อมูล --> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_email" name="user_email" placeholder="Gmail@hotmail.com" class="form-control col-md-7 col-xs-12"> </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_type">สถานะผู้ใช้งาน<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select id="user_type" name="user_type" class="form-control col-md-7 col-xs-12">
                                        <option value="Admin">ผู้ดูแลระบบ / แคชเชียร์</option>
                                        <option value="Chef">พนักงานปรุงอาหาร</option>
                                        <option value="Waiter">พนักงานเสิร์ฟ</option>
                                   </select>
                                    </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">ID เข้าสู่ระบบ<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="username" name="username" required="required" placeholder="ชื่อผู้ใช้" class="form-control col-md-7 col-xs-12"> </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">รหัสผ่าน<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="password" id="password" name="password" required="required" placeholder="รหัสผ่าน" class="form-control col-md-7 col-xs-12"> </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-success">บันทึก</button>
                                    <button type="reset" name="reset" class="btn btn-danger">ยกเลิก</button>
                                </div>
                            </div>
                        </form>
                        <?php
                        if(isset($_POST['submit']))
                        {
                            $user_fname=$_POST['user_fname'];
                            $user_lname=$_POST['user_lname'];
                            $user_tel=$_POST['user_tel'];
                            $user_email=$_POST['user_email'];
                            $user_type=$_POST['user_type'];
                            $username=$_POST['username'];
                            $password=$_POST['password'];
                            
                            $sql=" insert into tb_user(user_fname,user_lname,user_tel,user_email,user_type,username,password,user_date)";
                            $sql.=" values ('$user_fname','$user_lname','$user_tel','$user_email','$user_type','$username','$password',now())";
                            if($cls_conn->write_base($sql)==true)
                            {
                                echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
                                echo $cls_conn->goto_page(1,'show_user.php');
                            }
                            else
                            {
                                 echo $cls_conn->show_message('บันทึกข้อมูลไม่สำเร็จ');
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