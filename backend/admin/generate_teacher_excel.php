<?php
// โหลด PhpSpreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ตั้งค่าหัวข้อของไฟล์ Excel (สำหรับกรอกข้อมูลครู)
$sheet->setCellValue('A1', 'ชื่อครู');
$sheet->setCellValue('B1', 'ชื่อผู้ใช้');
$sheet->setCellValue('C1', 'รหัสผ่าน');
$sheet->setCellValue('D1', 'เบอร์โทรศัพท์');

// ตั้งค่าหัวข้อตัวหนา
$sheet->getStyle('A1:D1')->getFont()->setBold(true);

// ตั้งชื่อไฟล์
$filename = "ไฟล์เพิ่มข้อมูลครู.xlsx";

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
