<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูไฟล์ที่อาจารย์ส่ง";
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า homework_id จาก URL
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;

// ดึงข้อมูลไฟล์ที่อาจารย์ส่งจากฐานข้อมูล
$sql = "SELECT file_path FROM tb_homework WHERE homework_id = '$homework_id'";
$result = $mysqli->query($sql);

if ($result === false || $result->num_rows === 0) {
    die("การดึงข้อมูลไฟล์ที่อาจารย์ส่งล้มเหลวหรือไม่พบข้อมูล: " . $mysqli->error);
}

$homework = $result->fetch_assoc();
$teacher_files = json_decode($homework['file_path'], true);

// ตรวจสอบว่าการแปลง JSON เป็นอาร์เรย์เกิดข้อผิดพลาดหรือไม่
if (json_last_error() !== JSON_ERROR_NONE) {
    die("เกิดข้อผิดพลาดในการแปลงข้อมูลไฟล์จาก JSON: " . json_last_error_msg());
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สรุปไฟล์ที่อาจารย์ส่ง</title>
    <style>
        .btn-green {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .icon-img {
            width: 30px;
            height: auto;
            vertical-align: middle;
            margin-right: 8px;
        }

        .file-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .file-name {
            margin-left: 10px; /* เพิ่มระยะห่างระหว่างไอคอนและชื่อไฟล์ */
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>ไฟล์ที่อาจารย์ส่งสำหรับการบ้านนี้</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <ul class="file-list">
                        <?php if (!empty($teacher_files) && is_array($teacher_files)) {
                            foreach ($teacher_files as $file) {
                                $file_name = basename($file);
                                $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                                // ตรวจสอบประเภทไฟล์เพื่อแสดงไอคอนที่ถูกต้อง
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

                                echo "<li>
                                        <a href='../../backend/teacher/uploads/" . rawurlencode($file_name) . "' style='color: black;' download='$file_name'>
                                            <img src='icons/$icon' alt='$file_extension icon' class='icon-img'>
                                            <span class='file-name'>" . htmlspecialchars($file_name) . "</span>
                                        </a>
                                      </li>";
                            }
                        } else {
                            echo "<p>ไม่มีไฟล์ที่อาจารย์ส่ง</p>";
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
</body>
</html>
