<?php
include('header.php');

// Start output buffering
ob_start();

$alert_message = '';

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    $alert_message = '<div class="alert alert-danger">คุณต้องล็อกอินก่อนเพื่อดูการบ้าน</div>';
    ob_end_flush();
    exit();
}
$teacher_id = $_SESSION['user']['teacher_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    $alert_message = '<div class="alert alert-danger">การเชื่อมต่อล้มเหลว: ' . $mysqli->connect_error . '</div>';
    ob_end_flush();
    exit();
}

// รับค่า homework_id จาก URL
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;

// ตรวจสอบว่า homework_id ถูกต้อง
if ($homework_id <= 0) {
    $alert_message = '<div class="alert alert-danger">ข้อมูลไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง.</div>';
    ob_end_flush();
    exit();
}

// ดึงข้อมูลการบ้านจากฐานข้อมูล
$homework_sql = "SELECT title, description, assigned_date, deadline, subject_id FROM tb_homework WHERE homework_id = $homework_id";
$homework_result = $mysqli->query($homework_sql);

if ($homework_result === false || $homework_result->num_rows === 0) {
    $alert_message = '<div class="alert alert-danger">ไม่พบข้อมูลการบ้านในระบบ.</div>';
    ob_end_flush();
    exit();
}

$homework = $homework_result->fetch_assoc();
$subject_id = $homework['subject_id'];
$assigned_date = $homework['assigned_date'];
$deadline = $homework['deadline'];

// ดึงข้อมูล subject_pass
$subject_sql = "SELECT subject_pass FROM tb_subject WHERE subject_id = $subject_id";
$subject_result = $mysqli->query($subject_sql);

if ($subject_result === false || $subject_result->num_rows === 0) {
    $alert_message = '<div class="alert alert-danger">ไม่พบข้อมูลวิชาในระบบ.</div>';
    ob_end_flush();
    exit();
}

$subject_row = $subject_result->fetch_assoc();
$subject_pass = $subject_row['subject_pass'];

// ดึงข้อมูลนักเรียนที่ลงทะเบียนในรายวิชา
$students_sql = "SELECT m.member_id, m.member_number, m.member_fullname, s.submission_time, s.file_path, s.grade, s.feedback, s.checked 
                 FROM tb_student_subject ss
                 JOIN tb_member m ON ss.member_id = m.member_id
                 LEFT JOIN tb_student_homework s ON m.member_id = s.member_id AND s.homework_id = $homework_id
                 WHERE ss.subject_id = $subject_id";
$students_result = $mysqli->query($students_sql);

if ($students_result === false) {
    $alert_message = '<div class="alert alert-danger">การดึงข้อมูลนักเรียนล้มเหลว: ' . $mysqli->error . '</div>';
    ob_end_flush();
    exit();
}

// บันทึกหรือแก้ไขการตรวจงาน
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['member_id']) && isset($_POST['delete_submission'])) {
        // ลบการส่งงานของนักเรียนสำหรับการบ้านนี้
        $member_id = $_POST['member_id'];
        $delete_sql = "DELETE FROM tb_student_homework WHERE homework_id = $homework_id AND member_id = $member_id";

        if ($mysqli->query($delete_sql) === TRUE) {
            $alert_message = '
            <div class="alert alert-success" role="alert">
                ลบการส่งงานของนักเรียนสำเร็จ
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "check_homework.php?homework_id=' . htmlspecialchars($homework_id) . '";
                }, 1000); // 1000 milliseconds = 1 second
            </script>';
        } else {
            $alert_message = '<div class="alert alert-danger">การลบการส่งงานล้มเหลว: ' . $mysqli->error . '</div>';
        }
    } else {
        $member_id = $_POST['member_id'];
        $grade = $_POST['grade'];
        $feedback = $_POST['feedback'];
        $checked = isset($_POST['checked']) ? 1 : 0;  // ตรวจสอบว่ามีการยืนยันการตรวจหรือไม่

        // อัปเดตผลการตรวจงานในฐานข้อมูล
        $update_sql = "UPDATE tb_student_homework SET grade = '$grade', feedback = '$feedback', checked = '$checked' WHERE homework_id = $homework_id AND member_id = $member_id";

        if ($mysqli->query($update_sql) === TRUE) {
            $alert_message = '
            <div class="alert alert-success" role="alert">
                บันทึกผลการตรวจงานสำเร็จ
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "check_homework.php?homework_id=' . htmlspecialchars($homework_id) . '";
                }, 1000); // 1000 milliseconds = 1 second
            </script>';
        } else {
            $alert_message = '<div class="alert alert-danger">การบันทึกผลการตรวจงานล้มเหลว: ' . $mysqli->error . '</div>';
        }
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจงานการบ้าน</title>
    <style>
        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-row label {
            flex: 0 0 150px;
            margin-right: 10px;
            font-weight: bold;
        }

        .form-row h4 {
            margin: 0;
        }

        .btn-d {
            color: white;
            background-color: #BA55D3;
            border-color: black;
        }

        .x_panel {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        .x_title h2 {
            color: black;
        }

        .form-section {
            padding: 10px 0;
        }

        .grade-input {
            width: 60px;
        }

        .report-section1 {
            background-color: #fff;
            /* พื้นหลังสีขาว */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* เงา */
            padding: 20px;
            /* เพิ่มระยะห่างภายใน */
            border-radius: 8px;
            /* เพิ่มความโค้งที่มุม */
        }

        .btn-d {
            color: white;
            background-color: #BA55D3;
            border-color: black;

        }

        .btn-m {
            color: white;
            background-color: #FF00FF;
            border-color: black;
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <?php if (!empty($alert_message)) {
                    echo $alert_message;
                } ?>
                <div class="x_title">
                    <h2 class="section-title">ตรวจงานสำหรับการบ้าน: <?= htmlspecialchars($homework['title']); ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="report-section1">
                        <p><strong>รายละเอียดการบ้าน:</strong> <?= htmlspecialchars($homework['description']); ?></p>
                        <p><strong>วันที่สั่ง:</strong> <?= htmlspecialchars($assigned_date); ?></p>
                        <p><strong>วันหมดเขต:</strong> <?= htmlspecialchars($deadline); ?></p>
                    </div>
                    <br>
                    <div class="x_title">
                        <div class="clearfix"></div>
                    </div>

                    <h2 class="section-title" style="color: black;">การส่งงานของนักเรียน</h2>
                   
                    <?php if ($students_result->num_rows > 0) {
                        while ($row = $students_result->fetch_assoc()) {
                            // ตรวจสอบการส่งงานและเวลาการส่ง
                            $submission_status_color = 'color: red;';
                            $submission_status_text = 'ยังไม่ได้ส่งงาน';
                            if (!empty($row['submission_time'])) {
                                $submission_time = strtotime($row['submission_time']);
                                $formatted_submission_time = date('d/m/Y H:i:s', $submission_time); // แปลงเวลาให้เป็นรูปแบบ วันที่/เดือน/ปี ชั่วโมง:นาที:วินาที
                                $deadline_time = strtotime($deadline);

                                if ($submission_time <= $deadline_time) {
                                    $submission_status_color = 'color: green;';
                                    $submission_status_text = 'ส่งงานตามเวลา (วันที่ ' . $formatted_submission_time . ')';
                                } else {
                                    $submission_status_text = 'ส่งล่าช้า (วันที่ ' . $formatted_submission_time . ')';
                                }
                            }

                            // ตรวจสอบสถานะการตรวจงาน
                            $checked_status_color = $row['checked'] ? 'color: green;' : 'color: red;';
                            $checked_status_text = $row['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ';
                    ?>
                            <div class="report-section">
                                <div class="form-row">
                                    <label>รหัสนักเรียน:</label>
                                    <h4><?= htmlspecialchars($row['member_number']); ?></h4>
                                </div>
                                <div class="form-row">
                                    <label>ชื่อนักเรียน:</label>
                                    <h4><?= htmlspecialchars($row['member_fullname']); ?></h4>
                                </div>
                                <div class="form-row">
                                    <label>เวลาการส่ง:</label>
                                    <h4 style="<?= $submission_status_color; ?>"><?= $submission_status_text; ?></h4>
                                </div>
                                <div class="form-row">
                                    <label>สถานะการตรวจ:</label>
                                    <h4 style="<?= $checked_status_color; ?>"><?= $checked_status_text; ?></h4>
                                </div>

                                <!-- แบบฟอร์มการตรวจ -->
                                <form method="post" action="">
                                    <div class="form-section">
                                        <div class="form-row">
                                            <label for="grade">คะแนน:</label>
                                            <input type="text" name="grade" value="<?= htmlspecialchars($row['grade'] ?? ''); ?>" placeholder="ใส่คะแนน">
                                        </div>

                                        <div class="form-row">
                                            <label for="feedback">ความคิดเห็น:</label>
                                            <textarea name="feedback" rows="2" placeholder="ใส่ความคิดเห็น"><?= htmlspecialchars($row['feedback'] ?? ''); ?></textarea>
                                        </div>

                                        <div class="action-buttons">
                                            <input type="checkbox" name="checked" <?= $row['checked'] ? 'checked' : ''; ?>> ตรวจแล้ว
                                            <button type="submit" class="btn btn-m">บันทึก</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="member_id" value="<?= $row['member_id']; ?>">
                                </form>

                                <!-- ปุ่มดูรายละเอียด -->
                                <?php if (!empty($row['file_path'])) { ?>
                                    <a href="submission_details.php?homework_id=<?= htmlspecialchars($homework_id); ?>&member_id=<?= htmlspecialchars($row['member_id']); ?>" class="btn btn-m">ดูรายละเอียด</a>
                                <?php } ?>

                                <!-- ปุ่มลบ -->
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="member_id" value="<?= $row['member_id']; ?>">
                                    <input type="hidden" name="delete_submission" value="1">
                                    <button type="submit" class="btn btn-d" onclick="return confirm('คุณต้องการลบหรือไม่?')">ลบ</button>
                                </form>
                        <?php }
                    } else {
                        echo '<p>ไม่มีนักเรียนในรายวิชานี้</p>';
                    } ?>

                        <div align="right">
                            <a href="show_homework.php?subject_pass=<?= urlencode($subject_pass) ?>"><button class="btn btn-d">ย้อนกลับ</button></a>
                        </div>
                            </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
</body>

</html>