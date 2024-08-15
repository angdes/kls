<?php
include('header.php');?>
<?php
// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อเพิ่มนักเรียนให้กับการบ้าน";
    exit();
}

// ดึงค่า teacher_id จากเซสชัน
$teacher_id = $_SESSION['user']['teacher_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}


// $sql_subject = "SELECT * FROM tb_subject WHERE subject_id = $subject_id";
// $result_subject = $mysqli->query($sql_subject);

// if ($result_subject === false) {
//     die("การดึงข้อมูลการบ้านล้มเหลว: " . $mysqli->error);
// }

// ดึงข้อมูลการบ้านที่สามารถเลือกได้
$sql_homeworks = "SELECT * FROM tb_homework WHERE teacher_id = $teacher_id";
$result_homeworks = $mysqli->query($sql_homeworks);

// ตรวจสอบการดึงข้อมูลการบ้าน
if ($result_homeworks === false) {
    die("การดึงข้อมูลการบ้านล้มเหลว: " . $mysqli->error);
}

// ดึงข้อมูลนักเรียน
$sql_members = "SELECT * FROM tb_member";
$result_members = $mysqli->query($sql_members);
// ตรวจสอบการดึงข้อมูลนักเรียน
if ($result_members === false) {
    die("การดึงข้อมูลนักเรียนล้มเหลว: " . $mysqli->error);
}

if (isset($_POST['submit'])) {
    // ตรวจสอบว่ามีนักเรียนที่เลือกอยู่ใน tb_student_homework แล้วหรือไม่
    ob_start();
    // ดึงข้อมูลจากฟอร์ม
    $homework_id = $_POST['homework_id'];
    $selected_member = $_POST['member']; // Array of member_id

    // Convert the array to a comma-separated string
    $selected_member_str = implode(',', $selected_member);

    $check_mem = "SELECT member_id FROM tb_student_homework WHERE member_id IN ($selected_member_str) AND homework_id = $homework_id";
    $result_mem = $mysqli->query($check_mem);

    if ($result_mem && $result_mem->num_rows > 0) {
        // Some members already have homework assigned, handle this case
        echo "นักเรียนบางคนที่คุณเลือกมีข้อมูลอยู่แล้วในระบบ";
    } else {
        // No data found, proceed with the insert
        foreach ($selected_member as $student_id) {
            $sql_insert = "INSERT INTO tb_student_homework (homework_id, member_id) VALUES ('$homework_id', '$student_id')";
            if ($mysqli->query($sql_insert) === false) {
                die("การเพิ่มนักเรียนล้มเหลว: " . $mysqli->error);
            }
        }
        echo $cls_conn->show_message('บันทึกข้อมูลสำเร็จ');
        echo $cls_conn->goto_page(1,'show_homework.php');
        exit();
    }
    $alert_message = ob_get_clean();
     
}
?>

<div class="right_col" role="main">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-danger" role="alert">
        <?php echo $alert_message; ?>
        </div>
        <div class="x_panel">
            <div class="x_title">
                <h2>เพิ่มนักเรียนให้กับการบ้าน</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="add_member_form" class="form-horizontal form-label-left" method="post" action="">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="homework">เลือกการบ้าน<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="homework" name="homework_id" class="form-control col-md-7 col-xs-12" required>
                                <option value="">เลือกการบ้าน</option>
                                <?php while ($row_homework = $result_homeworks->fetch_assoc()) { ?>
                                    <option value="<?= htmlspecialchars($row_homework['homework_id']); ?>">
                                        <?= htmlspecialchars($row_homework['subject_id']); ?>
                                        <?= htmlspecialchars($row_homework['title']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
<br>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member">เลือกนักเรียน<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="member" name="member[]" class="form-control col-md-7 col-xs-12" multiple required>
                                <?php while ($row_member = $result_members->fetch_assoc()) { ?>
                                    <option value="<?= htmlspecialchars($row_member['member_id']); ?>">
                                        <?= htmlspecialchars($row_member['member_fullname']) . " (" . htmlspecialchars($row_member['member_number']) . ")"; ?>
                                    </option>
                                <?php } ?> <br>
                            </select>
                            <button type="button" id="select_all" class="btn btn-primary mt-2">เลือกทั้งหมด</button>
                            <button type="button" id="deselect_all" class="btn btn-danger">ยกเลิกการเลือกทั้งหมด</button>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" class="btn btn-success">บันทึกนักเรียน</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='show_homework.php';">ยกเลิก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('select_all').addEventListener('click', function() {
        var select = document.getElementById('member');
        for (var i = 0; i < select.options.length; i++) {
            select.options[i].selected = true;
        }
    });

    document.getElementById('deselect_all').addEventListener('click', function() {
        var select = document.getElementById('member');
        for (var i = 0; i < select.options.length; i++) {
            select.options[i].selected = false;
        }
    });
</script>

<?php
$mysqli->close();
include('footer.php');
?>
