<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูรายละเอียดสรุปงาน";
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลการบ้าน
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;

$sql = "SELECT subject_pass, title, description, deadline, file_path, assigned_date
        FROM tb_homework
        WHERE homework_id = $homework_id";

$result = $mysqli->query($sql);

if ($result === false || $result->num_rows === 0) {
    die("การดึงข้อมูลการบ้านล้มเหลวหรือไม่พบข้อมูล: " . $mysqli->error);
}

$homework = $result->fetch_assoc();
?>

<style>
    .icon-img {
        width: 50px;
        height: auto;
        vertical-align: middle;
    }

    .btn-d {
        color: white;
        background-color: #BA55D3;
        border-color: black;
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

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>สรุปรายละเอียดการบ้าน</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="form-row">
                    <label>รหัสวิชา</label>
                    <h4><?= htmlspecialchars($homework['subject_pass']); ?></h4>
                </div>

                <div class="form-row">
                    <label>หัวข้อการบ้าน</label>
                    <h4><?= htmlspecialchars($homework['title']); ?></h4>
                </div>

                <div class="form-row">
                    <label>รายละเอียดการบ้าน:</label>
                    <h4><?= htmlspecialchars($homework['description']); ?></h4>
                </div>

                <div class="form-row">
                    <label>วันหมดเขต:</label>
                    <h4><?= htmlspecialchars($homework['deadline']); ?></h4>
                </div>

                <div class="form-row">
                    <label>วันที่สั่ง:</label>
                    <h4><?= htmlspecialchars($homework['assigned_date']); ?></h4>
                </div>

                <div class="form-group">
                    <label>ไฟล์งานที่แนบมา:</label>
                    <ul>
                        <?php
                        $files = json_decode($homework['file_path'], true); // Decode JSON file path
                        if (json_last_error() === JSON_ERROR_NONE && !empty($files)) { // Check for JSON errors and if files exist
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

                                // สร้างลิงก์ดาวน์โหลดไฟล์โดยใช้สคริปต์ teacher_download.php
                                $download_url = "teacher_download.php?file=" . urlencode($file);

                                echo "<li><a href='$download_url' style='color: black;'>
                                        <img src='icons/$icon' alt='$file_extension icon' class='icon-img'> "
                                        . htmlspecialchars($file_name) . "</a></li>";
                            }
                        } else {
                            echo "<li>ไม่มีไฟล์แนบ หรือเกิดข้อผิดพลาดในการแปลง JSON</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="x_title">
                <div class="clearfix"></div>
            </div>
            <div align="right">
                <button class="btn btn-d" onclick="window.history.back()">ย้อนกลับ</button>
            </div>
        </div>
    </div>

    <?php
    $mysqli->close();
    include('footer.php');
    ?>
