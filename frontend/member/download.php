<?php
// ตรวจสอบว่ามีการกำหนดพารามิเตอร์ file หรือไม่
if (isset($_GET['file'])) {
    $file_path = '../../backend/teacher/uploads/' . basename($_GET['file']); // ใช้ basename เพื่อป้องกันการโจมตี Directory Traversal

    // ตรวจสอบว่าไฟล์มีอยู่จริง
    if (file_exists($file_path)) {
        // กำหนด Header เพื่อระบุว่าเป็นไฟล์ดาวน์โหลด
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        flush(); // Flush system output buffer
        readfile($file_path); // อ่านไฟล์และส่งไปยัง output buffer
        exit;
    } else {
        echo "ไฟล์ที่คุณต้องการดาวน์โหลดไม่มีอยู่ในระบบ.";
    }
} else {
    echo "ไม่พบไฟล์ที่ระบุ.";
}
?>
