<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูรายละเอียดการส่งงาน";
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า homework_id และ member_id จาก URL
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;
$member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;

// ดึงข้อมูลการส่งงาน
$sql = "SELECT sh.submission_time, sh.file_path, sh.grade, sh.feedback, m.member_fullname 
        FROM tb_student_homework sh 
        JOIN tb_member m ON sh.member_id = m.member_id 
        WHERE sh.homework_id = '$homework_id' AND sh.member_id = '$member_id'";
$result = $mysqli->query($sql);

if ($result === false || $result->num_rows === 0) {
    die("การดึงข้อมูลการส่งงานล้มเหลวหรือไม่พบข้อมูล: " . $mysqli->error);
}

$submission = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สรุปการส่งงาน</title>
    <style>
        .icon-img {
            width: 50px;
            height: auto;
            vertical-align: middle;
        }

        .btn-green {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

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
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>สรุปการส่งงานของนักเรียน: <?= htmlspecialchars($submission['member_fullname']); ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="form-row">
                        <label>เวลาที่ส่ง:</label>
                        <h4><?= htmlspecialchars($submission['submission_time']); ?></h4>
                    </div>
                    <div class="form-row">
                        <label>ไฟล์ที่ส่ง:</label>
                        <ul>
                            <?php
                            if (!empty($submission['file_path'])) {
                                $files = json_decode($submission['file_path'], true); // ใช้ JSON decode สำหรับแยกไฟล์
                                foreach ($files as $file) {
                                    $file = trim($file); // ตัดช่องว่างที่อาจเกิดขึ้น
                                    if (!empty($file)) {
                                        $file_extension = pathinfo($file, PATHINFO_EXTENSION);
                                        $file_name = basename($file);
                                        switch ($file_extension) {
                                            case 'docx':
                                            case 'doc':
                                                $icon = 'word-icon.jpg';
                                                break;
                                            case 'pdf':
                                                $icon = 'pdf-icon.png';
                                                break;
                                            case 'xlsx':
                                            case 'xls':
                                                $icon = 'excel-icon.png';
                                                break;
                                            default:
                                                $icon = 'file-icon.png';
                                        }
                                        echo "<li><a href='download.php?file=" . urlencode(basename($file)) . "' style='color: black;'>
                                            <img src='icons/$icon' alt='$file_extension icon' class='icon-img'> "
                                            . htmlspecialchars($file_name) . "</a></li>";
                                    }
                                }
                            } else {
                                echo "<p>ไม่มีไฟล์ที่ส่ง</p>";
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="form-row">
                        <label>คะแนน:</label>
                        <h4><?= htmlspecialchars($submission['grade'] ?? 'ยังไม่มีคะแนน'); ?></h4>
                    </div>
                    <div class="form-row">
                        <label>ความคิดเห็น:</label>
                        <h4><?= htmlspecialchars($submission['feedback'] ?? 'ไม่มีความคิดเห็น'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="x_title">
                <div class="clearfix"></div>
            </div>
            <div align="right">
                <button class="btn btn-green" onclick="window.history.back()">ย้อนกลับ</button>
            </div>
        </div>
    </div>

    <?php
    $mysqli->close();
    include('footer.php');
    ?>
</body>

</html>