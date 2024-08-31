<?php include('header.php');?>

<style>
    .btn-m{
        color: white;
        background-color: #FF00FF;
    }
    .btn-d{
        color: white;
        background-color: #BA55D3;
    }
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>ข้อมูลสมาชิก</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_number">รหัสประจำตัว<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_number" name="member_number" required="required" class="form-control col-md-7 col-xs-12"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_fullname">ชื่อสมาชิก<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_fullname" name="member_fullname" required="required" class="form-control col-md-7 col-xs-12"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_address">ที่อยู่<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_address" name="member_address" required="required" class="form-control col-md-7 col-xs-12">                            
                            </div>                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_tel">เบอร์โทรศัพท์<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="member_tel" name="member_tel" required="required" class="form-control col-md-7 col-xs-12"> 
                                <script>
                                    function number(){
                                        if(isNaN(member_tel.value)){
                                            alert("Please Insert Numbers Only");
                                            return false;
                                        }
                                    }
                                </script>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_email">อีเมล<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" id="member_email" name="member_email" required="required" class="form-control col-md-7 col-xs-12"> 
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_username">username<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_username" name="member_username" required="required" class="form-control col-md-7 col-xs-12"> 
                                <script>
                                    function checkText(){
                                        var text = document.getElementById('member_username').value;
                                        if(text.search(/[^a-zA-Z0-9\_@]/) !== -1 ) {
                                            alert('Can Only Be Specified A-Z,a-z,0-9 and _ @');
                                            document.getElementById('member_username').value = "";
                                            return false;
                                        }
                                    }
                                </script>
                            </div>
                        </div>    

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_password">รหัสผ่าน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_password" name="member_password" required="required" class="form-control col-md-7 col-xs-12"> 
                                <script>
                                    function checkPass(){
                                        var text = document.getElementById('member_password').value;
                                        if(text.search(/[^a-zA-Z0-9\_@]/) !== -1 ) {
                                            alert('Can Only Be Specified A-Z,a-z,0-9 and _ @');
                                            document.getElementById('member_password').value = "";
                                            return false;
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_status">สถานะสมาชิก<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="member_status" name="member_status" value="<?=$member_status;?>" required="required" class="form-control col-md-7 col-xs-12">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>  
                                </select>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_datetime">วันเกิด<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="datetime-local" id="member_datetime" name="member_datetime" required="required" class="form-control col-md-7 col-xs-12"> 
                            </div>
                        </div>    

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">บันทึก</button>
                                <button type="reset" name="reset" class="btn btn-d" onclick="window.location.href='show_member.php'">ยกเลิก</button>
                            </div>
                        </div>
                    </form>
                    <?php
                    if(isset($_POST['submit']))
                    {
                        $member_number=$_POST['member_number'];
                        $member_fullname=$_POST['member_fullname'];
                        $member_address=$_POST['member_address'];
                        $member_tel=$_POST['member_tel'];
                        $member_email=$_POST['member_email'];
                        $member_username=$_POST['member_username'];
                        $member_password=$_POST['member_password'];
                        $member_status=$_POST['member_status'];
                        $member_datetime=$_POST['member_datetime'];
                        
                        $sql="INSERT INTO tb_member(member_number, member_fullname, member_address, member_tel, member_email, member_username, member_password, member_status, member_datetime)";
                        $sql.=" VALUES ('$member_number', '$member_fullname', '$member_address', '$member_tel', '$member_email', '$member_username', '$member_password', '$member_status', '$member_datetime')";
                        
                        if($cls_conn->write_base($sql) == true)
                        {
                            echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_member.php');
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
