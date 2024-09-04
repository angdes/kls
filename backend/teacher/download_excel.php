<?php
// เริ่มต้น session
session_start();
ob_start(); // เริ่มการบัฟเฟอร์เอาต์พุต

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินแล้วหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดาวน์โหลดไฟล์ Excel";
    exit();
}

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ตรวจสอบว่ามี subject_id ที่ถูกต้อง
if (isset($_GET['subject_id'])) {
    $subject_id = intval($_GET['subject_id']);

    // ดึงข้อมูลชื่อรายวิชาจากฐานข้อมูล
    $subject_sql = "SELECT subject_name FROM tb_subject WHERE subject_id = '$subject_id'";
    $subject_result = $mysqli->query($subject_sql);

    if ($subject_result === false || $subject_result->num_rows === 0) {
        die("ไม่พบรายวิชาหรือการดึงข้อมูลล้มเหลว: " . $mysqli->error);
    }

    // รับค่าชื่อวิชา
    $subject_name = $subject_result->fetch_assoc()['subject_name'];

    // ดึงข้อมูลนักเรียนและการบ้านของรายวิชาที่เลือก
    $sql = "SELECT 
                ss.member_id, 
                m.member_fullname,
                h.title AS homework_title, 
                sh.grade
            FROM tb_student_subject ss
            LEFT JOIN tb_member m ON ss.member_id = m.member_id
            LEFT JOIN tb_homework h ON ss.subject_id = h.subject_id
            LEFT JOIN tb_student_homework sh ON sh.member_id = ss.member_id AND sh.homework_id = h.homework_id
            WHERE ss.subject_id = '$subject_id'
            ORDER BY m.member_fullname, h.homework_id";
    $result = $mysqli->query($sql);

    if ($result === false) {
        die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
    }

    require_once '../../vendor/autoload.php'; // ดึงไลบรารี PHPExcel

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ตั้งชื่อหัวข้อของคอลัมน์
    $sheet->setCellValue('A1', 'ชื่อสมาชิก');
    $sheet->setCellValue('B1', 'ชื่อการบ้าน');
    $sheet->setCellValue('C1', 'คะแนน');
    $sheet->setCellValue('D1', 'คะแนนรวมทั้งหมด');

    // วน loop เพื่อใส่ข้อมูลและคำนวณคะแนนรวม
    $rowCount = 2;
    $currentMemberId = null;
    $totalScore = 0;
    while ($row = $result->fetch_assoc()) {
        if ($currentMemberId !== $row['member_id']) {
            if ($currentMemberId !== null) {
                // แสดงคะแนนรวมของนักเรียนก่อนหน้า
                $sheet->setCellValue('D' . ($rowCount - 1), $totalScore);
            }
            $currentMemberId = $row['member_id'];
            $totalScore = 0;
        }

        $sheet->setCellValue('A' . $rowCount, $row['member_fullname']);
        $sheet->setCellValue('B' . $rowCount, $row['homework_title']);
        $sheet->setCellValue('C' . $rowCount, $row['grade']);

        // เพิ่มคะแนนในคะแนนรวม
        $totalScore += $row['grade'];
        $rowCount++;
    }

    // แสดงคะแนนรวมของนักเรียนคนสุดท้าย
    if ($currentMemberId !== null) {
        $sheet->setCellValue('D' . ($rowCount - 1), $totalScore);
    }

    // ล้างบัฟเฟอร์ก่อนใช้ header
    ob_end_clean();

    // สร้างไฟล์ Excel โดยใช้ชื่อรายวิชา
    $filename = "รายงานภาระนักเรียนในรายวิชา_" . $subject_name . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');

    exit();
} else {
    echo "ไม่พบรหัสรายวิชา";
    exit();
}
?>
