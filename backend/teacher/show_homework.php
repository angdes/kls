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
    .btn-m1 {
        color: white;
        background-color: #FF00FF;
        border-color: black;
    }

    .btn-d1 {
        color: white;
        background-color: #BA55D3;
        border-color: black;
    }
   

    th {
        font-size: 13px;
        /* กำหนดขนาดตัวอักษรที่ต้องการ */
    }

    td {
        font-size: 12px;
    }
</style>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>รายการการบ้านสำหรับวิชา <?= htmlspecialchars($subject_pass); ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <a href="add_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>" class="btn btn-m">เพิ่มการบ้านใหม่</a>
                <br>
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>หัวข้อการบ้าน</th>
                            <th>รายละเอียด</th>
                            <th>วันที่สั่ง</th>
                            <th>วันหมดเขต</th>
                            <th>ตรวจแล้ว</th>
                            <th>ยังไม่ตรวจ</th>
                            <th>ยังไม่ส่ง</th>
                            <th>ตรวจงาน</th>
                            <th>สรุปงาน</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <tr>
                                    <td><?= $index++; ?></td>
                                    <td><?= htmlspecialchars($row['title']); ?></td>
                                    <td><?= htmlspecialchars($row['description']); ?></td>
                                    <td><?= htmlspecialchars($row['assigned_date']); ?></td>
                                    <td><?= htmlspecialchars($row['deadline']); ?></td>
                                    <td style="color: green;"><?= $checked_count; ?></td>
                                    <td style="color: black;"><?= $unchecked_count; ?></td>
                                    <td style="color: red;"><?= $not_submitted_count; ?></td>
                                    <td>
                                        <a href="check_homework.php?homework_id=<?= $homework_id; ?>" class="btn btn-m1">ตรวจงาน</a>
                                    </td>
                                    <td>
                                        <a href="summary.php?homework_id=<?= $homework_id; ?>" class="btn btn-d1">สรุปงาน</a>
                                    </td>
                                    <td>
                                        <a href="delete_homework.php?homework_id=<?= $homework_id; ?>&subject_pass=<?= $subject_pass; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" /></a>
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
            <div class="x_title">
                <div class="clearfix"></div>
            </div>
            <div align="right">
                <a href="show_subjectandwork.php"><button class="btn btn-d">ย้อนกลับ</button></a>
            </div>
        </div>

    </div>
</div>
</div>
<?php include('footer.php'); ?>