<?php include('header.php'); ?>

<?php
// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลรายวิชาจากฐานข้อมูลที่นักเรียนลงทะเบียน
$student_id = $_SESSION['user']['member_id'];
$sql = "SELECT s.subject_pass, s.subject_name, s.subject_detail, s.subject_cover, s.subject_id, t.teacher_fullname 
        FROM tb_subject s
        JOIN tb_student_subject ss ON s.subject_id = ss.subject_id 
        JOIN tb_teacher t ON s.teacher_id = t.teacher_id
        WHERE ss.member_id = '$student_id'";
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
    <title>แสดงข้อมูลรายวิชาของนักเรียน</title>
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            width: calc(50% - 20px);
            display: flex;
            flex-direction: row;
            padding: 0px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .card:nth-child(odd) {
            justify-content: flex-start;
        }

        .card:nth-child(even) {
            justify-content: flex-end;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-image {
            flex: 1;
            background-color: #eaeaea;
            border-radius: 5px;
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
            margin-bottom: 5px;
            color: #C44AFD; /* สีที่กำหนดสำหรับตัวอักษร */
        }

        .card-pass {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .card-description {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stats {
            font-size: 14px;
            margin-top: 10px;
        }

        .stats .total {
            color: black;
        }

        .stats .submitted {
            color: green;
        }

        .stats .not-submitted {
            color: red;
        }

        .stats .checked {
            color: black;
        }

        .teacher-name {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>รายวิชาของนักเรียน</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="card-container">
                    <?php
                    // แสดงข้อมูลรายวิชาในรูปแบบการ์ด
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $subject_pass = htmlspecialchars($row['subject_pass']);
                            $subject_name = htmlspecialchars($row['subject_name']);
                            // $subject_detail = htmlspecialchars($row['subject_detail']);
                            $subject_cover = htmlspecialchars($row['subject_cover']);
                            $subject_id = htmlspecialchars($row['subject_id']);
                            $teacher_fullname = htmlspecialchars($row['teacher_fullname']);

                            // ปรับเส้นทางรูปปกให้ถูกต้อง
                            $image_path = !empty($subject_cover) ? '../../backend/teacher/uploads/' . rawurlencode(basename($subject_cover)) : '../../backend/teacher/uploads/default.png';

                            // คำสั่ง SQL เพื่อดึงข้อมูลจำนวนการบ้านทั้งหมด, ส่งแล้ว, ยังไม่ส่ง, และตรวจแล้ว
                            $total_sql = "SELECT COUNT(*) as total FROM tb_homework WHERE subject_id = '$subject_id'";
                            $submitted_sql = "SELECT COUNT(*) as submitted FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id') AND member_id = '$student_id' AND submission_time IS NOT NULL";
                            $not_submitted_sql = "SELECT COUNT(*) as not_submitted FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id') AND member_id = '$student_id' AND submission_time IS NULL";
                            $checked_sql = "SELECT COUNT(*) as checked FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id') AND member_id = '$student_id' AND checked = 1";

                            // ดึงข้อมูลการบ้าน
                            $total_result = $mysqli->query($total_sql);
                            $submitted_result = $mysqli->query($submitted_sql);
                            $not_submitted_result = $mysqli->query($not_submitted_sql);
                            $checked_result = $mysqli->query($checked_sql);

                            $total = $total_result->fetch_assoc()['total'];
                            $submitted = $submitted_result->fetch_assoc()['submitted'];
                            $not_submitted = $not_submitted_result->fetch_assoc()['not_submitted'];
                            $checked = $checked_result->fetch_assoc()['checked'];

                            echo '<div class="card" onclick="window.location.href=\'show_homework_student.php?subject_id=' . $subject_id . '\'">';
                            echo '<div class="card-image">';
                            echo '<img src="' . $image_path . '" alt="รูปปก">';
                            echo '</div>';
                            echo '<div class="card-content">';
                            echo '<div class="card-title">' . $subject_name . '</div>';
                            echo '<div class="card-pass">รหัสวิชา: ' . $subject_pass . '</div>';
                            // echo '<div class="card-description">' . $subject_detail . '</div>';
                            echo '<div class="teacher-fullname">ครูผู้สอน: ' . $teacher_fullname . '</div>'; // แสดงชื่อครูผู้สอน
                            echo '<div class="stats">';
                            echo '<p class="total">การบ้านทั้งหมด: ' . $total . '</p>';
                            echo '<p class="submitted">ส่งแล้ว: ' . $submitted . '</p>';
                            echo '<p class="not-submitted">ยังไม่ส่ง: ' . $not_submitted . '</p>';
                            echo '<p class="checked">ครูตรวจแล้ว: ' . $checked . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>ไม่มีรายวิชาที่นักเรียนลงทะเบียน</p>';
                    }

                    // ปิดการเชื่อมต่อฐานข้อมูล
                    $mysqli->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>

</html>
