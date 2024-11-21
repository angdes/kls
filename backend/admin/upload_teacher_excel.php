<?php include('header.php'); ?>
<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
        border: 2px solid #E0E0E0;
        /* ขอบสีเทาอ่อน */
        border-radius: 5px;
        /* ทำให้ขอบมนเล็กน้อย */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* เงาเบาบางใต้ปุ่ม */
        transition: box-shadow 0.3s ease;
        /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
    }

    .btn-m:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        /* เงาชัดเจนขึ้นเมื่อ hover */
    }

    .btn-d {
        color: white;
        background-color: #808080;
        border: 2px solid #E0E0E0;
        /* ขอบสีเทาอ่อน */
        border-radius: 5px;
        /* ทำให้ขอบมนเล็กน้อย */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* เงาเบาบางใต้ปุ่ม */
        transition: box-shadow 0.3s ease;
        /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
    }

    .btn-d:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        /* เงาชัดเจนขึ้นเมื่อ hover */
    }
</style>
<?php
require '../../vendor/autoload.php'; // โหลดไลบรารี PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['upload'])) {
    // ตรวจสอบการอัปโหลดไฟล์ Excel
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['size'] > 0) {
        $fileType = $_FILES['excel_file']['type'];
        $allowedTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

        if (in_array($fileType, $allowedTypes)) {
            // โหลดไฟล์ Excel
            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            // นับจำนวนการเพิ่มและการข้าม
            $insertedCount = 0;
            $skippedCount = 0;

            for ($row = 2; $row <= $highestRow; $row++) { // เริ่มจากแถวที่ 2 เพื่อข้ามหัวตาราง
                $teacher_fullname = $sheet->getCell("A" . $row)->getValue();
                $teacher_username = $sheet->getCell("B" . $row)->getValue();
                $teacher_password = $sheet->getCell("C" . $row)->getValue();
                $teacher_tel = $sheet->getCell("D" . $row)->getValue();

                // ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่ (ตรวจทั้ง teacher_fullname และ teacher_username)
                $check_sql = "SELECT * FROM tb_teacher WHERE teacher_username = '$teacher_username' OR teacher_fullname = '$teacher_fullname'";
                $result = $cls_conn->select_base($check_sql);

                if (mysqli_num_rows($result) > 0) {
                    // ถ้ามีข้อมูลแล้วให้ข้าม
                    $skippedCount++;
                } else {
                    // ถ้ายังไม่มีข้อมูล ให้แทรกข้อมูลใหม่
                    $insert_sql = "INSERT INTO tb_teacher (teacher_fullname, teacher_username, teacher_password, teacher_tel)";
                    $insert_sql .= " VALUES ('$teacher_fullname', IF('$teacher_username' != '', '$teacher_username', '-'), IF('$teacher_password' != '', '$teacher_password', '-'), IF('$teacher_tel' != '', '$teacher_tel', '-'))";
                    $cls_conn->write_base($insert_sql);
                    $insertedCount++;
                }
            }

            // แสดงผลลัพธ์การอัปโหลด
            echo $cls_conn->show_message("บันทึกข้อมูลจาก Excel สำเร็จ: เพิ่ม $insertedCount รายการ และข้าม $skippedCount รายการที่มีอยู่แล้ว");
            echo $cls_conn->goto_page(1, 'show_teacher.php');
        } else {
            echo $cls_conn->show_message('กรุณาอัปโหลดไฟล์ที่มีรูปแบบ Excel');
        }
    } else {
        echo $cls_conn->show_message('กรุณาเลือกไฟล์ Excel เพื่ออัปโหลด');
    }
}
?>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>อัปโหลดข้อมูลจาก Excel</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <!-- ฟอร์มสำหรับอัปโหลดไฟล์ Excel -->
                    <form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="excel_file">อัปโหลดไฟล์ Excel<span class="required">:</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="excel_file" name="excel_file" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="upload" class="btn btn-m">อัปโหลดและบันทึก</button>
                                <button type="button" class="btn btn-d" onclick="window.location.href='show_teacher.php'">กลับไปหน้าแสดง</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
