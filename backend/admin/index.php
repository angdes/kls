<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่ และเป็น Admin
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินด้วยสิทธิ์ผู้ดูแลระบบเพื่อเข้าถึงแดชบอร์ดนี้";
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลสถิติผู้ใช้ (แยกครูและนักเรียน)
$user_stats_sql = "SELECT 
                    (SELECT COUNT(*) FROM tb_teacher) AS total_teachers,
                    (SELECT COUNT(*) FROM tb_member) AS total_students,
                    (SELECT COUNT(*) FROM tb_admin) AS total_admins";
$user_stats_result = $mysqli->query($user_stats_sql);

// ตรวจสอบข้อผิดพลาดของคำสั่ง SQL
if (!$user_stats_result) {
    die("การสืบค้นข้อมูลล้มเหลว: " . $mysqli->error);
}

$user_stats = $user_stats_result->fetch_assoc();

// ดึงข้อมูลสถิติวิชา
$subject_stats_sql = "SELECT COUNT(*) AS total_subjects FROM tb_subject";
$subject_stats_result = $mysqli->query($subject_stats_sql);

// ตรวจสอบข้อผิดพลาดของคำสั่ง SQL
if (!$subject_stats_result) {
    die("การสืบค้นข้อมูลล้มเหลว: " . $mysqli->error);
}

$subject_stats = $subject_stats_result->fetch_assoc();

// ดึงข้อมูลสถิติการบ้าน
$homework_stats_sql = "SELECT 
                        COUNT(*) AS total_homework,
                        SUM(CASE WHEN deadline >= NOW() THEN 1 ELSE 0 END) AS pending_homework, 
                        SUM(CASE WHEN deadline < NOW() THEN 1 ELSE 0 END) AS overdue_homework 
                       FROM tb_homework";
$homework_stats_result = $mysqli->query($homework_stats_sql);

// ตรวจสอบข้อผิดพลาดของคำสั่ง SQL
if (!$homework_stats_result) {
    die("การสืบค้นข้อมูลล้มเหลว: " . $mysqli->error);
}

$homework_stats = $homework_stats_result->fetch_assoc();

// ดึงข้อมูลการตรวจการบ้านของนักเรียน
$submission_stats_sql = "SELECT 
                          COUNT(*) AS total_submissions, 
                          SUM(CASE WHEN grade IS NOT NULL THEN 1 ELSE 0 END) AS graded_submissions,
                          SUM(CASE WHEN grade IS NULL THEN 1 ELSE 0 END) AS ungraded_submissions
                         FROM tb_student_homework";
$submission_stats_result = $mysqli->query($submission_stats_sql);

// ตรวจสอบข้อผิดพลาดของคำสั่ง SQL
if (!$submission_stats_result) {
    die("การสืบค้นข้อมูลล้มเหลว: " . $mysqli->error);
}

$submission_stats = $submission_stats_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดผู้ดูแลระบบ</title>
    <style>
        .dashboard-card {
            background-color: #f4f4f4;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
                    <h2>แดชบอร์ดผู้ดูแลระบบ</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- แสดงสถิติผู้ใช้ -->
                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <h3>สถิติผู้ใช้</h3>
                            <p>ครู: <?= $user_stats['total_teachers']; ?></p>
                            <p>นักเรียน: <?= $user_stats['total_students']; ?></p>
                            <p>ผู้ดูแลระบบ: <?= $user_stats['total_admins']; ?></p>
                        </div>
                        <div class="dashboard-card">
                            <h3>สถิติวิชา</h3>
                            <p>จำนวนวิชาทั้งหมด: <?= $subject_stats['total_subjects']; ?></p>
                        </div>
                    </div>

                    <!-- แสดงสถิติการบ้าน -->
                    

                    <!-- ลิงก์ด่วนการจัดการข้อมูล -->
                    <h3 style="color: black;">จัดการข้อมูล</h3>
                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <a href="show_announcements.php" class="btn-primary">จัดการข้อมูลประกาศ</a>
                            </div>
                        <div class="dashboard-card">
                            <a href="show_teacher.php" class="btn-primary">จัดการข้อมูลครู</a>
                        </div>
                        <div class="dashboard-card">
                            <a href="show_member.php" class="btn-primary">จัดการข้อมูลนักเรียน</a>
                        </div>
                    </div>
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
