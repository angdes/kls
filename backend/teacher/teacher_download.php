<?php
session_start();

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['teacher']) && !isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดาวน์โหลดไฟล์";
    exit();
}

// ตรวจสอบว่าได้รับพารามิเตอร์ 'file' หรือไม่
if (!isset($_GET['file'])) {
    die("ไม่ได้รับพารามิเตอร์ไฟล์ที่ต้องการดาวน์โหลด");
}

// ทำความสะอาดชื่อไฟล์และเข้ารหัสให้ถูกต้อง
$file = basename(rawurldecode($_GET['file'])); // แปลงชื่อไฟล์จาก URL และทำความสะอาดด้วย basename
$file_path = "../../backend/teacher/uploads/" . $file; // ปรับให้ตรงกับที่เก็บไฟล์ของนักเรียน

// ตรวจสอบว่าไฟล์มีอยู่จริงและสามารถอ่านได้หรือไม่
if (!file_exists($file_path) || !is_readable($file_path)) {
    die("ไม่พบไฟล์ที่ต้องการดาวน์โหลดหรือไม่สามารถเข้าถึงได้");
}

// ตรวจสอบนามสกุลไฟล์เพื่อความปลอดภัย
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt']; // กำหนดนามสกุลไฟล์ที่อนุญาต
$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

// ตรวจสอบว่าประเภทไฟล์อยู่ในรายการที่อนุญาต
if (!in_array($file_extension, $allowed_extensions)) {
    die("ประเภทไฟล์ไม่อนุญาตให้ดาวน์โหลด");
}

// ส่ง header สำหรับดาวน์โหลดไฟล์
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));

// อ่านไฟล์และส่งไปยัง output buffer
readfile($file_path);
exit();
?>
