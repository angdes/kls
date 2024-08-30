<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อส่งการบ้าน";
    exit();
}

// ดึงข้อมูลนักเรียนจากเซสชัน
$member_id = $_SESSION['user']['member_id'];

// ดึงข้อมูลการบ้านทั้งหมดที่เกี่ยวข้องกับนักเรียนที่อาจารย์ได้เพิ่มให้
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($mysqli->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $mysqli->connect_error);
}

$sql = "SELECT tb_homework.homework_id, tb_homework.title, tb_homework.description, tb_homework.deadline, tb_subject.subject_name, tb_teacher.teacher_fullname, tb_student_homework.file_path AS student_file_path, tb_student_homework.checked 
        FROM tb_homework 
        JOIN tb_subject ON tb_homework.subject_id = tb_subject.subject_id 
        JOIN tb_teacher ON tb_homework.teacher_id = tb_teacher.teacher_id
        LEFT JOIN tb_student_homework ON tb_homework.homework_id = tb_student_homework.homework_id 
            AND tb_student_homework.member_id = '$member_id'
        WHERE tb_homework.homework_id IN (
            SELECT homework_id FROM tb_student_homework WHERE member_id = '$member_id'
        )";

$result = $mysqli->query($sql);

if (!$result) {
    die("เกิดข้อผิดพลาดในคำสั่ง SQL: " . $mysqli->error);
}

// ดึงข้อมูลการบ้านทั้งหมด
$homeworks = $result->fetch_all(MYSQLI_ASSOC);

if (!$homeworks) {
    die("ไม่พบข้อมูลการบ้านที่เกี่ยวข้อง");
}

if (isset($_POST['submit'])) {
    foreach ($_FILES['files']['name'] as $index => $file_name) {
        if ($_FILES['files']['error'][$index] == UPLOAD_ERR_OK) {
            $homework_id = $_POST['homework_ids'][$index];
            $target_dir = "../../backend/teacher/uploads/";
            $target_file = $target_dir . basename($file_name);
            if (move_uploaded_file($_FILES['files']['tmp_name'][$index], $target_file)) {
                // บันทึกข้อมูลการส่งงานในฐานข้อมูล
                $sql_check = "SELECT * FROM tb_student_homework WHERE homework_id = '$homework_id' AND member_id = '$member_id'";
                $check_result = $mysqli->query($sql_check);

                if (!$check_result) {
                    die("เกิดข้อผิดพลาดในคำสั่ง SQL: " . $mysqli->error);
                }

                if ($check_result->num_rows > 0) {
                    // ถ้ามีการส่งงานแล้ว ให้ทำการอัปเดตข้อมูล
                    $sql_update = "UPDATE tb_student_homework SET file_path='$target_file', submission_time=NOW() WHERE homework_id='$homework_id' AND member_id='$member_id'";
                    $mysqli->query($sql_update);
                    echo "อัปเดตงานเรียบร้อยแล้ว";
                } else {
                    // ถ้ายังไม่มีการส่งงาน ให้ทำการบันทึกใหม่
                    $sql_insert = "INSERT INTO tb_student_homework (homework_id, member_id, file_path, submission_time) 
                                   VALUES ('$homework_id', '$member_id', '$target_file', NOW())";
                    $mysqli->query($sql_insert);
                    echo "ส่งงานสำเร็จ";
                }
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: $file_name";
            }
        } else {
            echo "ไม่มีไฟล์ที่ถูกอัปโหลด: $file_name";
        }
    }
}
?>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>การบ้านของคุณ</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if ($homeworks): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>หัวข้อการบ้าน</th>
                                <th>วิชา</th>
                                <th>รายละเอียด</th>
                                <th>อาจารย์</th>
                                <th>กำหนดส่ง</th>
                                <th>สถานะ</th>
                                <th>ส่งงาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($homeworks as $homework): ?>
                                <tr>
                                    <td><?= htmlspecialchars($homework['title']); ?></td>
                                    <td><?= htmlspecialchars($homework['subject_name']); ?></td>
                                    <td><?= htmlspecialchars($homework['description']); ?></td>
                                    <td><?= htmlspecialchars($homework['teacher_fullname']); ?></td>
                                    <td><?= date("Y-m-d H:i:s", strtotime($homework['deadline'])); ?></td>
                                    <td>
                                        <?php if ($homework['checked']): ?>
                                            <p style="color: green;">ตรวจแล้ว</p>
                                        <?php else: ?>
                                            <p style="color: red;">ยังไม่ตรวจ</p>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($homework['student_file_path']): ?>
                                            <form method="post" action="<?= htmlspecialchars($homework['student_file_path']); ?>" target="_blank">
                                                <button type="submit" class="btn btn-success">ดูไฟล์ที่ส่ง</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="homework_ids[]" value="<?= htmlspecialchars($homework['homework_id']); ?>">
                                                <div class="form-group">
                                                    <input type="file" name="files[]" id="fileInput_<?= $homework['homework_id']; ?>" multiple onchange="updateFileCount(this)" required>
                                                    <span id="fileCount_<?= $homework['homework_id']; ?>">ยังไม่ได้เลือกไฟล์</span>
                                                </div>
                                                <button type="button" onclick="confirmSubmission(<?= $homework['homework_id']; ?>)" class="btn btn-success">ยืนยันการส่ง</button>
                                                <button type="button" onclick="cancelSubmission(<?= $homework['homework_id']; ?>)" class="btn btn-danger">ยกเลิก</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>ไม่พบข้อมูลการบ้านที่เกี่ยวข้อง</p>
                <?php endif; ?>
            </div>
        </div>   
    </div>
</div>

<script>
// ฟังก์ชันสำหรับแสดงจำนวนไฟล์ที่เลือก
function updateFileCount(input) {
    const fileCountSpan = document.getElementById('fileCount_' + input.id.split('_')[1]);
    const fileCount = input.files.length;
    fileCountSpan.textContent = fileCount + ' ไฟล์ที่เลือกแล้ว';
}

// ฟังก์ชันสำหรับยืนยันการส่งงาน
function confirmSubmission(homeworkId) {
    const form = document.querySelector(`input[name="homework_ids[]"][value="${homeworkId}"]`).closest('form');
    form.submit();
}

// ฟังก์ชันสำหรับยกเลิกการส่งงาน
function cancelSubmission(homeworkId) {
    const inputFile = document.getElementById('fileInput_' + homeworkId);
    inputFile.value = ''; // รีเซ็ตค่า input file
    updateFileCount(inputFile); // อัปเดตจำนวนไฟล์ที่เลือกให้เป็น 0
}
</script>

<?php
$mysqli->close();
include('footer.php');
?>
