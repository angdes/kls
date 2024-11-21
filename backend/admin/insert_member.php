<?php include('header.php'); ?>

<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-m:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-d {
        color: white;
        background-color: #808080;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-d:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>ข้อมูลนักเรียน</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_year">ปีการศึกษา(เช่น00)<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_year" name="member_year" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_number">รหัสประจำตัวนักเรียน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_number" name="member_number" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_gender">เลือกเพศ<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="member_gender" name="member_gender" required="required" class="form-control col-md-7 col-xs-12">
                                    <option value="" disabled selected>เลือกเพศ</option>
                                    <option value="male">ชาย</option>
                                    <option value="female">หญิง</option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_fullname">ชื่อ-สกุลนักเรียน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_fullname" name="member_fullname" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_tel">เบอร์โทรศัพท์<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="member_tel" name="member_tel" required="required" class="form-control col-md-7 col-xs-12" pattern="[0-9]{10}" maxlength="10" placeholder="กรอกเบอร์โทรศัพท์ 10 หลัก">
                                <small class="text-muted">กรอกเฉพาะตัวเลข 10 หลัก เช่น 0812345678</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_username">บัญชีผู้ใช้<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_username" name="member_username" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_password">รหัสผ่าน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="member_password" name="member_password" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_status">สถานะสมาชิก<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="member_status" name="member_status" required="required" class="form-control col-md-7 col-xs-12">
                                    <option value="1">นักเรียนภาคปกติ</option>
                                    <option value="0">นักเรียนย้ายเข้า</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_profile_pic">รูปโปรไฟล์<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="member_profile_pic" name="member_profile_pic" class="form-control col-md-7 col-xs-12">
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
                    if (isset($_POST['submit'])) {
                        $member_number = $_POST['member_number'];
                        $member_fullname = $_POST['member_fullname'];
                        $member_year = $_POST['member_year'];
                        $member_tel = $_POST['member_tel'];
                        $member_username = $_POST['member_username'];
                        $member_password = $_POST['member_password'];
                        $member_status = $_POST['member_status'];
                        $member_gender = $_POST['member_gender'];
                        var_dump($member_gender);

                        $default_profile_pic = '../../frontend/member/profile_member/user.jpg';

                        if (isset($_POST['submit'])) {
                            $member_gender = $_POST['member_gender'];
                            var_dump($member_gender); // ตรวจสอบค่าที่ส่งเข้ามา

                            // กำหนดข้อมูลสำหรับการอัปโหลดรูปภาพ
                            $default_profile_pic = '../../frontend/member/profile_member/user.jpg';
                            $profile_pic = $default_profile_pic;

                            if (isset($_FILES['member_profile_pic']) && $_FILES['member_profile_pic']['size'] > 0) {
                                $target_dir = "../../frontend/member/profile_member/";
                                $target_file = $target_dir . basename($_FILES["member_profile_pic"]["name"]);
                                $uploadOk = 1;
                                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                                // ตรวจสอบว่าเป็นไฟล์รูปภาพหรือไม่
                                $check = getimagesize($_FILES["member_profile_pic"]["tmp_name"]);
                                if ($check !== false) {
                                    $uploadOk = 1;
                                } else {
                                    echo "ไฟล์นี้ไม่ใช่รูปภาพ";
                                    $uploadOk = 0;
                                }

                                // ตรวจสอบชนิดไฟล์ที่อนุญาต
                                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น";
                                    $uploadOk = 0;
                                }

                                if ($uploadOk == 0) {
                                    echo "ไม่สามารถอัปโหลดไฟล์ได้";
                                } else {
                                    if (move_uploaded_file($_FILES["member_profile_pic"]["tmp_name"], $target_file)) {
                                        $profile_pic = $target_file;
                                    } else {
                                        echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
                                    }
                                }
                            }

                            // เตรียมคำสั่ง SQL เพื่อบันทึกข้อมูลลงฐานข้อมูล
                            $sql = "INSERT INTO tb_member (member_number, member_fullname, member_year, member_tel, member_username, member_password, member_status, member_gender, member_profile_pic)";
                            $sql .= " VALUES ('$member_number', '$member_fullname', '$member_year', '$member_tel', '$member_username', '$member_password', '$member_status', '$member_gender', '$profile_pic')";

                            // บันทึกข้อมูลลงฐานข้อมูล
                            if ($cls_conn->write_base($sql)) {
                                echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
                                echo $cls_conn->goto_page(1, 'show_member.php');
                            } else {
                                echo $cls_conn->show_message('บันทึกข้อมูลไม่สำเร็จ');
                            }
                        }
                    }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('footer.php'); ?>