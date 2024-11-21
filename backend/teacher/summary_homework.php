<?php
include('header.php');

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า subject_id จาก URL
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;

// ตรวจสอบว่า subject_id ถูกต้อง
if ($subject_id <= 0) {
    die("ข้อมูลวิชาไม่ถูกต้อง.");
}

// ดึงข้อมูลชื่อรายวิชา
$subject_sql = "SELECT subject_name FROM tb_subject WHERE subject_id = $subject_id";
$subject_result = $mysqli->query($subject_sql);
$subject_name = $subject_result->fetch_assoc()['subject_name'] ?? 'ไม่พบข้อมูลวิชา';

// ดึงข้อมูลนักเรียนที่ลงทะเบียนในรายวิชานี้ พร้อมกับสถานะการส่งงาน
$sql = "SELECT m.member_number, m.member_fullname, s.title, sh.submission_time, sh.checked
        FROM tb_student_subject ss
        JOIN tb_member m ON ss.member_id = m.member_id
        LEFT JOIN tb_student_homework sh ON ss.member_id = sh.member_id
        LEFT JOIN tb_homework s ON sh.homework_id = s.homework_id
        WHERE ss.subject_id = $subject_id
        ORDER BY m.member_number";
$result = $mysqli->query($sql);

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>สรุปการส่งงานของนักเรียนในรายวิชา: <?= htmlspecialchars($subject_name); ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if (!empty($students)) { ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสนักเรียน</th>
                                <th>ชื่อนักเรียน</th>
                                <th>หัวข้องาน</th>
                                <th>เวลาการส่ง</th>
                                <th>สถานะการตรวจ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student) { 
                                // ตรวจสอบการส่งงานและเวลาการส่ง
                                $submission_status_color = 'color: red;';
                                $submission_status_text = 'ยังไม่ได้ส่งงาน';
                                if (!empty($student['submission_time'])) {
                                    $submission_time = strtotime($student['submission_time']);
                                    $formatted_submission_time = date('d/m/Y H:i:s', $submission_time);
                                    $submission_status_color = 'color: green;';
                                    $submission_status_text = $formatted_submission_time;
                                }

                                // ตรวจสอบสถานะการตรวจงาน
                                $checked_status_color = $student['checked'] ? 'color: green;' : 'color: red;';
                                $checked_status_text = $student['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ';
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['member_number']); ?></td>
                                    <td><?= htmlspecialchars($student['member_fullname']); ?></td>
                                    <td><?= htmlspecialchars($student['title']); ?></td>
                                    <td style="<?= $submission_status_color; ?>"><?= $submission_status_text; ?></td>
                                    <td style="<?= $checked_status_color; ?>"><?= $checked_status_text; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>ไม่มีนักเรียนลงทะเบียนในรายวิชานี้</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php
$mysqli->close();
include('footer.php');
?>
