<?php
ob_start(); // เริ่มบัฟเฟอร์เอาต์พุต
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

// รับค่า subject_pass จาก URL
$subject_pass = $_GET['subject_pass'];

// ดึง subject_id ที่สัมพันธ์กับ subject_pass
$subject_sql = "SELECT subject_id FROM tb_subject WHERE subject_pass = '$subject_pass' LIMIT 1";
$subject_result = $mysqli->query($subject_sql);

// ตรวจสอบว่าพบ subject_id หรือไม่
if ($subject_result->num_rows > 0) {
    $subject_row = $subject_result->fetch_assoc();
    $subject_id = $subject_row['subject_id'];
} else {
    die("ไม่พบรหัสวิชาในระบบ กรุณาลองใหม่.");
}

// เพิ่มการบ้านใหม่เมื่อผู้ใช้ส่งฟอร์ม
$alert_message = ''; // กำหนดตัวแปร $alert_message ให้เป็นค่าว่างเริ่มต้น
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $assigned_date = $_POST['assigned_date'];
    $deadline = $_POST['deadline'];

    // ตรวจสอบไฟล์และจัดการอัปโหลดหลายไฟล์
    $file_paths = [];
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        foreach ($_FILES['files']['name'] as $key => $file_name) {
            $file_tmp = $_FILES['files']['tmp_name'][$key];

            // แปลงชื่อไฟล์ให้เป็น UTF-8
            $file_name_utf8 = iconv(mb_detect_encoding($file_name, mb_detect_order(), true), "UTF-8", $file_name);
            $file_path = 'uploads/' . $file_name_utf8;

            // ย้ายไฟล์ไปยังตำแหน่งที่ถูกต้อง
            if (move_uploaded_file($file_tmp, $file_path)) {
                $file_paths[] = $file_path; // เก็บเส้นทางไฟล์ที่อัปโหลดสำเร็จ
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: $file_name";
            }
        }
    }

    // แปลงเส้นทางไฟล์เป็น JSON เพื่อจัดเก็บในฐานข้อมูล
    $file_paths_json = json_encode($file_paths, JSON_UNESCAPED_UNICODE); // ใช้ JSON_UNESCAPED_UNICODE เพื่อไม่ให้เข้ารหัส Unicode

    // เพิ่มการบ้านใหม่ในฐานข้อมูล
    $insert_sql = "INSERT INTO tb_homework (subject_id, subject_pass, teacher_id, title, description, assigned_date, deadline, file_path) 
                   VALUES ('$subject_id', '$subject_pass', '$teacher_id', '$title', '$description', '$assigned_date', '$deadline', '$file_paths_json')";

    if ($mysqli->query($insert_sql) === TRUE) {
        // แสดงข้อความแจ้งเตือนเมื่อบันทึกสำเร็จและรีไดเรกต์ไปยังหน้าแสดงการบ้าน
        $alert_message = '
            <div class="alert alert-success" role="alert">
                บันทึกข้อมูลสำเร็จ
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "show_homework.php?subject_pass=' . htmlspecialchars($subject_pass) . '";
                }, 1000); // 1000 milliseconds = 1 second
            </script>
        ';
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มการบ้าน: " . $mysqli->error;
    }
}
ob_end_flush(); // ส่งเนื้อหาออกจากบัฟเฟอร์
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มการบ้าน</title>
    <style>
        .btn-danger {
            background-color: hotpink;
            border-color: black;
            color: black;
        }

        .btn-warning {
            background-color: yellow;
            border-color: black;
            color: black;
        }

        .btn-info {
            background-color: blue;
            border-color: black;
            color: white;
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <?php if (!empty($alert_message)) { echo $alert_message; } ?>
                <div class="x_title">
                    <h2>เพิ่มการบ้านในวิชา <?= htmlspecialchars($subject_pass); ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form id="add_homework_form" action="add_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">หัวข้อการบ้าน:</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">รายละเอียดการบ้าน:</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="assigned_date">วันที่สั่ง:</label>
                            <input type="datetime-local" name="assigned_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deadline">วันหมดเขต:</label>
                            <input type="datetime-local" name="deadline" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="files">ไฟล์การบ้าน:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" name="files[]" multiple class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div id="additional_homeworks"></div> <!-- สำหรับเพิ่มการบ้านหลายรายการ -->
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="button" id="add_more_homework" class="btn btn-primary">เพิ่มการบ้านอีกรายการ</button>
                                <button type="submit" name="submit" class="btn btn-success">บันทึกการบ้าน</button>
                                <button type="button" class="btn btn-danger" onclick="window.location.href='show_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>';">ยกเลิก</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

<script>
    document.getElementById('add_more_homework').addEventListener('click', function() {
        var additionalHomeworkHTML = `
        <div class="form-group">
            <label for="title">หัวข้อการบ้าน:</label>
            <input type="text" name="title[]" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">รายละเอียดการบ้าน:</label>
            <textarea name="description[]" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="assigned_date">วันที่สั่ง:</label>
            <input type="datetime-local" name="assigned_date[]" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="deadline">วันหมดเขต:</label>
            <input type="datetime-local" name="deadline[]" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="files">ไฟล์การบ้าน:</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" name="files[]" multiple class="form-control col-md-7 col-xs-12">
            </div>
        </div>`;
        document.getElementById('additional_homeworks').insertAdjacentHTML('beforeend', additionalHomeworkHTML);
    });
</script>

</html>
