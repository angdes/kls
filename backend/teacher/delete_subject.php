<?php 
include('header.php'); 

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

if (isset($_GET['subject_id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $subject_id = intval($_GET['subject_id']);
    
    // ลบข้อมูลที่เกี่ยวข้องใน tb_student_subject ก่อน
    $delete_student_subject_sql = "DELETE FROM tb_student_subject WHERE subject_id = $subject_id";
    
    if ($mysqli->query($delete_student_subject_sql) === TRUE) {
        // ลบข้อมูลจาก tb_subject เมื่อข้อมูลที่อ้างอิงถูกลบ
        $delete_subject_sql = "DELETE FROM tb_subject WHERE subject_id = $subject_id";
        
        if ($mysqli->query($delete_subject_sql) === TRUE) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'ลบข้อมูลสำเร็จ!',
                    text: 'รายวิชาและข้อมูลที่เกี่ยวข้องถูกลบเรียบร้อยแล้ว',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    setTimeout(function(){
                        window.history.back(); // กลับไปหน้าก่อนหน้า
                    }, 1000); // 1000 milliseconds = 1 second
                });
            </script>";
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถลบข้อมูลวิชาได้: " . $mysqli->error . "',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            </script>";
        }
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถลบข้อมูลนักเรียนที่เกี่ยวข้องได้: " . $mysqli->error . "',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        </script>";
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$mysqli->close();
?>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>ลบข้อมูลวิชา</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>กำลังลบข้อมูลวิชา...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
