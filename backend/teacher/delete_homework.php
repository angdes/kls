<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อลบการบ้าน";
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า homework_id และ subject_pass จาก URL
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;
$subject_pass = isset($_GET['subject_pass']) ? $_GET['subject_pass'] : '';

// ตรวจสอบว่า homework_id ถูกต้อง
if ($homework_id > 0) {
    // ลบการบ้านจากฐานข้อมูล
    $delete_sql = "DELETE FROM tb_homework WHERE homework_id = '$homework_id'";
    if ($mysqli->query($delete_sql) === TRUE) {
        // แสดงข้อความแจ้งเตือนเมื่อการลบสำเร็จ
        $alert_message = '
        <div class="alert alert-success" role="alert">
            ลบการบ้านสำเร็จ
        </div>
        <script>
            setTimeout(function(){
                window.location.href = "show_homework.php?subject_pass=' . htmlspecialchars($subject_pass) . '";
            }, 1000); // 1000 milliseconds = 1 second
        </script>';
    } else {
        // แสดงข้อความแจ้งเตือนเมื่อการลบล้มเหลว
        $alert_message = '
        <div class="alert alert-danger" role="alert">
            การลบการบ้านล้มเหลว: ' . htmlspecialchars($mysqli->error) . '
        </div>
        <script>
            setTimeout(function(){
                window.history.back();
            }, 1000); // 1000 milliseconds = 1 seconds
        </script>';
    }
} else {
    // แสดงข้อความแจ้งเตือนเมื่อข้อมูลไม่ถูกต้อง
    $alert_message = '
    <div class="alert alert-warning" role="alert">
        ข้อมูลไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง.
    </div>
    <script>
        setTimeout(function(){
            window.history.back();
        }, 1000); // 1000 milliseconds = 1 seconds
    </script>';
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลบการบ้าน</title>
    <style>
        .alert {
            margin: 20px;
            padding: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-color: #ffeeba;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- แสดงข้อความแจ้งเตือน -->
        <?php if (!empty($alert_message)) {
            echo $alert_message;
        } ?>
    </div>
</body>

</html>

<?php include('footer.php'); ?>
