<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูการบ้าน";
    exit();
}

// ดึงค่า student_id จากเซสชัน
$student_id = $_SESSION['user']['member_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า subject_id จาก URL
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;

if (!$subject_id) {
    echo "ไม่พบรหัสวิชา";
    exit();
}

// ดึงข้อมูลการบ้านจากฐานข้อมูลตามรายวิชาที่เลือก รวมถึงไฟล์ที่อาจารย์อัปโหลด
$sql = "SELECT h.homework_id, h.title, h.description, h.assigned_date, h.deadline, h.file_path AS teacher_file, 
               sh.submission_time, sh.checked, sh.grade 
        FROM tb_homework h
        LEFT JOIN tb_student_homework sh ON h.homework_id = sh.homework_id AND sh.member_id = '$student_id'
        WHERE h.subject_id = '$subject_id'";
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
    <title>รายการการบ้านในวิชา</title>
    <style>
        .report-section {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
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

        .btn-m {
            color: white;
            background-color: #FF00FF;
            border-color: black;
        }

        .btn-d {
            color: white;
            background-color: #BA55D3;
            border-color: black;
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>รายการการบ้านในวิชา</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php
                if ($result->num_rows > 0) {
                    $index = 1;
                    while ($row = $result->fetch_assoc()) {
                        $submission_status = $row['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ';
                        $submission_time = $row['submission_time'] ? htmlspecialchars($row['submission_time']) : '<span style="color: red">ยังไม่ได้ส่ง</span>';
                        
                        // แยกไฟล์ที่อาจารย์อัปโหลด
                        $teacher_files = json_decode($row['teacher_file'], true);
                ?>
                        <div class="report-section">
                            <div class="form-row">
                                <label>หัวข้อที่:</label>
                                <h4><?= $index++; ?></h4>
                            </div>
                            <div class="form-row">
                                <label>หัวข้อการบ้าน:</label>
                                <h4><?= htmlspecialchars($row['title'] ?? ''); ?></h4>
                            </div>
                            <div class="form-row">
                                <label>รายละเอียดการบ้าน:</label>
                                <h4><?= htmlspecialchars($row['description'] ?? ''); ?></h4>
                            </div>
                            <div class="form-row">
                                <label>วันที่สั่ง:</label>
                                <h4 style="color: #28a745;"><?= htmlspecialchars($row['assigned_date'] ?? ''); ?></h4>
                            </div>
                            <div class="form-row">
                                <label>วันหมดเขต:</label>
                                <h4 style="color: red;"><?= htmlspecialchars($row['deadline'] ?? ''); ?></h4>
                            </div>
                            <div class="form-row">
                                <label>ไฟล์งานที่อาจารย์แนบ:</label>
                                <h4>
                                    <?php if (!empty($teacher_files)) { ?>
                                        <a href="teacher_file_summary.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>" class="btn btn-m">ดูไฟล์งาน</a>
                                    <?php } else { ?>
                                        <span style="color: gray">ไม่มีไฟล์</span>
                                    <?php } ?>
                                </h4>
                            </div>
                            <div class="form-row">
                                <label>เวลาที่ส่ง:</label>
                                <h4 style="color: magenta;"><?= $submission_time; ?></h4>
                            </div>
                            <div class="form-row">
                                <label>คะแนน:</label>
                                <h4><?= htmlspecialchars($row['grade'] ?? 'ยังไม่มีคะแนน'); ?></h4>
                            </div>
                            <div class="form-row">
                                <label>สถานะการตรวจ:</label>
                                <h4><?= $submission_status; ?></h4>
                            </div>
                            <div class="form-row">
                                <label>การส่งงาน:</label>
                                <h4>
                                    <?php if (!$row['submission_time']) { ?>
                                        <form id="homework_form_<?= $row['homework_id']; ?>" action="submit_homework.php?subject_id=<?= $subject_id ?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="homework_id" value="<?= $row['homework_id']; ?>">
                                            
                                            <input type="file" name="homework_files[]" multiple required>
                                            <br>
                                            <button type="button" class="btn btn-m" onclick="confirmSubmit(<?= $row['homework_id']; ?>)">ยืนยันการส่ง</button>
                                            <button type="button" class="btn btn-d" onclick="cancelSubmit(<?= $row['homework_id']; ?>)">ยกเลิกการส่ง</button>
                                        </form>
                                    <?php } else { ?>
                                        <button class="btn btn-m" disabled>ส่งแล้ว</button>
                                    <?php } ?>
                                </h4>
                            </div>
                            <div class="form-row">
                                <label>สรุปการส่งงาน:</label>
                                <h4>
                                    <?php if ($row['submission_time']) { ?>
                                        <a href="submission_details_member.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>&member_id=<?= $student_id; ?>" class="btn btn-d">ดูสรุปการส่งงาน</a>
                                    <?php } else { ?>
                                        <span style="color: red">ยังไม่ได้ส่ง</span>
                                    <?php } ?>
                                </h4>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>ไม่มีการบ้านที่จะแสดง</p>';
                }

                $mysqli->close();
                ?>
            </div>
        </div>

        <div class="x_title">
            <div class="clearfix"></div>
        </div>
        <div align="right">
            <a href="show_student_subjects.php"><button class="btn btn-d">ย้อนกลับ</button></a>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        function confirmSubmit(homework_id) {
            const form = document.getElementById('homework_form_' + homework_id);
            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการส่งงานนี้?')) {
                form.submit();
            }
        }

        function cancelSubmit(homework_id) {
            const form = document.getElementById('homework_form_' + homework_id);
            form.reset();
        }
    </script>
</body>

</html>
