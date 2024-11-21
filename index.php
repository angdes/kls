<?php include('header.php'); ?>
<style>
    .text-detail {
        font-family: 'Kanit', sans-serif;
        color: #555555;
        font-size: 14.44px;
    }
    .x_panel {
        border-radius: 10px;
        border: 1px solid #e5e5e5;
        padding: 20px;
        background-color: white;
        box-shadow: 0 10px 16px rgba(0, 0, 0, 0.4);
    }
    .image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding-left: 50px;
    }

    .image-container img {
        max-width: 500px;
        max-height: 500px;
        cursor: pointer;
        transition: transform 0.3s;
        box-shadow: 0 10px 16px rgba(102, 51, 153, 0.5);
    }

    .image-container img:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 16px rgba(0, 0, 0, 0.8);
    }

    .image-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: -10px;
        height: 100%;
        width: 5px;
        background-color: #CC33FF;
        box-shadow: 0 5px 16px rgba(0, 0, 0, 0.1);

    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal-content {
        margin: 5% auto;
        padding: 20px;
        width: 80%;
        background-color: white;
        border-radius: 8px;
        position: relative;
    }

    .modal-content img {
        width: 100%;
        height: auto;
    }

    .close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 25px;
        font-size: 35px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h3 style="color: black;"><b>ข่าวประกาศ</b></h3>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php
                    // ดึงข้อมูลประกาศพร้อมชื่อผู้ดูแลระบบที่ประกาศ
                    $sql = "SELECT a.*, ad.admin_fullname FROM tb_announcements a
                        JOIN tb_admin ad ON a.admin_id = ad.admin_id
                        ORDER BY a.announcement_date DESC";
                    $result = $cls_conn->select_base($sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            // สมมติว่ารูปภาพถูกเก็บในรูปแบบคั่นด้วยจุลภาค
                            $images = explode(',', $row['announcement_image']);
                    ?>
                            <div class="announcement">
                                <h2 style="color: #6666FF;"> ⁂ <?php echo htmlspecialchars($row['announcement_title']); ?></h2>
                                <p style="text-align: right; color: #666;">
                                    ประกาศโดย: <?php echo htmlspecialchars($row['admin_fullname']); ?> | วันที่: <?php echo htmlspecialchars($row['announcement_date']); ?>
                                </p>
                                <hr>
                                <div class="image-container">
                                    <?php foreach ($images as $image): ?>
                                        <img src="backend/uploads/<?php echo trim(htmlspecialchars($image)); ?>" onclick="openModal(this.src)" alt="Announcement Image">
                                    <?php endforeach; ?>
                                </div>
                                <br>
                                <p class="text-detail" style="padding-left: 50px;">
                                    <?php echo htmlspecialchars($row['announcement_details']); ?>
                                </p>
                                <div class="x_title">
                                    <div class="clearfix"></div>
                                </div>
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

<!-- Modal structure -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img id="modalImage" src="" alt="Image">
    </div>
</div>

<script>
    function openModal(imageSrc) {
        var modal = document.getElementById("imageModal");
        var modalImage = document.getElementById("modalImage");
        modalImage.src = imageSrc;
        modal.style.display = "block";
    }

    function closeModal() {
        var modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }
</script>

<?php include('footer.php'); ?>