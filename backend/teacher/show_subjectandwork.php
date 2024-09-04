<?php include('header.php'); ?>

<?php
// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลของครูที่ล็อกอินอยู่
$teacher_id = $_SESSION['user']['teacher_id'];

// ดึงข้อมูลรายวิชาที่สอนโดยครูที่ล็อกอินอยู่จากฐานข้อมูล
$sql = "SELECT subject_id, subject_pass, subject_name, subject_detail, subject_cover 
        FROM tb_subject 
        WHERE teacher_id = '$teacher_id'";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงข้อมูลรายวิชา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            font-size: 24px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin: 0px;
            width: 100%;
            padding: 30px;
            max-width: 1000px;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 10px;
            width: calc(50% - 20px);
            display: flex;
            flex-direction: row;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s;
            position: relative; /* เพิ่มเพื่อรองรับไอคอน Excel */
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-image {
            flex: 1;
            background-color: #eaeaea;
            border-radius: 8px;
            overflow: hidden;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-content {
            flex: 2;
            padding-left: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #C44AFD;
        }
        .card-description {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .card-stats {
            font-size: 12px;
            margin-bottom: 5px;
        }
        .text-green {
            color: green;
        }
        .text-red {
            color: red;
        }
        .text-work {
            color: black;
        }
        .text-stus {
            color: magenta;
        }
        .colorfont {
            color: black;
            font-size: 22px;
        }
        .download-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2 class="colorfont">รายวิชาที่สอน</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
    
            <div class="card-container">
                <?php
                // แสดงข้อมูลรายวิชาในรูปแบบการ์ด
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $subject_id = $row['subject_id'];
                        $subject_pass = htmlspecialchars($row['subject_pass']);
                        $subject_name = htmlspecialchars($row['subject_name']);
                        $subject_detail = htmlspecialchars($row['subject_detail']);
                        $subject_cover = htmlspecialchars($row['subject_cover']);

                        // ใช้ rawurlencode เพื่อเข้ารหัส URL ของรูปภาพ
                        $image_path = !empty($subject_cover) ? '../../backend/teacher/uploads/' . rawurlencode(basename($subject_cover)) : 'path/to/placeholder/image.png';

                        // คำนวณสถิติการบ้านและข้อมูลเพิ่มเติม
                        $total_homework_sql = "SELECT COUNT(*) as total_homework FROM tb_homework WHERE subject_id = '$subject_id'";
                        $total_students_sql = "SELECT COUNT(*) as total_students FROM tb_student_subject WHERE subject_id = '$subject_id'";
                        $submitted_sql = "SELECT COUNT(DISTINCT member_id) as submitted FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id')";
                        $not_submitted_sql = "SELECT (SELECT COUNT(*) FROM tb_student_subject WHERE subject_id = '$subject_id') - IFNULL((SELECT COUNT(DISTINCT member_id) FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id')), 0) as not_submitted";
                        $unchecked_sql = "SELECT IFNULL((SELECT COUNT(*) FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id') AND checked = 0), 0) as unchecked";

                        $total_homework = $mysqli->query($total_homework_sql)->fetch_assoc()['total_homework'];
                        $total_students = $mysqli->query($total_students_sql)->fetch_assoc()['total_students'];
                        $submitted = $mysqli->query($submitted_sql)->fetch_assoc()['submitted'];
                        $not_submitted = $mysqli->query($not_submitted_sql)->fetch_assoc()['not_submitted'];
                        $unchecked = $mysqli->query($unchecked_sql)->fetch_assoc()['unchecked'];

                        echo '<div class="card">';
                        echo '<div class="card-image">';
                        echo '<img src="' . $image_path . '" alt="รูปปก">';
                        echo '</div>';
                        echo '<div class="card-content" onclick="window.location.href=\'show_homework.php?subject_pass=' . $subject_pass . '\'">'; // เพิ่ม onclick เพื่อไปยังหน้าแสดงการบ้าน
                        echo '<div class="card-title">' . $subject_name . '</div>';
                        echo '<div class="card-description">' . $subject_detail . '</div>';
                        echo '<div class="card-stats text-work">การบ้านทั้งหมด: ' . $total_homework . ' งาน</div>';
                        echo '<div class="card-stats text-stus">นักเรียนทั้งหมด: ' . $total_students . ' คน</div>';
                        echo '<div class="card-stats text-green">ส่งงานแล้ว: ' . $submitted . ' คน</div>';
                        echo '<div class="card-stats text-red">ยังไม่ส่ง: ' . $not_submitted . ' คน</div>';
                        echo '<div class="card-stats text-black">ยังไม่ตรวจ: ' . $unchecked . ' งาน</div>';
                        echo '</div>';
                        echo '<div class="download-icon">';
                        echo '<a href="download_excel.php?subject_id=' . $subject_id . '">';
                        echo '<img src="icons/excel-icon.png" alt="Download Excel" width="30">';
                        echo '</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>ไม่มีรายวิชาที่จะแสดง</p>';
                }

                // ปิดการเชื่อมต่อฐานข้อมูล
                $mysqli->close();
                ?>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
