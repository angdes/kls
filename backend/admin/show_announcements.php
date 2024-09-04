<?php include('header.php'); ?>

<style>
    .btn-hotpink {
        background-color: hotpink;
        border-color: hotpink;
        color: white;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-hotpink:hover {
        background-color: deeppink;
        color: white;
    }

    .table img {
        border-radius: 8px;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .table img:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .x_panel {
        border-radius: 10px;
        border: 1px solid #e5e5e5;
        padding: 20px;
        background-color: white;
    }

    .x_title {
        border-bottom: 2px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .x_title h2 {
        font-weight: bold;
        color: #333;
    }

    .panel_toolbox {
        display: flex;
        justify-content: flex-end;
    }

    .panel_toolbox li {
        margin-left: 10px;
    }

    .panel_toolbox li a {
        color: #333;
    }

    .panel_toolbox li a:hover {
        color: hotpink;
    }

    /* Modal styles */
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
        margin: 15% auto;
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
                    <h2>รายการประกาศ</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="text-muted font-13 m-b-30">
                        <div align="left">
                            <a href="add_announcement.php">
                                <button class="btn btn-hotpink">เพิ่มข้อมูล</button>
                            </a>
                        </div>
                    </div>
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>หัวข้อ</th>
                                <th>รายละเอียด</th>
                                <th>รูปภาพ</th>
                                <th>แก้ไข</th>
                                <th>ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tb_announcements ORDER BY announcement_id";
                            $result = $cls_conn->select_base($sql);
                            $count = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                $count++;
                                $announcement_images = explode(',', $row['announcement_image']); // Split filenames into an array
                            ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo htmlspecialchars($row['announcement_title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['announcement_details']); ?></td>
                                    <td>
                                        <?php if (!empty($announcement_images[0])): ?>
                                            <?php foreach ($announcement_images as $image): ?>
                                                <img src="../uploads/<?php echo htmlspecialchars(trim($image)); ?>" onclick="openModal('../uploads/<?php echo htmlspecialchars(trim($image)); ?>')" style="max-width: 100px; max-height: 100px;">
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span>ไม่มีรูปภาพ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_announcement.php?id=<?php echo $row['announcement_id']; ?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')">
                                            <img src="../../images/edit.png" alt="Edit" title="แก้ไข" style="cursor: pointer;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="delete_announcement.php?id=<?php echo $row['announcement_id']; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')">
                                            <img src="../../images/delete.png" alt="Delete" title="ลบ" style="cursor: pointer;">
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
