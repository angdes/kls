<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อแก้ไขการบ้าน";
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

// ดึงข้อมูลการบ้านที่ต้องการแก้ไข
$homework_id = $_GET['homework_id'];
$sql = "SELECT * FROM tb_homework WHERE homework_id = $homework_id AND teacher_id = $teacher_id";
$result = $mysqli->query($sql);

if ($result->num_rows == 0) {
    echo "ไม่พบข้อมูลการบ้าน";
    exit();
}

$homework = $result->fetch_assoc();

// ดึงข้อมูลรายวิชาของครู
$sql = "SELECT * FROM tb_subject WHERE teacher_id = $teacher_id";
$subjects = $mysqli->query($sql);

// ประมวลผลการแก้ไขการบ้าน
if (isset($_POST['submit'])) {
    $subject_id = $_POST['subject_id'];
    $homework_title = $_POST['homework_title'];
    $homework_description = $_POST['homework_description'];
    $deadline = $_POST['deadline'];
    $assigned_date = $_POST['assigned_date'];
    
    $file_path = $homework['file_path'];

    // ตรวจสอบการอัปโหลดไฟล์ใหม่
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
        $file_path = 'uploads/' . $file_name;

        // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
        if (!move_uploaded_file($file_tmp, $file_path)) {
            echo "อัปโหลดไฟล์ล้มเหลว";
            exit();
        }
    }

    // อัปเดตข้อมูลการบ้านในฐานข้อมูล
    $sql = "UPDATE tb_homework 
            SET subject_id='$subject_id', title='$homework_title', description='$homework_description', deadline='$deadline', file_path='$file_path', assigned_date='$assigned_date'
            WHERE homework_id = $homework_id AND teacher_id = $teacher_id";
    
    if ($mysqli->query($sql) === TRUE) {
        echo $cls_conn->show_message('แก้ไขการบ้านสำเร็จ');
        echo $cls_conn->goto_page(1,'show_homework.php');
        exit();
    } else {
        echo "แก้ไขการบ้านล้มเหลว: " . $mysqli->error;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$mysqli->close();
?>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>แก้ไขการบ้าน</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="update_homework_form" class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject">เลือกวิชา<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="subject" name="subject_id" class="form-control col-md-7 col-xs-12" required>
                                <option value="">เลือกวิชา</option>
                                <?php
                                while ($row = $subjects->fetch_assoc()) {
                                    $selected = $row['subject_id'] == $homework['subject_id'] ? 'selected' : '';
                                    ?>
                                    <option value="<?= htmlspecialchars($row['subject_id']); ?>" <?= $selected; ?>>
                                        <?= htmlspecialchars($row['subject_pass']) . " - " . htmlspecialchars($row['subject_name']); ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="homework_title">หัวข้อการบ้าน<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="homework_title" name="homework_title" value="<?= htmlspecialchars($homework['title']); ?>" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="homework_description">รายละเอียดการบ้าน<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="homework_description" name="homework_description" rows="4" required="required" class="form-control col-md-7 col-xs-12"><?= htmlspecialchars($homework['description']); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assigned_date">วันที่สั่ง<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="datetime-local" id="assigned_date" name="assigned_date" value="<?= htmlspecialchars($homework['assigned_date']); ?>" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deadline">วันหมดเขต<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="datetime-local" id="deadline" name="deadline" value="<?= htmlspecialchars($homework['deadline']); ?>" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="file">ไฟล์การบ้านปัจจุบัน: 
                            <a href="<?= htmlspecialchars($homework['file_path']); ?>" target="_blank" class="btn btn-info-small">ดูไฟล์</a>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" id="file" name="file" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" class="btn btn-success">บันทึกการแก้ไข</button>
                            <button type="button" class="btn btn-danger" onclick="window.history.back();">ยกเลิก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
