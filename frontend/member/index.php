<?php include('header.php'); ?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>รายการประกาศ</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php
                    $sql = "SELECT * FROM tb_announcements WHERE announcement_id = 14";
                    $result = $cls_conn->select_base($sql);
                    if ($row = mysqli_fetch_array($result)) {
                        // สมมติว่ารูปภาพอยู่ในโฟลเดอร์ backend/uploads/
                        $imagePath = '../../backend/uploads/' . $row['announcement_image'];
                    ?>


                        <h3><?php echo htmlspecialchars($row['announcement_title']); ?></h3>

                        <hr>
                        <center>
                            <img src="<?php echo $imagePath; ?>" style="max-width: 700px; max-height: 800px;">
                        </center>
                        <br>
                        <p style="font-size: 20px; padding-left: 50px;" >
                            <?php echo htmlspecialchars($row['announcement_details']); ?>
                        </p>
                    <?php
                    } else {
                        echo "<p>ไม่พบประกาศ</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>