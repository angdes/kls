<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h3>แก้ไขข้อมูลประกาศ</h3>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <?php
                    if(isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $sql = "SELECT * FROM tb_announcements WHERE announcement_id = $id";
                        $result = $cls_conn->select_base($sql);
                        if($row = mysqli_fetch_array($result)) {
                            $announcement_id = $row['announcement_id'];
                            $announcement_title = $row['announcement_title'];
                            $announcement_details = $row['announcement_details'];
                            $announcement_image = $row['announcement_image'];
                        }
                    }
                    ?>
                    <form id="edit_announcement_form" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="announcement_id" value="<?=$announcement_id;?>" />

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_title">หัวข้อประกาศ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="announcement_title" name="announcement_title" value="<?=$announcement_title;?>" required="required" class="form-control col-md-7 col-xs-12"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_details">รายละเอียดประกาศ<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="announcement_details" name="announcement_details" required="required" class="form-control col-md-7 col-xs-12"><?=$announcement_details;?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_image">รูปประกาศ</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="announcement_image" name="announcement_image" class="form-control col-md-7 col-xs-12">
                                <?php if(!empty($announcement_image)): ?>
                                    <img src="<?=$announcement_image;?>" alt="Announcement Image" style="max-width: 200px;">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="submit" class="btn btn-success">แก้ไข</button>
                                <a href="index.php" class="btn btn-danger">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                    <?php
                    if(isset($_POST['submit'])) {
                        $announcement_id = $_POST['announcement_id'];
                        $announcement_title = $_POST['announcement_title'];
                        $announcement_details = $_POST['announcement_details'];

                        // Upload image handling (if needed)
                        $announcement_image = $row['announcement_image']; // Default to existing image

                        if($_FILES['announcement_image']['size'] > 0) {
                            $target_dir = "../uploads";
                            $target_file = $target_dir . basename($_FILES["announcement_image"]["name"]);
                            $uploadOk = 1;
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                            // Allow certain file formats
                            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                                $uploadOk = 0;
                            }

                            if($uploadOk == 1) {
                                if(move_uploaded_file($_FILES["announcement_image"]["tmp_name"], $target_file)) {
                                    $announcement_image = $target_file;
                                } else {
                                    echo "Sorry, there was an error uploading your file.";
                                }
                            }
                        }

                        // Update data in tb_announcements table
                        $sql = "UPDATE tb_announcements SET announcement_title='$announcement_title', announcement_details='$announcement_details', announcement_image='$announcement_image' WHERE announcement_id=$announcement_id";

                        if($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('แก้ไขข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_announcements.php');
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
