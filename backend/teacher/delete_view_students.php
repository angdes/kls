<?php
// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ตรวจสอบว่ามีการส่ง homework_id และ member_id มาหรือไม่
if (isset($_GET['homework_id']) && isset($_GET['member_id'])) {
    $homework_id = intval($_GET['homework_id']);
    $member_id = intval($_GET['member_id']);

    // ลบข้อมูลจาก tb_student_homework
    $sql_delete = "DELETE FROM tb_student_homework WHERE homework_id = $homework_id AND member_id = $member_id";
    
    if ($mysqli->query($sql_delete) === TRUE) {
        echo "<script>alert('ลบข้อมูลนักเรียนสำเร็จ'); window.location.href = 'view_students.php?homework_id=$homework_id';</script>";
    } else {
        echo "การลบข้อมูลล้มเหลว: " . $mysqli->error;
    }
} else {
    echo "ข้อมูลไม่ครบถ้วน";
}

$mysqli->close();
?>
