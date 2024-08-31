<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อเข้าถึงแดชบอร์ด";
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลสถิติการบ้าน
$teacher_id = $_SESSION['user']['teacher_id'];

// ดึงข้อมูลการบ้านที่มอบหมายโดยครู
$homework_sql = "SELECT COUNT(*) AS total_homework, 
                        SUM(CASE WHEN deadline >= NOW() THEN 1 ELSE 0 END) AS pending_homework, 
                        SUM(CASE WHEN deadline < NOW() THEN 1 ELSE 0 END) AS overdue_homework 
                 FROM tb_homework 
                 WHERE teacher_id = $teacher_id";
$homework_result = $mysqli->query($homework_sql);
$homework_data = $homework_result->fetch_assoc();

// ดึงข้อมูลการตรวจการบ้านของนักเรียน
$submission_sql = "SELECT COUNT(*) AS total_submissions, 
                          SUM(CASE WHEN grade IS NOT NULL THEN 1 ELSE 0 END) AS graded_submissions,
                          SUM(CASE WHEN grade IS NULL THEN 1 ELSE 0 END) AS ungraded_submissions
                   FROM tb_student_homework
                   WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE teacher_id = $teacher_id)";
$submission_result = $mysqli->query($submission_sql);
$submission_data = $submission_result->fetch_assoc();

// ดึงข้อมูลรายวิชาที่ครูสอน
$subject_sql = "SELECT subject_id, subject_pass, subject_name FROM tb_subject WHERE teacher_id = $teacher_id";
$subject_result = $mysqli->query($subject_sql);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=2.0">
    <title>แดชบอร์ดครู</title>
    <style>
        .dashboard-card {
            background-color: #f4f4f4;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .dashboard-card h3 {
            margin-top: 0;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .dashboard-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .dashboard-grid > div {
            flex: 1;
            min-width: 200px;
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>แดชบอร์ดครู</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- แสดงสถิติการบ้าน -->
                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <h3>การบ้านที่มอบหมาย</h3>
                            <p>จำนวนทั้งหมด: <?= $homework_data['total_homework']; ?></p>
                            <p>การบ้านที่ยังไม่หมดเขต: <?= $homework_data['pending_homework']; ?></p>
                            <p>การบ้านที่หมดเขตแล้ว: <?= $homework_data['overdue_homework']; ?></p>
                        </div>
                        <div class="dashboard-card">
                            <h3>การตรวจการบ้าน</h3>
                            <p>ส่งแล้วทั้งหมด: <?= $submission_data['total_submissions']; ?></p>
                            <p>ตรวจแล้ว: <?= $submission_data['graded_submissions']; ?></p>
                            <p>ยังไม่ได้ตรวจ: <?= $submission_data['ungraded_submissions']; ?></p>
                        </div>
                    </div>

                    <!-- แสดงรายวิชาที่สอน -->
                    <h3>รายวิชาที่สอน</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>รหัสวิชา</th>
                                <th>ชื่อวิชา</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($subject = $subject_result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($subject['subject_pass']); ?></td>
                                    <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                                    <td>
                                        <a href="show_homework.php?subject_pass=<?= urlencode($subject['subject_pass']); ?>" class="btn-primary">ดูการบ้าน</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>

<?php
$mysqli->close();
?>
