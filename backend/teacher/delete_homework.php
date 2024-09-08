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
    // ลบข้อมูลใน tb_student_homework ก่อน เพื่อหลีกเลี่ยงการละเมิด foreign key constraint
    $delete_student_homework_sql = "DELETE FROM tb_student_homework WHERE homework_id = ?";
    $stmt = $mysqli->prepare($delete_student_homework_sql);
    $stmt->bind_param('i', $homework_id);
    if ($stmt->execute()) {
        // ลบข้อมูลใน tb_homework หลังจากลบข้อมูลที่เกี่ยวข้องเสร็จแล้ว
        $delete_homework_sql = "DELETE FROM tb_homework WHERE homework_id = ?";
        $stmt = $mysqli->prepare($delete_homework_sql);
        $stmt->bind_param('i', $homework_id);
        
        if ($stmt->execute()) {
            // แสดงข้อความแจ้งเตือนเมื่อการลบสำเร็จ
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'ลบข้อมูลสำเร็จ!',
                    text: 'การบ้านและข้อมูลที่เกี่ยวข้องถูกลบเรียบร้อยแล้ว',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    setTimeout(function(){
                        window.history.back(); // กลับไปยังหน้าก่อนหน้า
                    }, 1000); // 1000 milliseconds = 1 second
                });
            </script>";
        } else {
            // แสดงข้อความแจ้งเตือนเมื่อการลบล้มเหลว
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถลบข้อมูลการบ้านได้: " . $mysqli->error . "',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            </script>";
        }
    } else {
        // แสดงข้อความแจ้งเตือนเมื่อการลบล้มเหลวในตาราง tb_student_homework
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถลบข้อมูลการบ้านที่เกี่ยวข้องได้: " . $mysqli->error . "',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        </script>";
    }
    $stmt->close(); // ปิด statement
} else {
    // แสดงข้อความแจ้งเตือนเมื่อข้อมูลไม่ถูกต้อง
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: 'ข้อมูลไม่ถูกต้อง',
            text: 'กรุณาลองใหม่อีกครั้ง.',
            icon: 'warning',
            confirmButtonText: 'ตกลง'
        }).then(() => {
            window.history.back();
        });
    </script>";
}

$mysqli->close();
?>
