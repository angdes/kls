<?php

include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อเพิ่มการบ้าน";
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

// ดึงข้อมูลรายวิชาของครู
$sql = "SELECT * FROM tb_subject WHERE teacher_id = $teacher_id";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}

// ประมวลผลการเพิ่มการบ้าน
if (isset($_POST['submit'])) {
    $subject_id = $_POST['subject_id'];
    $homework_title = $_POST['homework_title'];
    $homework_description = $_POST['homework_description'];
    $deadline = $_POST['deadline'];
    $assigned_date = $_POST['assigned_date'];
    
    $file_path = null;

    // ตรวจสอบการอัปโหลดไฟล์
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

    // คำสั่ง SQL สำหรับเพิ่มการบ้าน
    $sql = "INSERT INTO tb_homework (subject_id, teacher_id, title, description, deadline, file_path, assigned_date)
            VALUES ('$subject_id', '$teacher_id', '$homework_title', '$homework_description', '$deadline', '$file_path', '$assigned_date')";
    if ($mysqli->query($sql) === TRUE) {
        echo $cls_conn->show_message('เพิ่มการบ้านสำเร็จ');
        echo $cls_conn->goto_page(1,'show_homework.php');
        exit();
    } else {
        echo "เพิ่มการบ้านล้มเหลว: " . $mysqli->error;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$mysqli->close();
?>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>เพิ่มการบ้าน</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="add_homework_form" class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject">เลือกวิชา<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="subject" name="subject_id" class="form-control col-md-7 col-xs-12" required>
                                <option value="">เลือกวิชา</option>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <option value="<?= htmlspecialchars($row['subject_id']); ?>">
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
                            <input type="text" id="homework_title" name="homework_title" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="homework_description">รายละเอียดการบ้าน<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="homework_description" name="homework_description" rows="4" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assigned_date">วันที่สั่ง<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="datetime-local" id="assigned_date" name="assigned_date" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deadline">วันหมดเขต<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="datetime-local" id="deadline" name="deadline" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="file">ไฟล์การบ้าน:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" id="file" name="file" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" class="btn btn-success">บันทึกการบ้าน</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='show_homework.php';">ยกเลิก</button>
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
