<?php include('header.php');?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3>แก้ไขข้อมูลผู้ใช้งาน</h3>
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
                            $sql=" select *  from tb_user";
                            $sql.=" where";
                            $sql.=" user_id=$id";
                            $result=$cls_conn->select_base($sql);
                            while($row=mysqli_fetch_array($result))
                            {
                                $user_id=$row['user_id'];
                                $user_fname=$row['user_fname'];
                                $user_lname=$row['user_lname'];
                                $user_tel=$row['user_tel'];
                                $user_email=$row['user_email'];
                                $user_type=$row['user_type'];
                                $username=$row['username'];
                                $password=$row['password'];
                                $user_date=$row['user_date'];
                            }
                        }
                        ?>
                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                            <input type="hidden" name="user_id" value="<?=$user_id;?>" />
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_fname">ชื่อ<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_fname" name="user_fname" required="required" class="form-control col-md-7 col-xs-12" value="<?=$user_fname;?>"> </div>
                            </div>
                             <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_lname">นามสกุล<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_lname" name="user_lname" required="required" class="form-control col-md-7 col-xs-12" value="<?=$user_lname;?>" > </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_tel">เบอร์โทรศัพท์<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_tel" name="user_tel" required="required" class="form-control col-md-7 col-xs-12" value="<?=$user_tel;?>" > </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_email">อีเมล์<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_email" name="user_email" required="required" class="form-control col-md-7 col-xs-12" value="<?=$user_email;?>" > </div>
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
                                    <input type="text" id="username" name="username" required="required" class="form-control col-md-7 col-xs-12" value="<?=$username;?>" > </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">รหัสผ่าน<span class="required">*</span> </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="password" name="password" required="required" class="form-control col-md-7 col-xs-12" value="<?=$password;?>" > </div>
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
                            $user_id=$_POST['user_id'];
                            $user_fname=$_POST['user_fname'];
                            $user_lname=$_POST['user_lname'];
                            $user_tel=$_POST['user_tel'];
                            $user_email=$_POST['user_email'];
                            $user_type=$_POST['user_type'];
                            $username=$_POST['username'];
                            $password=$_POST['password'];
                            
                            $sql=" update tb_user";
                            $sql.=" set";
                            $sql.=" user_fname='$user_fname'";
                            $sql.=" ,user_lname='$user_lname'";
                            $sql.=" ,user_tel='$user_tel'";
                            $sql.=" ,user_email='$user_email'";
                            $sql.=" ,user_type='$user_type'";
                            $sql.=" ,username='$username'";
                            $sql.=" ,password='$password'";
                            $sql.=" where";
                            $sql.=" user_id=$user_id";
                             
                            if($cls_conn->write_base($sql)==true)
                            {
                                echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                                echo $cls_conn->goto_page(1,'show_user.php');
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