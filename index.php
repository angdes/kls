<?php include('header.php'); ?>
<style>
     .image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -10px; /* Adjust this value to control the distance from the image */
            height: 100%;
            width: 5px; /* Width of the line */
            background-color: #F97AB6; /* Color of the line */
        }
</style>
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
$sql = "SELECT * FROM tb_announcements";
$result = $cls_conn->select_base($sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        // สมมติว่ารูปภาพอยู่ในโฟลเดอร์ backend/uploads/
        $imagePath = 'backend/uploads/' . $row['announcement_image'];
        ?>
        <div class="announcement">
            <h3><?php echo htmlspecialchars($row['announcement_title']); ?></h3>
            <hr>
            <div class="image-container">
            <img src="<?php echo $imagePath; ?>" style="max-width: 400px; max-height: 400px; margin-left: 30px; ">
            </div>
            
            <br>
            <p style="font-size: 20px; padding-left: 50px;">
                <?php echo htmlspecialchars($row['announcement_details']); ?>
            </p>
        </div>
        <br>
        <?php
    }
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