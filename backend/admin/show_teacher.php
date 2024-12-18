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
    .profile-pic {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>แสดงข้อมูลครู</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="text-muted font-13 m-b-30">
                <div align="left">
                    <a href="insert_teacher.php">
                        <button class="btn btn-hotpink">เพิ่มข้อมูล</button>
                    </a>
                </div>
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อครู</th>
                            <th>ชื่อผู้ใช้งาน</th>
                            <th>รหัสผ่าน</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>รูปโปรไฟล์</th> <!-- เพิ่มคอลัมน์สำหรับแสดงรูปโปรไฟล์ -->
                            <th>แก้ไข</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tb_teacher";
                        $result = $cls_conn->select_base($sql);
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><?= $row['teacher_id']; ?></td>
                                <td><?= $row['teacher_fullname']; ?></td>
                                <td><?= $row['teacher_username']; ?></td>
                                <td><?= str_repeat('*', strlen($row['teacher_password'])); ?></td>
                                <td><?= $row['teacher_tel']; ?></td>
                                <td>
                                    <?php if (!empty($row['teacher_profile_pic'])): ?>
                                        <img src="<?= htmlspecialchars($row['teacher_profile_pic']); ?>" alt="Profile Picture" class="profile-pic">
                                    <?php else: ?>
                                        <img src="../teacher/profile_teacher/user.jpg" alt="Default Profile Picture" class="profile-pic"> <!-- รูปโปรไฟล์เริ่มต้น -->
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="update_teacher.php?id=<?= $row['teacher_id']; ?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')">
                                        <img src="../../images/edit.png" />
                                    </a>
                                </td>
                                <td>
                                    <a href="delete_teacher.php?id=<?= $row['teacher_id']; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')">
                                        <img src="../../images/delete.png" />
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
