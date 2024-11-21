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
</style>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>แสดงข้อมูลสมาชิก</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div align="left">
                    <a href="insert_member.php">
                        <button class="btn btn-hotpink">เพิ่มข้อมูล</button>
                    </a>
                </div>
                <!-- ฟอร์มสำหรับการลบหลายรายการ -->
                <form method="post" action="delete_selected_members.php" id="deleteForm">
                    <div align="right">
                        <button type="submit" name="delete_selected" class="btn btn-danger" onclick="return confirmDeleteSelected()">ลบที่เลือก</button>
                        <button type="submit" name="delete_all" class="btn btn-danger" onclick="return confirm('คุณต้องการลบสมาชิกทั้งหมดหรือไม่?')">ลบทั้งหมด</button>
                    </div>
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th> <!-- เช็คบ็อกซ์สำหรับเลือกทั้งหมด -->
                                <th>ปีการศึกษา</th>
                                <th>รหัสสมาชิก</th>
                                <th>ชื่อสมาชิก</th>

                                <th>เบอร์โทรศัพท์</th>

                                <th>username</th>
                                <th>สถานะสมาชิก</th>
                                <th>เพศ</th>
                                <th>รูปโปรไฟล์</th>
                                <th>แก้ไข</th>
                                <th>ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tb_member";
                            $result = $cls_conn->select_base($sql);
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td><input type="checkbox" name="selected_members[]" value="<?= $row['member_id']; ?>"></td> <!-- เช็คบ็อกซ์สำหรับเลือกแต่ละรายการ -->
                                    <td><?= $row['member_year']; ?></td>
                                    <td><?= $row['member_number']; ?></td>
                                    <td><?= $row['member_fullname']; ?></td>

                                    <td><?= $row['member_tel']; ?></td>

                                    <td><?= $row['member_username']; ?></td>
                                    <td>
                                        <?php
                                        echo $row['member_status'] == '1' ? '<span style="color:green;">นักเรียนภาคปกติ</span>' : '<span style="color:blue;">นักเรียนย้ายเข้า</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        // ตรวจสอบค่าของ member_gender และแสดงผลเป็นภาษาไทย
                                        echo $row['member_gender'] === 'male' ? 'ชาย' : 'หญิง';
                                        ?>
                                    </td>


                                    <td>
                                        <?php if (!empty($row['member_profile_pic'])): ?>
                                            <img src="<?= htmlspecialchars($row['member_profile_pic'], ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="profile_admin/user.jpg" alt="Default Profile Picture" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="update_member.php?id=<?= $row['member_id']; ?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')">
                                            <img src="../../images/edit.png" />
                                        </a>
                                    </td>
                                    <td>
                                        <a href="delete_member.php?id=<?= $row['member_id']; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')">
                                            <img src="../../images/delete.png" />
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>

<script>
    // JavaScript สำหรับเช็คบ็อกซ์เลือกทั้งหมด
    document.getElementById('checkAll').onclick = function() {
        var checkboxes = document.getElementsByName('selected_members[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }

    // ฟังก์ชันยืนยันการลบที่เลือก
    function confirmDeleteSelected() {
        var checkboxes = document.querySelectorAll('input[name="selected_members[]"]:checked');
        if (checkboxes.length === 0) {
            alert('กรุณาเลือกสมาชิกที่ต้องการลบ');
            return false;
        }
        return confirm('คุณต้องการลบสมาชิกที่เลือกหรือไม่?');
    }
</script>