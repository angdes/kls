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
    <title>การบ้านในวิชา</title>
    <style>
        .btn-green {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="right_col" role="main">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2 style="color: magenta;">รายการการบ้านในวิชา</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>หัวข้อการบ้าน</th>
                            <th>รายละเอียด</th>
                            <th>วันที่สั่ง</th>
                            <th>วันหมดเขต</th>
                            <th>ไฟล์งาน</th>
                            <th>เวลาที่ส่ง</th>
                            <th>คะแนน</th>
                            <th>สถานะการตรวจ</th>
                            <th>การส่งงาน</th>
                            <th>สรุป</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $submission_status = $row['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ';
                                $submission_time = $row['submission_time'] ? htmlspecialchars($row['submission_time']) : '<span style="color: red">ยังไม่ได้ส่ง</span>';
                                
                                // แยกไฟล์ที่อาจารย์อัปโหลด
                                $teacher_files = json_decode($row['teacher_file'], true);
                                ?>
                                <tr>
                                    <td><?= $index++; ?></td>
                                    <td><?= htmlspecialchars($row['title']); ?></td>
                                    <td><?= htmlspecialchars($row['description']); ?></td>
                                    <td style="color: #28a745;"><?= htmlspecialchars($row['assigned_date']); ?></td>
                                    <td style="color: red;"><?= htmlspecialchars($row['deadline']); ?></td>
                                    <td>
                                        <?php if (!empty($teacher_files)) { ?>
                                            <a href="teacher_file_summary.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>" class="btn btn-primary">ดูไฟล์งาน</a>
                                        <?php } else { ?>
                                            <span style="color: gray">ไม่มีไฟล์</span>
                                        <?php } ?>
                                    </td>
                                    <td style="color: magenta;"><?= $submission_time; ?></td>
                                    <td><?= htmlspecialchars($row['grade']) ?? 'ยังไม่มีคะแนน'; ?></td>
                                    <td><?= $submission_status; ?></td>
                                    <td>
                                        <?php if (!$row['submission_time']) { ?>
                                            <form id="homework_form_<?= $row['homework_id']; ?>" action="submit_homework.php?subject_id=<?= $subject_id ?>" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="homework_id" value="<?= $row['homework_id']; ?>">
                                                <input type="file" name="homework_files[]" multiple required>
                                                <button type="button" class="btn btn-success" onclick="confirmSubmit(<?= $row['homework_id']; ?>)">ยืนยันการส่ง</button>
                                                <button type="button" class="btn btn-danger" onclick="cancelSubmit(<?= $row['homework_id']; ?>)">ยกเลิกการส่ง</button>
                                            </form>
                                        <?php } else { ?>
                                            <button class="btn btn-info" disabled>ส่งแล้ว</button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($row['submission_time']) { ?>
                                            <a href="submission_details_member.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>&member_id=<?= $student_id; ?>" class="btn btn-primary">ดูสรุปการส่งงาน</a>
                                        <?php } else { ?>
                                            <span style="color: red">ยังไม่ได้ส่ง</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="11">ไม่มีการบ้านที่จะแสดง</td></tr>';
                        }

                        $mysqli->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="x_title">
            <div class="clearfix"></div>
        </div>
        <div align="right">
            <a href="show_student_subjects.php"><button class="btn btn-green" >ย้อนกลับ</button></a>
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
