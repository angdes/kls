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
    // ดึงค่าจากฟอร์ม
    $titles = is_array($_POST['title']) ? $_POST['title'] : [$_POST['title']]; // แปลงให้เป็น array หากเป็นรายการเดียว
    $descriptions = is_array($_POST['description']) ? $_POST['description'] : [$_POST['description']];
    $assigned_dates = is_array($_POST['assigned_date']) ? $_POST['assigned_date'] : [$_POST['assigned_date']];
    $deadlines = is_array($_POST['deadline']) ? $_POST['deadline'] : [$_POST['deadline']];

    // ตรวจสอบว่ารูปแบบข้อมูลถูกต้อง
    if (is_array($titles) && is_array($descriptions) && is_array($assigned_dates) && is_array($deadlines)) {
        foreach ($titles as $index => $title) {
            $description = $descriptions[$index];
            $assigned_date_str = $assigned_dates[$index];
            $deadline_str = $deadlines[$index];

            // แปลงวันที่จากรูปแบบไทยเป็นรูปแบบที่ฐานข้อมูลรองรับ (Y-m-d H:i:s)
            $assigned_date_obj = DateTime::createFromFormat('d/m/Y H:i', $assigned_date_str);
            $deadline_obj = DateTime::createFromFormat('d/m/Y H:i', $deadline_str);

            if ($assigned_date_obj && $deadline_obj) {
                $assigned_date = $assigned_date_obj->format('Y-m-d H:i:s');
                $deadline = $deadline_obj->format('Y-m-d H:i:s');
            } else {
                die("รูปแบบวันที่ไม่ถูกต้อง กรุณาตรวจสอบรูปแบบวันที่ให้เป็น วัน/เดือน/ปี ชั่วโมง:นาที");
            }

            // ตรวจสอบไฟล์และจัดการอัปโหลดหลายไฟล์
            $file_paths = [];
            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'][0])) {
                foreach ($_FILES['files']['name'] as $key => $file_name) {
                    $file_tmp = $_FILES['files']['tmp_name'][$key]; // ใช้ $key แทน $index

                    if (is_uploaded_file($file_tmp)) {
                        $file_name_utf8 = iconv(mb_detect_encoding($file_name, mb_detect_order(), true), "UTF-8", $file_name);
                        $file_path = 'uploads/' . $file_name_utf8;

                        if (move_uploaded_file($file_tmp, $file_path)) {
                            $file_paths[] = $file_path;
                        } else {
                            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: $file_name";
                        }
                    }
                }
            }


            // แปลงเส้นทางไฟล์เป็น JSON เพื่อจัดเก็บในฐานข้อมูล
            $file_paths_json = json_encode($file_paths, JSON_UNESCAPED_UNICODE);

            // ตรวจสอบว่ามีหัวข้อและรายละเอียดการบ้านที่ซ้ำกันในฐานข้อมูลหรือไม่
            $check_duplicate_sql = "SELECT * FROM tb_homework WHERE subject_id = '$subject_id' AND title = '$title' AND description = '$description' LIMIT 1";
            $duplicate_result = $mysqli->query($check_duplicate_sql);

            if ($duplicate_result->num_rows > 0) {
                // ถ้ามีข้อมูลซ้ำให้แสดงข้อความแจ้งเตือน
                $alert_message = '
                    <div class="alert alert-danger" role="alert">
                        มีการบ้านที่มีหัวข้อและรายละเอียดซ้ำกันอยู่แล้วในระบบ กรุณาตรวจสอบข้อมูลอีกครั้ง
                    </div>
                ';
            } else {
                // ถ้าไม่มีข้อมูลซ้ำ ให้ทำการเพิ่มการบ้านใหม่ในฐานข้อมูล
                $insert_sql = "INSERT INTO tb_homework (subject_id, subject_pass, teacher_id, title, description, assigned_date, deadline, file_path) 
                               VALUES ('$subject_id', '$subject_pass', '$teacher_id', '$title', '$description', '$assigned_date', '$deadline', '$file_paths_json')";

                if ($mysqli->query($insert_sql) === TRUE) {
                    // แสดงข้อความแจ้งเตือนเมื่อบันทึกสำเร็จ
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
        }
    } else {
        die("ข้อมูลที่ส่งมาไม่ถูกต้อง");
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
        .btn-m {
            color: white;
            background-color: #FF00FF;
            border: 2px solid #E0E0E0;
            /* ขอบสีเทาอ่อน */
            border-radius: 5px;
            /* ทำให้ขอบมนเล็กน้อย */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* เงาเบาบางใต้ปุ่ม */
            transition: box-shadow 0.3s ease;
            /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
        }

        .btn-m:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
            /* เงาชัดเจนขึ้นเมื่อ hover */
        }

        .btn-d {
            color: white;
            background-color: #808080;
            border: 2px solid #E0E0E0;
            /* ขอบสีเทาอ่อน */
            border-radius: 5px;
            /* ทำให้ขอบมนเล็กน้อย */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* เงาเบาบางใต้ปุ่ม */
            transition: box-shadow 0.3s ease;
            /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
        }

        .btn-d:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
            /* เงาชัดเจนขึ้นเมื่อ hover */
        }

        /* ปรับขนาดฟิลด์วันที่ให้เท่ากัน */
        input[type="text"] {
            width: 100%;
            max-width: 400px;
        }

        .form-group-file {
            margin-bottom: 35px;
        }

        /* ปรับขนาด Flatpickr */
        .flatpickr-calendar {
            width: 300px !important;
        }

        .flatpickr-day {
            height: 30px !important;
            width: 40px !important;
        }

        .homework-set {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>

    <!-- เพิ่ม Flatpickr CSS และ JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <?php if (!empty($alert_message)) {
                    echo $alert_message;
                } ?>
                <div class="x_title">
                    <h2 style="color: black;"><b>เพิ่มข้อมูลงานในรหัสวิชา <?= htmlspecialchars($subject_pass); ?></b></h2>
                    <div class="clearfix"></div>

                </div>

                <div class="x_content">
                    <form id="add_homework_form" action="add_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">หัวข้องาน:</label>
                            <input type="text" name="title[]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">รายละเอียดงาน:</label>
                            <textarea name="description[]" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="assigned_date">วันที่สั่ง:</label>
                            <input type="text" name="assigned_date[]" id="assigned_date" class="form-control datetimepicker" required placeholder="วัน/เดือน/ปี ชั่วโมง:นาที">
                        </div>
                        <div class="form-group">
                            <label for="deadline">วันหมดเขต:</label>
                            <input type="text" name="deadline[]" id="deadline" class="form-control datetimepicker" required placeholder="วัน/เดือน/ปี ชั่วโมง:นาที">
                        </div>
                        <div class="form-group form-group-file">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="files">ไฟล์งาน:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" name="files[]" multiple class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                        <div id="additional_homeworks"></div> <!-- สำหรับเพิ่มการบ้านหลายรายการ -->
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="button" id="add_more_homework" class="btn btn-d">เพิ่มงานอีกรายการ</button>
                                <button type="submit" name="submit" class="btn btn-d">บันทึกงาน</button>
                                <button type="button" class="btn btn-m" onclick="window.location.href='show_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>';">ยกเลิก</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

<script>
    document.getElementById('add_homework_form').addEventListener('submit', function(event) {
        var assignedDateElements = document.querySelectorAll('input[name="assigned_date[]"]');
        var deadlineElements = document.querySelectorAll('input[name="deadline[]"]');

        for (let i = 0; i < assignedDateElements.length; i++) {
            var assignedDate = flatpickr.parseDate(assignedDateElements[i].value, "d/m/Y H:i");
            var deadline = flatpickr.parseDate(deadlineElements[i].value, "d/m/Y H:i");

            if (assignedDate && deadline && deadline < assignedDate) {
                alert('วันหมดเขตต้องมากกว่าวันที่สั่งสำหรับการบ้านที่ ' + (i + 1));
                event.preventDefault();
                return;
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // ใช้ Flatpickr กับฟิลด์อินพุตวันที่
        flatpickr('.datetimepicker', {
            enableTime: true,
            dateFormat: "d/m/Y H:i",
            time_24hr: true,
            position: "above"
        });

        document.getElementById('add_more_homework').addEventListener('click', function() {
            var additionalHomeworkHTML = `
        <div class="homework-set">
            <hr style="border-top: 2px dashed #BA55D3;">
            <div class="form-group">
                <label for="title">หัวข้องาน:</label>
                <input type="text" name="title[]" class="form-control" required style="margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <label for="description">รายละเอียดงาน:</label>
                <textarea name="description[]" class="form-control" required style="margin-bottom: 10px;"></textarea>
            </div>
            <div class="form-group">
                <label for="assigned_date">วันที่สั่ง:</label>
                <input type="text" name="assigned_date[]" class="form-control datetimepicker" required placeholder="วัน/เดือน/ปี ชั่วโมง:นาที" style="margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <label for="deadline">วันหมดเขต:</label>
                <input type="text" name="deadline[]" class="form-control datetimepicker" required placeholder="วัน/เดือน/ปี ชั่วโมง:นาที" style="margin-bottom: 10px;">
            </div>
            <div class="form-group form-group-file">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="files">ไฟล์การบ้าน:</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="files[]" multiple class="form-control col-md-7 col-xs-12" style="margin-bottom: 20px;" accept=".doc,.docx,.pdf,.jpg">
                </div>
            </div>
        </div>`;
            document.getElementById('additional_homeworks').insertAdjacentHTML('beforeend', additionalHomeworkHTML);

            flatpickr('.datetimepicker', {
                enableTime: true,
                dateFormat: "d/m/Y H:i",
                time_24hr: true,
                position: "above"
            });
        });

        document.getElementById('add_homework_form').addEventListener('submit', function(event) {
            const allowedExtensions = ['.doc', '.docx', '.pdf', '.jpg'];
            const filesInput = document.querySelectorAll('input[type="file"]');

            for (let fileInput of filesInput) {
                for (let file of fileInput.files) {
                    const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
                    if (!allowedExtensions.includes(fileExtension)) {
                        alert('สามารถอัปโหลดได้เฉพาะไฟล์ .doc, .docx, .pdf และ .jpg เท่านั้น');
                        event.preventDefault();
                        return;
                    }
                }
            }
        });
    });


    document.getElementById('add_homework_form').addEventListener('submit', function(event) {
        var assignedDate = document.getElementById('assigned_date').value;
        var deadline = document.getElementById('deadline').value;

        if (assignedDate === "" || deadline === "") {
            alert('กรุณากรอกวันที่สั่งและวันหมดเขตให้ครบถ้วน');
            event.preventDefault();
        }
    });
</script>

</html>