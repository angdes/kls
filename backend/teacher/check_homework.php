<?php
include('header.php');

// Start output buffering
ob_start();

$alert_message = '';

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    $alert_message = '<div class="alert alert-danger">คุณต้องล็อกอินก่อนเพื่อดูการบ้าน</div>';
    ob_end_flush(); // Flush output buffer
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    $alert_message = '<div class="alert alert-danger">การเชื่อมต่อล้มเหลว: ' . $mysqli->connect_error . '</div>';
    ob_end_flush(); // Flush output buffer
    exit();
}

// รับค่า homework_id จาก URL
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;

// ตรวจสอบว่า homework_id ถูกต้อง
if ($homework_id <= 0) {
    $alert_message = '<div class="alert alert-danger">ข้อมูลไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง.</div>';
    ob_end_flush(); // Flush output buffer
    exit();
}

// ดึงข้อมูลการบ้านจากฐานข้อมูล
$homework_sql = "SELECT title, description, assigned_date, deadline, subject_id FROM tb_homework WHERE homework_id = $homework_id";
$homework_result = $mysqli->query($homework_sql);

if ($homework_result === false || $homework_result->num_rows === 0) {
    $alert_message = '<div class="alert alert-danger">ไม่พบข้อมูลการบ้านในระบบ.</div>';
    ob_end_flush(); // Flush output buffer
    exit();
}

$homework = $homework_result->fetch_assoc();
$subject_id = $homework['subject_id'];
$assigned_date = $homework['assigned_date'];
$deadline = $homework['deadline'];

// ดึง subject_pass โดยใช้ subject_id
$subject_sql = "SELECT subject_pass FROM tb_subject WHERE subject_id = $subject_id";
$subject_result = $mysqli->query($subject_sql);

if ($subject_result === false || $subject_result->num_rows === 0) {
    $alert_message = '<div class="alert alert-danger">ไม่พบข้อมูลวิชาในระบบ.</div>';
    ob_end_flush(); // Flush output buffer
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
    ob_end_flush(); // Flush output buffer
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
        .btn-success {
            background-color: #28a745;
            border-color: black;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-info {
            background-color: blue;
            border-color: black;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-danger {
            background-color: hotpink;
            border-color: black;
            color: black;
            padding: 5px 10px;
            font-size: 12px;
        }

        .alert {
            margin: 20px;
            padding: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: black;
        }

        .late-submission {
            color: red;
        }

        .on-time {
            color: green;
        }

        .btn-green {
            background-color: #28a745;
            border-color: black;
            color: white;
        }
        .btn-d {
            color: white;
            background-color: #BA55D3;
            border-color: black;
        }
        .btn-custom {
            background-color: #28a745;
            border-color: black;
            color: white;
        }

        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table td {
            padding: 10px;
            vertical-align: middle;
        }

        /* ปรับขนาด input ให้เล็กลง */
        .grade-input {
            width: 60px;
        }
        td {
            font-size: 12px;
        }
        th {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <?php if (!empty($alert_message)) { echo $alert_message; } ?>
                <div class="x_title">
                    <h2 style="color: magenta;">ตรวจงานสำหรับการบ้าน: <?= htmlspecialchars($homework['title']); ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p class="small-font" style="color: black;">รายละเอียดการบ้าน: <?= htmlspecialchars($homework['description']); ?></p>
                    <p class="small-font" style="color: #28a745;">วันที่สั่ง: <?= htmlspecialchars($assigned_date); ?></p>
                    <p style="color: red;">วันหมดเขต: <?= htmlspecialchars($deadline); ?></p>

                    <h3 style="color: black;">การส่งงานของนักเรียนในรายวิชา</h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสนักเรียน</th>
                                <th>ชื่อนักเรียน</th>
                                <th>วันที่สั่ง / วันหมดเขต</th>
                                <th>เวลาการส่ง</th>
                                <th>สถานะการตรวจ</th>
                                <th>คะแนน</th>
                                <th>ความคิดเห็น</th>
                                <th>ดำเนินการ</th>
                                <th>ดูรายละเอียด</th>
                                <th>ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($students_result->num_rows > 0) {
                                while ($row = $students_result->fetch_assoc()) {
                                    $submissionTime = strtotime($row['submission_time']);
                                    $deadlineTime = strtotime($deadline);
                                    $isLate = $submissionTime > $deadlineTime;
                                    $submissionClass = $isLate ? 'late-submission' : 'on-time';
                            ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['member_number']); ?></td>
                                        <td><?= htmlspecialchars($row['member_fullname']); ?></td>
                                        <td class="small-font"><?= htmlspecialchars($assigned_date); ?> / <?= htmlspecialchars($deadline); ?></td>
                                        <td class="<?= $submissionClass; ?>">
                                            <?php if (!empty($row['submission_time'])) { ?>
                                                <?= htmlspecialchars($row['submission_time']); ?>
                                                <?php if ($isLate) { ?>
                                                    (ส่งช้า)
                                                <?php } else { ?>
                                                    (ส่งตามเวลา)
                                                <?php } ?>
                                            <?php } else { ?>
                                                <p style="color: red">ยังไม่ได้ส่งงาน</p>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?= $row['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ'; ?>
                                        </td>
                                        <form method="post" class="form-inline">
                                            <input type="hidden" name="homework_id" value="<?= $homework_id; ?>">
                                            <input type="hidden" name="member_id" value="<?= $row['member_id']; ?>">
                                            <td>
                                                <input type="text" class="grade-input" name="grade" value="<?= htmlspecialchars($row['grade']); ?>" placeholder="คะแนน">
                                            </td>
                                            <td>
                                                <input type="text" name="feedback" value="<?= htmlspecialchars($row['feedback']); ?>" placeholder="ความคิดเห็น">
                                            </td>
                                            <td>
                                                <input type="checkbox" name="checked" <?= $row['checked'] ? 'checked' : ''; ?>> ตรวจ
                                                <button type="submit" class="btn btn-success">บันทึก</button>
                                            </td>
                                        </form>
                                        <td>
                                            <?php if (!empty($row['submission_time'])) { ?>
                                                <?php if (!empty($row['file_path'])) { ?>
                                                    <a href="submission_details.php?homework_id=<?= htmlspecialchars($homework_id); ?>&member_id=<?= htmlspecialchars($row['member_id']); ?>"
                                                        class="btn btn-custom">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <span>ไม่มีไฟล์</span>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <p style="color: red">ยังไม่ได้ส่งงาน</p>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <form method="post" action="">
                                                <input type="hidden" name="member_id" value="<?= $row['member_id']; ?>">
                                                <input type="hidden" name="delete_submission" value="1">
                                                <button type="submit" onclick="return confirm('คุณต้องการลบหรือไม่?')" ><img src="../../images/delete.png" /></button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="11">ไม่มีนักเรียนในรายวิชานี้</td></tr>';
                            }

                            $mysqli->close();
                            ?>
                        </tbody>
                    </table>
                    <div class="x_title">
                        <div class="clearfix"></div>
                    </div>
                    <div align="right">
                        <!-- ลิงก์ไปยังหน้า show_homework.php พร้อมพารามิเตอร์ subject_pass -->
                        <a href="show_homework.php?subject_pass=<?= urlencode($subject_pass) ?>">
                            <button class="btn btn-d">ย้อนกลับ</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>
