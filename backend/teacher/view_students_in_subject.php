<?php
include('header.php');

// ตรวจสอบว่าได้รับ subject_id จาก URL หรือไม่
if (!isset($_GET['subject_id'])) {
    die("ไม่พบรหัสรายวิชา");
}

// ดึง subject_id จาก URL
$subject_id = intval($_GET['subject_id']);

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ตรวจสอบว่ามีการลบสมาชิกที่ระบุหรือไม่
if (isset($_GET['delete_member_id'])) {
    $delete_member_id = intval($_GET['delete_member_id']);
    $delete_sql = "DELETE FROM tb_student_subject WHERE subject_id = $subject_id AND member_id = $delete_member_id";
    if ($mysqli->query($delete_sql) === TRUE) {
        $alert_message = '<div class="alert alert-success" role="alert">ลบนักเรียนสำเร็จ</div>';
    } else {
        $alert_message = '<div class="alert alert-danger" role="alert">ลบนักเรียนไม่สำเร็จ: ' . $mysqli->error . '</div>';
    }
}

// ตรวจสอบว่ามีการลบข้อมูลนักเรียนที่เลือกทั้งหมดหรือไม่
if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['selected_members'])) {
        $selected_members = $_POST['selected_members'];
        $ids_to_delete = implode(',', $selected_members);

        // ลบข้อมูลนักเรียนที่เลือก
        $delete_sql = "DELETE FROM tb_student_subject WHERE subject_id = $subject_id AND member_id IN ($ids_to_delete)";
        if ($mysqli->query($delete_sql) === TRUE) {
            $alert_message = '<div class="alert alert-success" role="alert">ลบนักเรียนที่เลือกสำเร็จ</div>';
        } else {
            $alert_message = '<div class="alert alert-danger" role="alert">ลบนักเรียนไม่สำเร็จ: ' . $mysqli->error . '</div>';
        }
    }
}

// ตรวจสอบว่ามีการลบข้อมูลนักเรียนทั้งหมดหรือไม่
if (isset($_POST['delete_all'])) {
    // ลบนักเรียนทั้งหมดจากรายวิชานั้น ๆ
    $delete_sql = "DELETE FROM tb_student_subject WHERE subject_id = $subject_id";
    if ($mysqli->query($delete_sql) === TRUE) {
        $alert_message = '<div class="alert alert-success" role="alert">ลบนักเรียนทั้งหมดสำเร็จ</div>';
    } else {
        $alert_message = '<div class="alert alert-danger" role="alert">ลบนักเรียนไม่สำเร็จ: ' . $mysqli->error . '</div>';
    }
}

// ดึงข้อมูลนักเรียนที่อยู่ในรายวิชา
$sql = "
    SELECT 
        tb_member.member_id,
        tb_member.member_number,
        tb_member.member_fullname,
        tb_member.member_tel,
        tb_member.member_year
    FROM 
        tb_student_subject
    INNER JOIN 
        tb_member ON tb_student_subject.member_id = tb_member.member_id
    WHERE 
        tb_student_subject.subject_id = $subject_id
";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}
?>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>รายชื่อนักเรียนในรายวิชา</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if (isset($alert_message)) echo $alert_message; ?>
                <form method="post" action="">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all" /></th>
                                <th>ปีการศึกษา</th>
                                <th>รหัสนักเรียน</th>
                                <th>ชื่อเต็ม</th>
                                <th>เบอร์โทรศัพท์</th>
                                <th>ลบนักเรียนในรายวิชา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td><input type='checkbox' name='selected_members[]' value='" . htmlspecialchars($row['member_id']) . "' /></td>
                                            <td>" . htmlspecialchars($row['member_year']) . "</td>
                                            <td>" . htmlspecialchars($row['member_number']) . "</td>
                                            <td>" . htmlspecialchars($row['member_fullname']) . "</td>
                                            <td>" . htmlspecialchars($row['member_tel']) . "</td>
                                            <td>
                                                <a href='view_students_in_subject.php?subject_id=$subject_id&delete_member_id=" . htmlspecialchars($row['member_id']) . "' onclick=\"return confirm('คุณต้องการลบหรือไม่?')\">
                                                    <img src='../../images/delete.png' alt='Delete' />
                                                </a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo '<tr><td colspan="6">ไม่มีนักเรียนในรายวิชานี้</td></tr>';
                            }

                            $mysqli->close();
                            ?>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <button type="submit" name="delete_selected" class="btn btn-danger" onclick="return confirm('คุณต้องการลบรายการที่เลือกหรือไม่?')">ลบที่เลือก</button>
                        <button type="submit" name="delete_all" class="btn btn-danger" onclick="return confirm('คุณต้องการลบนักเรียนทั้งหมดหรือไม่?')">ลบทั้งหมด</button>
                    </div>
                    <div align="right">
                        <button type="button" name="reset" class="btn btn-success" onclick="window.location.href='show_subject.php';">ย้อนกลับ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ฟังก์ชันเลือก/ไม่เลือกทั้งหมด
    document.getElementById('select_all').addEventListener('click', function(e) {
        var checkboxes = document.querySelectorAll('input[name="selected_members[]"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>

<?php include('footer.php'); ?>