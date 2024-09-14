<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อแก้ไขการบ้าน";
    exit();
}

// ดึงค่า `homework_id` จาก URL
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับข้อมูลการบ้านที่ต้องการแก้ไขจากฐานข้อมูล
$homework_sql = "SELECT title, description, assigned_date, deadline FROM tb_homework WHERE homework_id = $homework_id";
$homework_result = $mysqli->query($homework_sql);

// ตรวจสอบว่ามีการบ้านหรือไม่
if ($homework_result->num_rows > 0) {
    $homework = $homework_result->fetch_assoc();
} else {
    die("ไม่พบการบ้านที่ต้องการแก้ไข.");
}

// ตัวแปรสำหรับข้อความแจ้งเตือน
$alert_message = '';

// เมื่อผู้ใช้ส่งฟอร์มแก้ไข
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $assigned_date = DateTime::createFromFormat('d/m/Y H:i', $_POST['assigned_date'])->format('Y-m-d H:i:s');
    $deadline = DateTime::createFromFormat('d/m/Y H:i', $_POST['deadline'])->format('Y-m-d H:i:s');

    // อัปเดตข้อมูลการบ้านในฐานข้อมูล
    $update_sql = "UPDATE tb_homework SET title = ?, description = ?, assigned_date = ?, deadline = ? WHERE homework_id = ?";
    $stmt = $mysqli->prepare($update_sql);
    $stmt->bind_param("ssssi", $title, $description, $assigned_date, $deadline, $homework_id);

    if ($stmt->execute()) {
        // ข้อความแจ้งเตือนเมื่อแก้ไขสำเร็จ
        $alert_message = '<div class="alert alert-success" role="alert">การแก้ไขการบ้านสำเร็จ</div>';
        // รีไดเรกต์ไปยังหน้าแสดงการบ้านหลังจาก 1 วินาที
        $alert_message .= '<script>setTimeout(function(){ window.location.href = "show_homework.php?subject_pass=' . htmlspecialchars($_GET['subject_pass']) . '"; }, 1000);</script>';
    } else {
        // ข้อความแจ้งเตือนเมื่อแก้ไขล้มเหลว
        $alert_message = '<div class="alert alert-danger" role="alert">เกิดข้อผิดพลาดในการแก้ไข: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

$mysqli->close();
?>

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
</style>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขการบ้าน</title>

    <!-- เพิ่ม Flatpickr CSS และ JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>แก้ไขการบ้าน</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <!-- แสดงข้อความแจ้งเตือน -->
                    <?php if (!empty($alert_message)) {
                        echo $alert_message;
                    } ?>

                    <form action="edit_homework.php?homework_id=<?= $homework_id ?>&subject_pass=<?= htmlspecialchars($_GET['subject_pass']); ?>" method="post">
                        <div class="form-group">
                            <label for="title">หัวข้อการบ้าน:</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($homework['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">รายละเอียดการบ้าน:</label>
                            <textarea name="description" class="form-control" required><?= htmlspecialchars($homework['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="assigned_date">วันที่สั่ง:</label>
                            <input type="text" name="assigned_date" id="assigned_date" class="form-control datetimepicker" value="<?= htmlspecialchars(date('d/m/Y H:i', strtotime($homework['assigned_date']))); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="deadline">วันหมดเขต:</label>
                            <input type="text" name="deadline" id="deadline" class="form-control datetimepicker" value="<?= htmlspecialchars(date('d/m/Y H:i', strtotime($homework['deadline']))); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-d">บันทึกการเปลี่ยนแปลง</button>
                        <button type="button" class="btn btn-m" onclick="window.location.href='show_homework.php?subject_pass=<?= htmlspecialchars($_GET['subject_pass']); ?>';">ยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flatpickr -->
    <script>
        flatpickr('.datetimepicker', {
            enableTime: true,
            dateFormat: "d/m/Y H:i",
            time_24hr: true,
        });
    </script>
</body>

</html>
