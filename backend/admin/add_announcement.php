<?php include('header.php'); ?>

<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
    }
    .btn-d {
        color: white;
        background-color: #BA55D3;
    }
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>เพิ่มประกาศใหม่</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="add_announcement_form" class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_title">หัวข้อประกาศ<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="announcement_title" name="announcement_title" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_details">รายละเอียดประกาศ<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="announcement_details" name="announcement_details" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement_image">รูปประกาศ</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="announcement_image" name="announcement_image[]" multiple class="form-control col-md-7 col-xs-12">
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
                        $announcement_title = $_POST['announcement_title'];
                        $announcement_details = $_POST['announcement_details'];

                        // Upload image handling for multiple files
                        $announcement_images = []; // Initialize with an empty array

                        if (isset($_FILES['announcement_image'])) {
                            $target_dir = "../uploads/"; // Directory where images will be uploaded

                            // Loop through each file
                            foreach ($_FILES['announcement_image']['name'] as $key => $filename) {
                                if ($_FILES['announcement_image']['size'][$key] > 0) {
                                    $uploadOk = 1;
                                    $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                                    // Generate unique file name to avoid conflicts
                                    $unique_filename = uniqid() . '.' . $imageFileType;
                                    $target_file = $target_dir . $unique_filename;

                                    // Allow certain file formats
                                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                                        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                                        $uploadOk = 0;
                                    }

                                    // Check if $uploadOk is set to 0 by an error
                                    if ($uploadOk == 0) {
                                        echo "Sorry, your file was not uploaded.";
                                    } else {
                                        if (move_uploaded_file($_FILES["announcement_image"]["tmp_name"][$key], $target_file)) {
                                            $announcement_images[] = $unique_filename; // Store only the filename
                                        } else {
                                            echo "Sorry, there was an error uploading your file.";
                                        }
                                    }
                                }
                            }
                        }

                        // Convert array of filenames to a comma-separated string for storage
                        $announcement_images_string = implode(',', $announcement_images);

                        // Insert data into tb_announcements table
                        $sql = "INSERT INTO tb_announcements (announcement_title, announcement_details, announcement_image)
                                VALUES ('$announcement_title', '$announcement_details', '$announcement_images_string')";

                        if ($cls_conn->write_base($sql) == true) {
                            echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
                            echo $cls_conn->goto_page(1, 'show_announcements.php');
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
<?php include('footer.php'); ?>
