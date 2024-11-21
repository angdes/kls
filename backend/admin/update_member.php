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
                    <h2>แก้ไขข้อมูลสมาชิก</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />

                    <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $sql = "SELECT * FROM tb_member WHERE member_id = $id";
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) {
                            $member_id = $row['member_id'];
                            $member_year = $row['member_year'];
                            $member_number = $row['member_number'];
                            $member_fullname = $row['member_fullname'];
                            $member_gender = $row['member_gender']; // เพิ่มการดึงเพศ
                            $member_tel = $row['member_tel'];
                            $member_username = $row['member_username'];
                            $member_password = $row['member_password'];
                            $member_status = $row['member_status'];
                            $member_profile_pic = $row['member_profile_pic'];
                        }
                    }
                    ?>

                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="member_id" value="<?= $member_id; ?>" />

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_year">ปีการศึกษา<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_year" name="member_year" value="<?= $member_year; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_number">รหัสประจำตัว<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_number" name="member_number" value="<?= $member_number; ?>" required="required" class="form-control col-md-7 col-xs-12" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_gender">เพศ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="member_gender" name="member_gender" required="required" class="form-control col-md-7 col-xs-12">
                                    <option value="male" <?= $member_gender == 'male' ? 'selected' : ''; ?>>ชาย</option>
                                    <option value="female" <?= $member_gender == 'female' ? 'selected' : ''; ?>>หญิง</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_fullname">ชื่อสมาชิก<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_fullname" name="member_fullname" value="<?= $member_fullname; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_tel">เบอร์โทรศัพท์<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="member_tel" name="member_tel" value="<?= $member_tel; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_username">username<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="member_username" name="member_username" value="<?= $member_username; ?>" required="required" class="form-control col-md-7 col-xs-12" readonly>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_password">รหัสผ่าน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="member_password" name="member_password" value="<?= $member_password; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_status">สถานะสมาชิก<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="member_status" name="member_status" required="required" class="form-control col-md-7 col-xs-12">
                                    <option value="1" <?= $member_status == '1' ? 'selected' : ''; ?>>นักเรียนภาคปกติ</option>
                                    <option value="0" <?= $member_status == '0' ? 'selected' : ''; ?>>นักเรียนย้ายเข้า</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_profile_pic">รูปโปรไฟล์<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if (!empty($member_profile_pic)): ?>
                                    <img src="<?= htmlspecialchars($member_profile_pic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                                <?php endif; ?>
                                <input type="file" id="member_profile_pic" name="member_profile_pic" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">แก้ไข</button>
                                <button type="reset" name="reset" class="btn btn-d" onclick="window.location.href='show_member.php'">ยกเลิก</button>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        $member_fullname = $_POST['member_fullname'];
                        $member_year = $_POST['member_year'];
                        $member_tel = $_POST['member_tel'];
                        $member_gender = $_POST['member_gender'];
                        $member_username = $_POST['member_username'];
                        $member_password = $_POST['member_password'];
                        $member_status = $_POST['member_status'];

                        if (isset($_FILES['member_profile_pic']) && $_FILES['member_profile_pic']['error'] === UPLOAD_ERR_OK) {
                            $fileTmpPath = $_FILES['member_profile_pic']['tmp_name'];
                            $fileName = $_FILES['member_profile_pic']['name'];
                            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            $allowedExtensions = ['jpg', 'jpeg', 'png'];

                            if (in_array($fileExtension, $allowedExtensions)) {
                                $uploadDir = '../../frontend/member/profile_member/';
                                $filePath = $uploadDir . basename($fileName);

                                if (move_uploaded_file($fileTmpPath, $filePath)) {
                                    $member_profile_pic = $filePath;
                                }
                            }
                        }

                        $sql = "UPDATE tb_member SET
                                member_fullname='$member_fullname',
                                member_year='$member_year',
                                member_tel='$member_tel',
                                member_gender='$member_gender',
                                member_username='$member_username',
                                member_password='$member_password',
                                member_status='$member_status'";

                        if (isset($member_profile_pic)) {
                            $sql .= ", member_profile_pic='$member_profile_pic'";
                        }

                        $sql .= " WHERE member_id=$member_id";

                        if ($cls_conn->write_base($sql)) {
                            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_member.php');
                        } else {
                            echo $cls_conn->show_message('แก้ไขข้อมูลไม่สำเร็จ');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>