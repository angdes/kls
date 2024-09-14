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
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>ข้อมูลผู้ดูแลระบบ</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <!-- เพิ่ม enctype="multipart/form-data" เพื่อรองรับการอัปโหลดไฟล์ -->
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_fullname">ชื่อผู้ดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_fullname" name="admin_fullname" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_email">อีเมลดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" id="admin_email" name="admin_email" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_tel">เบอร์โทรศัพท์ผู้ดูแลระบบ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="admin_tel" name="admin_tel" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_username">username<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="admin_username" name="admin_username" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_password">รหัสผ่าน<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="password" id="admin_password" name="admin_password" required="required" class="form-control col-md-7 col-xs-12"> </div>
                        </div>

                        <!-- เพิ่มช่องสำหรับอัปโหลดรูปโปรไฟล์ -->
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_profile_pic">รูปโปรไฟล์<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="admin_profile_pic" name="admin_profile_pic" class="form-control col-md-7 col-xs-12" accept="image/*">
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-m">บันทึก</button>
                                <button type="reset" name="reset" class="btn btn-d">ยกเลิก</button>
                            </div>
                        </div>
                    </form>
                    
                    <?php
                    if (isset($_POST['submit'])) {
                        $admin_fullname = $_POST['admin_fullname'];
                        $admin_email = $_POST['admin_email'];
                        $admin_tel = $_POST['admin_tel'];
                        $admin_username = $_POST['admin_username'];
                        $admin_password = $_POST['admin_password'];
                        
                        // จัดการการอัปโหลดไฟล์
                        $admin_profile_pic = '';
                        if (isset($_FILES['admin_profile_pic']) && $_FILES['admin_profile_pic']['error'] == 0) {
                            $upload_dir = 'profile_admin/';  // กำหนดโฟลเดอร์ที่ต้องการอัปโหลดรูปโปรไฟล์
                            if (!file_exists($upload_dir)) {
                                mkdir($upload_dir, 0777, true);  // สร้างโฟลเดอร์ถ้าไม่มีอยู่
                            }
                            $file_name = basename($_FILES['admin_profile_pic']['name']);
                            $target_path = $upload_dir . $file_name;
                            
                            // ตรวจสอบว่าการอัปโหลดสำเร็จหรือไม่
                            if (move_uploaded_file($_FILES['admin_profile_pic']['tmp_name'], $target_path)) {
                                $admin_profile_pic = $target_path; // บันทึกเส้นทางไฟล์
                            } else {
                                echo "<div class='alert alert-danger'>เกิดข้อผิดพลาดในการอัปโหลดรูปโปรไฟล์</div>";
                            }
                        }

                        // เพิ่มข้อมูลผู้ดูแลระบบในตาราง tb_admin รวมถึงรูปโปรไฟล์
                        $sql = "INSERT INTO `tb_admin`(`admin_fullname`, `admin_email`, `admin_tel`, `admin_username`, `admin_password`, `admin_profile_pic`)";
                        $sql .= " VALUES ('$admin_fullname', '$admin_email', '$admin_tel', '$admin_username', '$admin_password', '$admin_profile_pic')";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('บันทึกข้อมูลและรูปโปรไฟล์สำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_admin.php');
                        } else {
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
