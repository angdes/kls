<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อทำการลบการบ้าน";
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

// ดึงข้อมูลการบ้านที่ต้องการลบ
$homework_id = $_GET['homework_id'];

// ลบการบ้านจากฐานข้อมูล
$sql = "DELETE FROM tb_homework WHERE homework_id = $homework_id AND teacher_id = $teacher_id";
if ($mysqli->query($sql) === TRUE) {
    echo $cls_conn->show_message('ลบการบ้านสำเร็จ');
    echo $cls_conn->goto_page(1,'show_homework.php');
} else {
    echo "การลบการบ้านล้มเหลว: " . $mysqli->error;
}

// ปิดการเชื่อมต่อฐานข้อมูล
$mysqli->close();
?>
