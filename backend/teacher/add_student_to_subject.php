<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อเพิ่มนักเรียนให้กับรายวิชา";
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

// ดึงข้อมูลรายวิชาที่สามารถเลือกได้
$sql_subjects = "
    SELECT 
        tb_subject.subject_id,
        tb_subject.subject_pass,
        tb_subject.subject_name,
        tb_subject.subject_detail
    FROM 
        tb_subject
    WHERE 
        tb_subject.teacher_id = $teacher_id
";
$result_subjects = $mysqli->query($sql_subjects);

// ตรวจสอบการดึงข้อมูลรายวิชา
if ($result_subjects === false) {
    die("การดึงข้อมูลรายวิชาล้มเหลว: " . $mysqli->error);
}

// ดึงข้อมูลนักเรียน
$sql_members = "SELECT * FROM tb_member";
$result_members = $mysqli->query($sql_members);

// ตรวจสอบการดึงข้อมูลนักเรียน
if ($result_members === false) {
    die("การดึงข้อมูลนักเรียนล้มเหลว: " . $mysqli->error);
}

// ตั้งค่าตัวแปรเพื่อเก็บข้อความแจ้งเตือน
$alert_message = '';

if (isset($_POST['submit'])) {
    // ตรวจสอบว่ามีนักเรียนที่เลือกอยู่ใน tb_student_subject แล้วหรือไม่
    ob_start();
    // ดึงข้อมูลจากฟอร์ม
    $subject_id = $_POST['subject_id'];
    $selected_member = $_POST['member']; // Array of member_id

    // Convert the array to a comma-separated string
    $selected_member_str = implode(',', $selected_member);

    $check_mem = "SELECT member_id FROM tb_student_subject WHERE member_id IN ($selected_member_str) AND subject_id = $subject_id";
    $result_mem = $mysqli->query($check_mem);

    if ($result_mem && $result_mem->num_rows > 0) {
        // Some members already have subjects assigned, handle this case
        $alert_message = '<div class="alert alert-danger" role="alert">นักเรียนบางคนที่คุณเลือกมีข้อมูลอยู่แล้วในระบบ</div>';
    } else {
        // No data found, proceed with the insert
        foreach ($selected_member as $student_id) {
            $sql_insert = "INSERT INTO tb_student_subject (subject_id, member_id) VALUES ('$subject_id', '$student_id')";
            if ($mysqli->query($sql_insert) === false) {
                die("การเพิ่มนักเรียนล้มเหลว: " . $mysqli->error);
            }
        }
        $alert_message = '
    <div class="alert alert-success" role="alert">
        บันทึกข้อมูลสำเร็จ
    </div>
    <script>
        setTimeout(function(){
            window.location.href = "show_subject.php";
        }, 1000); // 1000 milliseconds = 1 second
    </script>
';
    }
    ob_end_clean();
}
?>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <!-- แสดงข้อความแจ้งเตือนเฉพาะเมื่อมีข้อความแจ้งเตือน -->


        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                
                <h2>เพิ่มนักเรียนให้กับรายวิชา</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <?php if (!empty($alert_message)) {
                    echo $alert_message;
                } ?>
                <form id="add_member_form" class="form-horizontal form-label-left" method="post" action="">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject">เลือกรายวิชา<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="subject" name="subject_id" class="form-control col-md-7 col-xs-12" required>
                                <option value="">เลือกรายวิชา</option>
                                <?php while ($row_subject = $result_subjects->fetch_assoc()) { ?>
                                    <option value="<?= htmlspecialchars($row_subject['subject_id']); ?>">
                                        <?= "[" . htmlspecialchars($row_subject['subject_pass']) . "]"; ?>
                                        <?= htmlspecialchars($row_subject['subject_name']); ?>
                                        <?= htmlspecialchars($row_subject['subject_detail']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member">เลือกนักเรียน<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <!-- ช่องค้นหา -->
                            <input type="text" id="search_member" class="form-control" placeholder="ค้นหานักเรียน...">
                            <select id="member" name="member[]" class="form-control col-md-7 col-xs-12" multiple required>
                                <?php while ($row_member = $result_members->fetch_assoc()) { ?>
                                    <option value="<?= htmlspecialchars($row_member['member_id']); ?>">
                                        <?= "(" . htmlspecialchars($row_member['member_number']) . ")" . " " . htmlspecialchars($row_member['member_fullname']) . ""; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <br>
                            <button type="button" id="select_all" class="btn btn-primary mt-2">เลือกทั้งหมด</button>
                            <button type="button" id="deselect_all" class="btn btn-danger">ยกเลิกการเลือกทั้งหมด</button>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" class="btn btn-success">บันทึกนักเรียน</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='show_subject.php';">ยกเลิก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ฟังก์ชันค้นหาใน dropdown
    document.getElementById('search_member').addEventListener('input', function() {
        var filter = this.value.toUpperCase();
        var select = document.getElementById('member');
        var options = select.options;
        for (var i = 0; i < options.length; i++) {
            var text = options[i].text.toUpperCase();
            options[i].style.display = text.indexOf(filter) > -1 ? '' : 'none';
        }
    });

    // ฟังก์ชันเลือกทั้งหมด
    document.getElementById('select_all').addEventListener('click', function() {
        var select = document.getElementById('member');
        for (var i = 0; i < select.options.length; i++) {
            select.options[i].selected = true;
        }
    });

    // ฟังก์ชันยกเลิกการเลือกทั้งหมด
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