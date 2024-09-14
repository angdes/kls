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

    // ดึงข้อมูลการบ้านทั้งหมดในรายวิชา
    $homework_sql = "SELECT homework_id, title FROM tb_homework WHERE subject_id = '$subject_id' ORDER BY homework_id";
    $homework_result = $mysqli->query($homework_sql);

    if ($homework_result === false || $homework_result->num_rows === 0) {
        die("ไม่พบการบ้านในรายวิชานี้หรือการดึงข้อมูลล้มเหลว: " . $mysqli->error);
    }

    // สร้างอาร์เรย์เก็บข้อมูลการบ้าน
    $homeworks = [];
    while ($homework_row = $homework_result->fetch_assoc()) {
        $homeworks[$homework_row['homework_id']] = $homework_row['title'];
    }

    // ดึงข้อมูลนักเรียนและคะแนนของการบ้านในรายวิชาที่เลือก
    $sql = "SELECT 
                ss.member_id, 
                m.member_fullname,
                m.member_number,
                sh.homework_id,
                sh.grade
            FROM tb_student_subject ss
            LEFT JOIN tb_member m ON ss.member_id = m.member_id
            LEFT JOIN tb_student_homework sh ON sh.member_id = ss.member_id
            WHERE ss.subject_id = '$subject_id'
            ORDER BY m.member_fullname, sh.homework_id";
    $result = $mysqli->query($sql);

    if ($result === false) {
        die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
    }

    require_once '../../vendor/autoload.php'; // ดึงไลบรารี PHPExcel

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ตั้งชื่อหัวข้อของคอลัมน์
    $sheet->setCellValue('A1', 'รหัสนักเรียน');
    $sheet->setCellValue('B1', 'ชื่อสมาชิก');
    
    // ฟังก์ชันสำหรับแปลงตัวเลขเป็นตัวอักษรของคอลัมน์
    function getColumnLetter($columnNumber) {
        $columnNumber = $columnNumber - 1;
        $dividend = $columnNumber + 1;
        $columnName = '';
        while ($dividend > 0) {
            $modulo = ($dividend - 1) % 26;
            $columnName = chr(65 + $modulo) . $columnName;
            $dividend = floor(($dividend - $modulo) / 26);
        }
        return $columnName;
    }

    // สร้างหัวข้อคอลัมน์การบ้านทั้งหมดในแถวเดียว
    $columnIndex = 3; // เริ่มจากคอลัมน์ C
    foreach ($homeworks as $homework_title) {
        $sheet->setCellValue(getColumnLetter($columnIndex) . '1', 'ชื่อการบ้าน');
        $sheet->setCellValue(getColumnLetter($columnIndex) . '2', $homework_title);
        $columnIndex++;
    }

    // สร้างคอลัมน์สำหรับคะแนนรวม
    $sheet->setCellValue(getColumnLetter($columnIndex) . '1', 'คะแนนรวมทั้งหมด');

    // วน loop เพื่อใส่ข้อมูลนักเรียนและคะแนน
    $rowCount = 3; // เริ่มข้อมูลนักเรียนที่แถว 3
    $currentMemberId = null;
    $previousMemberId = null; // เก็บค่า member_id ก่อนหน้าเพื่อตรวจสอบนักเรียน
    $grades = []; // เก็บคะแนนของนักเรียนในแต่ละการบ้าน

    while ($row = $result->fetch_assoc()) {
        // ตรวจสอบว่าเป็นนักเรียนคนใหม่หรือไม่
        if ($currentMemberId !== $row['member_id']) {
            if ($currentMemberId !== null) {
                // ใส่คะแนนรวมของนักเรียนก่อนหน้า
                $totalScore = array_sum($grades);
                $sheet->setCellValue(getColumnLetter($columnIndex) . ($rowCount - 1), $totalScore);
            }

            // เริ่มใส่ข้อมูลของนักเรียนใหม่
            $currentMemberId = $row['member_id'];
            $sheet->setCellValue('A' . $rowCount, $row['member_number']);
            $sheet->setCellValue('B' . $rowCount, $row['member_fullname']);
            $grades = array_fill_keys(array_keys($homeworks), ''); // รีเซ็ตคะแนนของการบ้าน

            $rowCount++;
        }

        // ใส่คะแนนในคอลัมน์ที่เกี่ยวข้อง
        if (isset($homeworks[$row['homework_id']])) {
            $homeworkColumn = array_search($row['homework_id'], array_keys($homeworks)) + 3; // หาคอลัมน์ที่การบ้านอยู่
            $grades[$row['homework_id']] = $row['grade'];
            $sheet->setCellValue(getColumnLetter($homeworkColumn) . ($rowCount - 1), $row['grade']);
        }
    }

    // ใส่คะแนนรวมของนักเรียนคนสุดท้าย
    if ($currentMemberId !== null) {
        $totalScore = array_sum($grades);
        $sheet->setCellValue(getColumnLetter($columnIndex) . ($rowCount - 1), $totalScore);
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
