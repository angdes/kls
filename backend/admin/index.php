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

// ตัวแปรเพื่อควบคุมการแสดงข้อมูล
$display = $_GET['display'] ?? 'both';

// ดึงข้อมูลนักเรียนหรือครูตามการเลือก
$members = [];
$teachers = [];

if ($display == 'students' || $display == 'both') {
    $members_query = "SELECT member_id, member_fullname, member_tel FROM tb_member";
    $members_result = $mysqli->query($members_query);
    while ($row = $members_result->fetch_assoc()) {
        $members[] = $row;
    }
}

if ($display == 'teachers' || $display == 'both') {
    $teachers_query = "SELECT teacher_fullname, teacher_tel FROM tb_teacher";
    $teachers_result = $mysqli->query($teachers_query);
    while ($row = $teachers_result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดผู้ดูแลระบบ</title>
    <style>
         body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f8;
            color: #4A235A;
            margin: 0;
            padding: 0;
        }

        .dashboard-card {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
        }

        .dashboard-card h3 {
            margin-top: 0;
            font-size: 2.5rem;
        }

        .dashboard-card p {
            margin: 0;
            font-size: 1.2rem;
        }

        .dashboard-card i {
            font-size: 2.5rem;
        }

        .dashboard-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card-blue {
            background-color: #3498db;
        }

        .card-green {
            background-color: #2ecc71;
        }

        .card-yellow {
            background-color: #f39c12;
        }

        .card-orange {
            background-color: #e67e22;
        }

        .btn-primary {
            background-color: #b856d6;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 20px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .btn-primary:hover {
            background-color: #79099c;
            box-shadow: 0 4px 8px rgba(41, 128, 185, 0.3);
        }

        .dashboard-grid>div {
            flex: 1;
            min-width: 200px;
        }

        .x_panel {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(102, 51, 153, 0.3);
        }

        h2 {
            color: black;
            /* สีของหัวข้อใหญ่ */
            
            font-size: 2rem;
        }

        p {
            color: #4A235A;
            /* สีของข้อความทั่วไป */
            font-size: 1.2rem;
        }

        h3 {
            color: #9B59B6;
            /* สีของหัวข้อย่อย */
            font-size: 1.4rem;
            text-transform: uppercase;
        }

        .x_content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        a.btn-primary {
            font-size: 1.1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f8;
            color: #4A235A;
            margin: 0;
            padding: 0;
        }
        .fa-user-shield{
            color: black;
        }
        .btn1 {
            padding: 8px 12px;
            /* Smaller padding for more refined buttons */
            margin: 5px;
            background-image: linear-gradient(to right, #79099c, #b856d6);
            /* Light green gradient */
            color: #fff;
            /* Dark grey for text to ensure good contrast */
            text-decoration: none;
            border: none;
            /* Removes border */
            border-radius: 20px;
            /* Soft rounded corners for a modern look */
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Light shadow for a subtle depth */
            font-weight: bold;
            /* Bold text for readability */
            font-size: 14px;
            /* Smaller font size fits the smaller button size */
        }

        .btn1:hover {
            background-image: linear-gradient(to right, #b856d6, #b856d6);
            color: #fff;
            /* Darker green gradient on hover */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
            /* Slightly larger shadow on hover for a "lifting" effect */
            transform: translateY(-1px);
            /* Slight raise when hovered for tactile feedback */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            color: black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="color: black;">แดชบอร์ดผู้ดูแลระบบ</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- แสดงสถิติผู้ใช้ -->
                    <div class="dashboard-grid">
                        <div class="dashboard-card card-blue">
                            <div>
                                <h3 style="color: white;"><?= $user_stats['total_teachers']; ?></h3>
                                <p style="color: white;" >จำนวนครู</p>
                            </div>
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="dashboard-card card-green">
                            <div>
                                <h3 style="color: white;"><?= $user_stats['total_students']; ?></h3>
                                <p style="color: white;">จำนวนนักเรียน</p>
                            </div>
                            <i class="fa fa-user-graduate"></i>
                        </div>
                        <div class="dashboard-card card-yellow">
                            <div>
                                <h3 style="color: black;"><?= $user_stats['total_admins']; ?></h3>
                                <p style="color: black;">จำนวนผู้ดูแลระบบ</p>
                            </div>
                            <i class="fa fa-user-shield"></i>
                        </div>
                    </div>

                    <!-- ลิงก์ด่วนการจัดการข้อมูล -->
                    <h2>จัดการข้อมูล</h2>
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

                    <!-- แสดงข้อมูลนักเรียนและครู -->
                    <div class="x_content">
                        <div>
                            <a href="?display=students" class="btn1">ข้อมูลครู</a>
                            <a href="?display=teachers" class="btn1">ข้อมูลนักเรียน</a>
                            <a href="?display=both" class="btn1">ข้อมูลนักเรียน/ครู</a>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>ชื่อ</th>
                                    <th>โทรศัพท์</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($display == 'students' || $display == 'both'): ?>
                                    <?php foreach ($members as $member): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($member['member_fullname']); ?></td>
                                            <td><?= htmlspecialchars($member['member_tel']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if ($display == 'teachers' || $display == 'both'): ?>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($teacher['teacher_fullname']); ?></td>
                                            <td><?= htmlspecialchars($teacher['teacher_tel']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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