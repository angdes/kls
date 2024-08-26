<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h3>แก้ไขข้อมูลวิชา</h3>
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
                        $sql = "SELECT * FROM tb_subject WHERE subject_id = $id";
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) {
                            $subject_id = $row['subject_id'];
                            $subject_name = $row['subject_name'];
                            $subject_pass = $row['subject_pass'];
                            $subject_detail = $row['subject_detail'];
                            $subject_cover = $row['subject_cover'];
                        }
                    }
                    ?>
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="subject_id" value="<?= $subject_id; ?>" />

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_name">ชื่อวิชา<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="subject_name" name="subject_name" value="<?= $subject_name; ?>" required="required" class="form-control col-md-7 col-xs-12"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_pass">รหัสวิชา<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="subject_pass" name="subject_pass" value="<?= $subject_pass; ?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_detail">รายละเอียดวิชา:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="subject_detail" name="subject_detail" class="form-control col-md-7 col-xs-12"><?= $subject_detail; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_cover">รูปปกวิชา:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if (!empty($subject_cover)) : ?>
                                    <img src="<?= htmlspecialchars($subject_cover); ?>" alt="Cover Image" style="width: 100px; height: auto;"><br>
                                <?php endif; ?>
                                <input type="file" id="subject_cover" name="subject_cover" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-success">แก้ไข</button>
                                <button type="button" name="reset" class="btn btn-danger" onclick="window.history.back();">ยกเลิก</button>

                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        $subject_id = $_POST['subject_id'];
                        $subject_name = $_POST['subject_name'];
                        $subject_pass = $_POST['subject_pass'];
                        $subject_detail = $_POST['subject_detail'];

                        // ตรวจสอบการอัปโหลดรูปภาพใหม่
                        if (!empty($_FILES['subject_cover']['name'])) {
                            $target_dir = "uploads/";
                            $subject_cover = $target_dir . basename($_FILES["subject_cover"]["name"]);
                            move_uploaded_file($_FILES["subject_cover"]["tmp_name"], $subject_cover);
                        }

                        // อัปเดตข้อมูลในตาราง tb_subject
                        $sql = "UPDATE tb_subject SET subject_name='$subject_name', subject_pass='$subject_pass', subject_detail='$subject_detail', subject_cover='$subject_cover' WHERE subject_id=$subject_id";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_subject.php');
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
