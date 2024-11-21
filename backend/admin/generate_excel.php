<?php
// โหลด PhpSpreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ตั้งค่าหัวข้อของไฟล์ Excel ตามลำดับในฟอร์ม
$sheet->setCellValue('A1', 'ปีการศึกษา');
$sheet->setCellValue('B1', 'รหัสประจำตัวนักเรียน');
$sheet->setCellValue('C1', 'เลือกเพศ(เช่น ชาย,หญิง)');
$sheet->setCellValue('D1', 'ชื่อ-สกุลนักเรียน');
$sheet->setCellValue('E1', 'เบอร์โทรศัพท์ต้องให้ครบ10ตัว');
$sheet->setCellValue('F1', 'บัญชีผู้ใช้');
$sheet->setCellValue('G1', 'รหัสผ่าน');
$sheet->setCellValue('H1', 'สถานะสมาชิก(เช่น ภาคปกติ=1 ภาคย้ายเข้า=0)');

// ตั้งค่าหัวข้อตัวหนา
$sheet->getStyle('A1:H1')->getFont()->setBold(true);

// ตั้งชื่อไฟล์
$filename = "ไฟล์เพิ่มข้อมูลนักเรียน.xlsx";

// สร้างไฟล์ Excel
$writer = new Xlsx($spreadsheet);

// กำหนด header เพื่อให้ดาวน์โหลดไฟล์
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// เขียนไฟล์ไปยัง output stream
$writer->save('php://output');
exit();
?>
