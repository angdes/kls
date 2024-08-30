<?php
session_start();

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อส่งงาน";
    exit();
}

// ดึงค่า student_id จากเซสชัน
$student_id = $_SESSION['user']['member_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ตรวจสอบว่ามีการส่ง subject_id มาหรือไม่
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;

if (!$subject_id) {
    echo "ไม่พบรหัสวิชา";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework Submission</title>

    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $homework_id = $_POST['homework_id'];
    
    // กำหนดโฟลเดอร์สำหรับอัปโหลดไฟล์
    $upload_dir = '../../backend/teacher/uploads/';

    // สร้างโฟลเดอร์ถ้าไม่มี
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_paths = [];

    // ตรวจสอบว่ามีไฟล์ที่อัปโหลดหรือไม่
    if (!empty($_FILES['homework_files']['name'][0])) {
        foreach ($_FILES['homework_files']['name'] as $key => $filename) {
            $tmp_name = $_FILES['homework_files']['tmp_name'][$key];
            $file_path = $upload_dir . basename($filename);

            // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
            if (move_uploaded_file($tmp_name, $file_path)) {
                $file_paths[] = $file_path;
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . htmlspecialchars($filename);
            }
        }
    }

    // แปลงเส้นทางไฟล์เป็น JSON เพื่อบันทึกลงฐานข้อมูล
    $file_paths_json = json_encode($file_paths, JSON_UNESCAPED_UNICODE);

    // บันทึกข้อมูลการส่งงาน
    $submission_time = date('Y-m-d H:i:s');
    $sql = "INSERT INTO tb_student_homework (homework_id, member_id, submission_time, file_path) 
            VALUES ('$homework_id', '$student_id', '$submission_time', '$file_paths_json')
            ON DUPLICATE KEY UPDATE submission_time = '$submission_time', file_path = '$file_paths_json', checked = 0, grade = NULL";

    if ($mysqli->query($sql) === TRUE) {
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "บันทึกข้อมูลสำเร็จ",
                        text: "ส่งงานสำเร็จ",
                        type: "success"
                    }, function() {
                        window.location = "show_homework_student.php?subject_id=' . $subject_id . '"; // หน้าที่ต้องการให้กระโดดไป
                    });
                }, 1000);
              </script>';
    } else {
        echo "เกิดข้อผิดพลาดในการส่งงาน: " . $mysqli->error;
    }

    $mysqli->close();
}
?>

</body>
</html>
