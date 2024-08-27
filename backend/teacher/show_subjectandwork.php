<?php include('header.php'); ?>

<?php
// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลรายวิชาจากฐานข้อมูล
$sql = "SELECT subject_pass, subject_name, subject_detail, subject_cover FROM tb_subject";
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
            margin: 20px;
            width: 100%;
            max-width: 1000px; /* กำหนดขนาดสูงสุดของคอนเทนเนอร์ */
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            width: calc(50% - 20px); /* กำหนดให้การ์ดมีขนาดครึ่งหนึ่งของความกว้างทั้งหมดลบด้วยระยะขอบ */
            display: flex;
            flex-direction: row;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .card:nth-child(odd) {
            justify-content: flex-start; /* การ์ดที่เป็นเลขคี่จะแสดงด้านซ้าย */
        }
        .card:nth-child(even) {
            justify-content: flex-end; /* การ์ดที่เป็นเลขคู่จะแสดงด้านขวา */
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
        }
        .card-description {
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>รายวิชาที่สอน</h2>
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
            while($row = $result->fetch_assoc()) {
                $subject_pass = htmlspecialchars($row['subject_pass']);
                $subject_name = htmlspecialchars($row['subject_name']);
                $subject_detail = htmlspecialchars($row['subject_detail']);
                $subject_cover = htmlspecialchars($row['subject_cover']);
                
                echo '<div class="card" onclick="window.location.href=\'show_homework.php?subject_pass=' . $subject_pass . '\'">';
                echo '<div class="card-image">';
                echo '<img src="' . $subject_cover . '" alt="รูปปก">';
                echo '</div>';
                echo '<div class="card-content">';
                echo '<div class="card-title">' . $subject_name . '</div>';
                echo '<div class="card-description">' . $subject_detail . '</div>';
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


<?php include('footer.php'); ?>
