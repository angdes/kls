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
            /* สีพื้นหลังที่อ่อนกว่า */
            color: #4A235A;
            /* สีข้อความที่เข้มขึ้นเล็กน้อย */
            margin: 0;
            padding: 0;
        }

        .dashboard-card {
            background: #FFFFFF;
            /* สีพื้นหลังของการ์ดเป็นขาว */
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(102, 51, 153, 0.2);
            /* เงาของการ์ดที่มีโทนสีม่วง */
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(102, 51, 153, 0.4);
            /* เพิ่มความเข้มของเงาเมื่อ hover */
        }

        .dashboard-card h3 {
            margin-top: 0;
            color: #2980B9;
            /* สีน้ำเงินเข้ม */
            /* สีหัวข้อในการ์ด */
            font-size: 1.5rem;
        }

        .btn-primary {
            background-color: #2ECC71;
            /* สีปุ่มใหม่เป็นสีฟ้า */
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 50px;
            display: inline-block;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #27AE60;
            /* ทำให้สีปุ่มเข้มขึ้นเมื่อ hover */
            box-shadow: 0 4px 8px rgba(41, 128, 185, 0.3);
        }

        .dashboard-card p {
            color: #424949;
            /* สีเทาชาร์โคล */
            font-size: 1.2rem;
        }

        .dashboard-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
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
            color: #8E44AD;
            /* สีของหัวข้อใหญ่ */
            text-align: center;
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

        .btn1 {
            padding: 8px 12px;
            /* Smaller padding for more refined buttons */
            margin: 5px;
            background-image: linear-gradient(to right, #77c593, #a8d8b9);
            /* Light green gradient */
            color: #333;
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
            background-image: linear-gradient(to right, #66a482, #99c7a2);
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

                    <!-- ลิงก์ด่วนการจัดการข้อมูล -->
                    <h3>จัดการข้อมูล</h3>
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

                    <div class="x_content">
                        <div>
                            <a href="?display=students" class="btn1">ข้อมูลครู</a>
                            <a href="?display=teachers" class="btn1">ข้อมูลนักเรียน</a>
                            <a href="?display=both" class="btn1">ข้อมูลนักเรียน/ครู</a>
                        </div>
                        <table>
                            <tr>
                                <th>ชื่อนักเรียน/ครู</th>
                                <th>โทรศัพท์</th>
                            </tr>
                            <?php if ($display == 'students' || $display == 'both'): ?>
                                <?php foreach ($members as $member): ?>
                                    <tr>
                                        <td><?= $member['member_fullname']; ?></td>
                                        <td><?= $member['member_tel']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ($display == 'teachers' || $display == 'both'): ?>
                                <?php foreach ($teachers as $teacher): ?>
                                    <tr>
                                        <td><?= $teacher['teacher_fullname']; ?></td>
                                        <td><?= $teacher['teacher_tel']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
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