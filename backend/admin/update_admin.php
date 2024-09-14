<?php include('header.php');?>
<style>
.btn-m {
        color: white;
        background-color: #FF00FF;
        border: 2px solid #E0E0E0;
        /* ขอบสีเทาอ่อน */
        border-radius: 5px;
        /* ทำให้ขอบมนเล็กน้อย */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* เงาเบาบางใต้ปุ่ม */
        transition: box-shadow 0.3s ease;
        /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
    }

    .btn-m:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        /* เงาชัดเจนขึ้นเมื่อ hover */
    }

    .btn-d {
        color: white;
        background-color: #808080;
        border: 2px solid #E0E0E0;
        /* ขอบสีเทาอ่อน */
        border-radius: 5px;
        /* ทำให้ขอบมนเล็กน้อย */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* เงาเบาบางใต้ปุ่ม */
        transition: box-shadow 0.3s ease;
        /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
    }

    .btn-d:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        /* เงาชัดเจนขึ้นเมื่อ hover */
    }
    .profile-pic {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        display: block;
        margin-bottom: 10px;
    }
</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>แก้ไขข้อมูลผู้ดูแลระบบ</h2>
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
                        $sql = "SELECT * FROM tb_admin WHERE admin_id = $id";
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) {
                            $admin_id = $row['admin_id'];
                            $admin_fullname = $row['admin_fullname'];
                            $admin_email = $row['admin_email'];
                            $admin_tel = $row['admin_tel'];
                            $admin_username = $row['admin_username'];
                            $admin_password = $row['admin_password'];
                            $admin_profile_pic = $row['admin_profile_pic']; // Get profile picture path
                        }
                    }
                    ?>
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="admin_id" value="<?= $admin_id; ?>" />

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_fullname">ชื่อผู้ดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_fullname" name="admin_fullname" value="<?= $admin_fullname; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_email">อีเมลผู้ดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" id="admin_email" name="admin_email" value="<?= $admin_email; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_tel">เบอร์โทรศัพท์ผู้ดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="admin_tel" name="admin_tel" value="<?= $admin_tel; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_username">ชื่อผู้ใช้งาน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_username" name="admin_username" value="<?= $admin_username; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_password">รหัสผ่าน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="admin_password" name="admin_password" value="<?= $admin_password; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <!-- Profile Picture -->
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_profile_pic">รูปโปรไฟล์<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if (!empty($admin_profile_pic)) : ?>
                                    <img src="<?= htmlspecialchars($admin_profile_pic); ?>" alt="Profile Picture" class="profile-pic">
                                <?php else : ?>
                                    <img src="default_profile.png" alt="Default Profile Picture" class="profile-pic">
                                <?php endif; ?>
                                <input type="file" id="admin_profile_pic" name="admin_profile_pic" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">แก้ไข</button>
                                <a href="show_admin.php" class="btn btn-d">ยกเลิก</a>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['submit'])) {
                        $admin_id = $_POST['admin_id'];
                        $admin_fullname = $_POST['admin_fullname'];
                        $admin_email = $_POST['admin_email'];
                        $admin_tel = $_POST['admin_tel'];
                        $admin_username = $_POST['admin_username'];
                        $admin_password = $_POST['admin_password'];

                        // Upload profile picture
                        $profile_pic_path = $admin_profile_pic; // Default to existing profile pic path
                        if (!empty($_FILES['admin_profile_pic']['name'])) {
                            $target_dir = "profile_admin/";
                            $target_file = $target_dir . basename($_FILES["admin_profile_pic"]["name"]);
                            if (move_uploaded_file($_FILES["admin_profile_pic"]["tmp_name"], $target_file)) {
                                $profile_pic_path = $target_file;
                            } else {
                                echo $cls_conn->show_message('อัปโหลดรูปโปรไฟล์ไม่สำเร็จ');
                            }
                        }

                        $sql = "UPDATE tb_admin SET admin_fullname='$admin_fullname', admin_email='$admin_email', admin_tel='$admin_tel', 
                                admin_username='$admin_username', admin_password='$admin_password', admin_profile_pic='$profile_pic_path' 
                                WHERE admin_id=$admin_id";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_admin.php');
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
