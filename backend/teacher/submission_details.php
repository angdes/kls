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

// ดึงข้อมูลการส่งงาน
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;
$member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;

$sql = "SELECT tb_student_homework.submission_time, tb_member.member_fullname, tb_member.member_address, tb_member.member_tel, tb_member.member_email, tb_student_homework.file_path
        FROM tb_student_homework
        JOIN tb_member ON tb_student_homework.member_id = tb_member.member_id
        WHERE tb_student_homework.homework_id = $homework_id AND tb_student_homework.member_id = $member_id";

$result = $mysqli->query($sql);

if ($result === false || $result->num_rows === 0) {
    die("การดึงข้อมูลการส่งงานล้มเหลวหรือไม่พบข้อมูล: " . $mysqli->error);
}

$submission = $result->fetch_assoc();
?>

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
        align-items: center; /* Aligns items vertically centered */
        margin-bottom: 10px; /* Adds space between rows */
    }
    
    .form-row label {
        flex: 0 0 150px; /* Sets a fixed width for labels */
        margin-right: 10px; /* Adds space between label and value */
        font-weight: bold; /* Makes the label text bold */
    }
    
    .form-row h4 {
        margin: 0; /* Removes default margin around the h4 tag */
    }
</style>


<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>รายละเอียดการส่งงาน</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="form-row">
                    <label>ชื่อ-สกุลนักเรียน:</label>
                    <h4><?= htmlspecialchars($submission['member_fullname']); ?></h4>
                </div>
                
                <div class="form-row">
                    <label>ที่อยู่:</label>
                    <h4><?= htmlspecialchars($submission['member_address']); ?></h4>
                </div>
                <div class="form-row">
                    <label>ช่องทางติดต่อ:</label>
                    <h4><?= htmlspecialchars($submission['member_tel']); ?></h4>
                </div>
                <div class="form-row">
                    <label>อีเมล:</label>
                    <h4><?= htmlspecialchars($submission['member_email']); ?></h4>
                </div>
                <div class="form-row">
                    <label>วันที่และเวลาการส่ง:</label>
                    <h4><?= htmlspecialchars($submission['submission_time']); ?></h4>
                </div>
                <div class="form-group">
                    <label>ไฟล์ที่แนบมา:</label>
                    <ul>
                        <?php
                        $files = explode(',', $submission['file_path']);
                        foreach ($files as $file) {
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
                            echo "<li><a href='" . htmlspecialchars($file) . "' target='_blank'>
                                <img src='icons/$icon' alt='$file_extension icon' class='icon-img'> "
                                . htmlspecialchars($file_name) . "</a></li>";
                        }
                        ?>
                    </ul>
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