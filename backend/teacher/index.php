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
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6;
            /* สีพื้นหลังโทนอ่อน */
            color: #5a5c69;
            /* สีข้อความโทนเข้มสบายตา */
            margin: 0;

        }

        .dashboard-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 4px 20px rgba(149, 102, 255, 1);
            /* เงาสีม่วงอ่อน */
            transition: all 0.3s ease-in-out;
        }

        .dashboard-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 25px rgba(149, 102, 255, 0.35);
            /* เงาสีม่วงอ่อนขึ้นเมื่อ hover */
        }

       

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            /* จัดการ์ดให้เต็มและเท่ากัน */
            gap: 20px;
        }

        .btn-primary {
            background-color: #4e73df;
            /* สีฟ้าเข้ม */
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            /* ปุ่มมนกว่าเดิม */
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            /* สีฟ้าที่เข้มกว่า */
        }

        .table {
            width: 100%;
            /* ตารางเต็มความกว้าง */
            border-collapse: collapse;
            /* ไม่มีช่องว่างระหว่างเซลล์ */

            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(149, 102, 255, 0.15);
            /* เงาสีม่วงอ่อน */
            /* เงาใต้ตาราง */
        }

        .table th,
        .table td {
            text-align: left;
            /* ข้อความชิดซ้าย */
            padding: 12px;
            /* ระยะห่างในเซลล์ */
        }

        .table th {
            background-color: #f8f9fc;
            /* สีหัวตารางอ่อนๆ */
            color: #4e73df;
            /* สีข้อความหัวตารางฟ้าเข้ม */
        }

        .table td {
            border-top: 1px solid #e3e6f0;
            /* เส้นขอบเซลล์บน */
        }

        h2 {
            color: #8E44AD;
            /* สีของหัวข้อใหญ่ */

            font-size: 2rem;
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);">
                <div class="x_title">
                    <h2 style="color: black;"><b>Dashboard</b></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- แสดงสถิติการบ้าน -->
                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <h2>งานมอบหมาย</h2>
                            <p>จำนวนทั้งหมด: <?= $homework_data['total_homework']; ?></p>
                            <p>งานที่ยังไม่หมดเขต: <?= $homework_data['pending_homework']; ?></p>
                            <p>งานที่หมดเขตแล้ว: <?= $homework_data['overdue_homework']; ?></p>
                        </div>
                        <div class="dashboard-card">
                            <h2>การตรวจงาน</h2>
                            
                            <p>ตรวจแล้ว: <?= $submission_data['graded_submissions']; ?></p>
                            <p>ยังไม่ได้ตรวจ: <?= $submission_data['ungraded_submissions']; ?></p>
                            
                        </div>
                    </div>

                    <!-- แสดงรายวิชาที่สอน -->
                    <h2 style="color: black;">รายวิชาที่สอน</h2>
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
                                        <a href="show_homework.php?subject_pass=<?= urlencode($subject['subject_pass']); ?>" class="btn-primary">ดูงาน</a>
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