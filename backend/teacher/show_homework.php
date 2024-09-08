<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูการบ้าน";
    exit();
}

// ดึงค่า teacher_id จากเซสชัน
$teacher_id = $_SESSION['user']['teacher_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า subject_pass จาก URL
$subject_pass = $_GET['subject_pass'];

// ดึงข้อมูลการบ้านจากฐานข้อมูลตามรายวิชาที่เลือก
$sql = "SELECT homework_id, title, description, assigned_date, deadline FROM tb_homework WHERE subject_pass = '$subject_pass'";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}
?>

<style>
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

    .btn-custom {
        background-color: #FF00FF;
        color: white;
        border-color: black;
        padding: 9px 15px;
        font-size: 12px;
        margin-right: 5px;
    }

    .btn-m {
        color: white;
        background-color: #BA55D3;
        border-color: black;
    }

    .btn-m:hover {
        background-color: deeppink;
        color: white;
    }

    .btn-d {
        background-color: magenta;
        border-color: magenta;
        color: white;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-d:hover {
        background-color: deeppink;
        color: white;
    }


    .report-section {
        background-color: #f9f9f9;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>รายงานการบ้านสำหรับวิชา <?= htmlspecialchars($subject_pass); ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <a href="add_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>" class="btn btn-custom">เพิ่มการบ้านใหม่</a>
                <br><br>

                <?php
                $index = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $homework_id = htmlspecialchars($row['homework_id']);

                        // คำนวณ "ตรวจแล้ว" และ "ยังไม่ตรวจ" จากฐานข้อมูล
                        $checked_sql = "SELECT COUNT(*) as checked_count FROM tb_student_homework WHERE homework_id = $homework_id AND checked = 1";
                        $unchecked_sql = "SELECT COUNT(*) as unchecked_count FROM tb_student_homework WHERE homework_id = $homework_id AND (checked = 0 OR checked IS NULL)";

                        $checked_result = $mysqli->query($checked_sql);
                        $unchecked_result = $mysqli->query($unchecked_sql);

                        $checked_count = $checked_result->fetch_assoc()['checked_count'] ?? 0;
                        $unchecked_count = $unchecked_result->fetch_assoc()['unchecked_count'] ?? 0;

                        // คำนวณ "ยังไม่ส่ง" โดยดึงข้อมูลนักเรียนทั้งหมดในวิชาและลบจำนวนที่ส่งงานแล้ว
                        $students_sql = "SELECT COUNT(*) as total_students FROM tb_student_subject WHERE subject_id = (SELECT subject_id FROM tb_subject WHERE subject_pass = '$subject_pass' LIMIT 1)";
                        $submitted_students_sql = "SELECT COUNT(DISTINCT member_id) as submitted_count FROM tb_student_homework WHERE homework_id = $homework_id";

                        $students_result = $mysqli->query($students_sql);
                        $submitted_result = $mysqli->query($submitted_students_sql);

                        $total_students = $students_result->fetch_assoc()['total_students'] ?? 0;
                        $submitted_count = $submitted_result->fetch_assoc()['submitted_count'] ?? 0;
                        $not_submitted_count = $total_students - $submitted_count;
                ?>

                        <div class="report-section">
                            <div class="form-row">
                                <label>หัวข้อการบ้าน:</label>
                                <h4><?= htmlspecialchars($row['title']); ?></h4>
                            </div>

                            <div class="form-row">
                                <label>รายละเอียด:</label>
                                <h4><?= htmlspecialchars($row['description']); ?></h4>
                            </div>

                            <div class="form-row">
                                <label>วันที่สั่ง:</label>
                                <h4><?= htmlspecialchars($row['assigned_date']); ?></h4>
                            </div>

                            <div class="form-row">
                                <label>วันหมดเขต:</label>
                                <h4><?= htmlspecialchars($row['deadline']); ?></h4>
                            </div>

                            <div class="form-row">
                                <label>ตรวจแล้ว:</label>
                                <h4 style="color: green;"><?= $checked_count; ?></h4>
                            </div>

                            <div class="form-row">
                                <label>ยังไม่ตรวจ:</label>
                                <h4 style="color: black;"><?= $unchecked_count; ?></h4>
                            </div>

                            <div class="form-row">
                                <label>ยังไม่ส่ง:</label>
                                <h4 style="color: red;"><?= $not_submitted_count; ?></h4>
                            </div>

                            <div class="form-row">
                                <a href="check_homework.php?homework_id=<?= $homework_id; ?>" class="btn btn-d">ตรวจงานนักเรียนในรายวิชานี้</a>
                                <a href="summary.php?homework_id=<?= $homework_id; ?>" class="btn btn-d">สรุปงานที่มอบ</a>
                                <a href="edit_homework.php?homework_id=<?= $homework_id; ?>&subject_pass=<?= $subject_pass; ?>" class="btn btn-m" style="padding: 9px 15px; font-size: 12px;">
                                    <i class="fas fa-edit"></i> แก้ไข
                                </a>
                                <a href="delete_homework.php?homework_id=<?= $homework_id; ?>&subject_pass=<?= $subject_pass; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')" class="btn btn-m" style="padding: 9px 15px; font-size: 12px;">ลบ</a>
                            </div>
                        </div>
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>ไม่มีการบ้านที่จะแสดง</p>';
                }

                $mysqli->close();
                ?>
            </div>

            <div align="right">
                <a href="show_subjectandwork.php"><button class="btn btn-m">ย้อนกลับ</button></a>
            </div>
        </div>

    </div>
</div>

<?php include('footer.php'); ?>