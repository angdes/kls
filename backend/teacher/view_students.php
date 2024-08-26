<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูข้อมูลนักเรียน";
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

// ดึงข้อมูลการบ้าน
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;

// ตรวจสอบว่ามีการเลือกการบ้านหรือไม่
if ($homework_id <= 0) {
    die("รหัสการบ้านไม่ถูกต้อง");
}

// ดึงข้อมูลการบ้าน รวมถึง assigned_date และ deadline
$sql_homework = "SELECT assigned_date, deadline
                 FROM tb_homework
                 WHERE homework_id = $homework_id";

$result_homework = $mysqli->query($sql_homework);

if ($result_homework === false || $result_homework->num_rows === 0) {
    die("การดึงข้อมูลการบ้านล้มเหลวหรือไม่พบข้อมูล: " . $mysqli->error);
}

$homework_row = $result_homework->fetch_assoc();
$assigned_date = $homework_row['assigned_date'];
$deadline = $homework_row['deadline'];

// ดึงข้อมูลนักเรียนที่ได้รับการบ้านพร้อมกับเวลาที่ส่งและสถานะการตรวจ
$sql_students = "SELECT tb_member.*, tb_student_homework.homework_id, tb_student_homework.file_path, tb_student_homework.checked, tb_student_homework.submission_time
                 FROM tb_member
                 JOIN tb_student_homework ON tb_member.member_id = tb_student_homework.member_id
                 WHERE tb_student_homework.homework_id = $homework_id";

$result_students = $mysqli->query($sql_students);

// ตรวจสอบการดึงข้อมูล
if ($result_students === false) {
    die("การดึงข้อมูลนักเรียนล้มเหลว: " . $mysqli->error);
}

// ฟังก์ชันสำหรับอัปเดตสถานะการตรวจ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $homework_id = intval($_POST['homework_id']);
    $member_id = intval($_POST['member_id']);
    $sql_update = "UPDATE tb_student_homework SET checked = 1 WHERE homework_id = $homework_id AND member_id = $member_id";

    if ($mysqli->query($sql_update)) {
        echo "<script>alert('อัปเดตสถานะการตรวจเรียบร้อยแล้ว'); window.location.href = window.location.href;</script>";
    } else {
        echo "การอัปเดตสถานะการตรวจล้มเหลว: " . $mysqli->error;
    }
}
?>

<style>
    .btn-hotpink {
        background-color: hotpink;
        border-color: hotpink;
        color: black;
    }

    .late-submission {
        color: red;
    }

    .on-time {
        color: green;
    }

    .btn-custom {
        font-size: 15px;
        color: white;
        background-color: #28a745;
        border: none;
        border-radius: 4px;
        padding: 10px 20px; /* Adjust padding as needed */
        text-decoration: none; /* Remove underline */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease; /* Smooth color transition */
    }

    .btn-custom:hover {
        background-color: #218838; /* Darker green on hover */
    }

    .btn-custom i {
        margin-right: 8px; /* Space between icon and text */
    }
</style>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>ข้อมูลนักเรียนที่ได้รับการบ้าน</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div align="left">
                    <a href="show_homework.php">
                        <button class="btn btn-hotpink">ย้อนกลับ</button>
                    </a>
                </div>
                <table id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>รหัสนักเรียน</th>
                            <th>ชื่อ-สกุล</th>
                            
                            <th>วันที่สั่ง</th>
                            <th>วันหมดเขต</th>
                            <th>เวลาส่งการบ้าน</th>
                            <th>สถานะการตรวจ</th>
                            <th>ดูไฟล์งาน</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_students->num_rows > 0) {
                            while ($row = $result_students->fetch_assoc()) {
                                $submissionTime = strtotime($row['submission_time']);
                                $deadlineTime = strtotime($deadline);
                                $isLate = $submissionTime > $deadlineTime;
                                $submissionClass = $isLate ? 'late-submission' : 'on-time';
                        ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['member_number']); ?></td>
                                    <td><?= htmlspecialchars($row['member_fullname']); ?></td>
                                    
                                    <td><?= htmlspecialchars($assigned_date); ?></td>
                                    <td><?= htmlspecialchars($deadline); ?></td>
                                    <td class="<?= $submissionClass; ?>">
                                        <?php if (!empty($row['submission_time'])) { ?>
                                            <?= htmlspecialchars($row['submission_time']); ?>
                                            <?php if ($isLate) { ?>
                                                (ส่งช้า)
                                            <?php } else { ?>
                                                (ส่งตามเวลา)
                                            <?php } ?>
                                        <?php } else { ?>
                                            <p style="color: red">ยังไม่ได้ส่งงาน</p>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?= $row['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ'; ?>
                                        <?php if (!$row['checked']) { ?>
                                            <form method="post">
                                                <input type="hidden" name="homework_id" value="<?= $row['homework_id']; ?>">
                                                <input type="hidden" name="member_id" value="<?= $row['member_id']; ?>">
                                                <button type="submit" class="btn btn-success">ยืนยันการตรวจ</button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['submission_time'])) { // ตรวจสอบว่ามีการส่งงานหรือไม่ 
                                        ?>
                                            <?php if (!empty($row['file_path'])) { ?>
                                                <a href="submission_details.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>&member_id=<?= htmlspecialchars($row['member_id']); ?>"
                                                    class="btn btn-custom">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                            <?php } else { ?>
                                                <span>ไม่มีไฟล์</span>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <p style="color: red">ยังไม่ได้ส่งงาน</p>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="delete_view_students.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>&member_id=<?= htmlspecialchars($row['member_id']); ?>" onclick="return confirm('คุณต้องการลบนักเรียนนี้หรือไม่?')">
                                            <img src="../../images/delete.png" />
                                        </a>
                                    </td>

                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="10">ไม่มีข้อมูลนักเรียน</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$mysqli->close();
include('footer.php');
?>