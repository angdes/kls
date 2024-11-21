<?php include('header.php'); ?>

<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-m:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-d {
        color: white;
        background-color: #808080;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-d:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }
</style>

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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="excel_file">อัปโหลดไฟล์ Excel<span class="required">:</span> </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="excel_file" name="excel_file" class="form-control col-md-7 col-xs-12" accept=".xlsx, .xls" required>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="upload" class="btn btn-m">อัปโหลดและบันทึก</button>
                                <button type="button" class="btn btn-d" onclick="window.location.href='show_member.php'">กลับไปหน้าแสดง</button>
                            </div>
                        </div>
                    </form>

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
                                    $member_year = $sheet->getCell("A" . $row)->getValue();
                                    $member_number = $sheet->getCell("B" . $row)->getValue();
                                    $member_gender = strtolower($sheet->getCell("C" . $row)->getValue()); // เปลี่ยนให้เป็นตัวพิมพ์เล็กทั้งหมด
                                    $member_fullname = $sheet->getCell("D" . $row)->getValue();
                                    $member_tel = $sheet->getCell("E" . $row)->getValue();
                                    $member_username = $sheet->getCell("F" . $row)->getValue();
                                    $member_password = $sheet->getCell("G" . $row)->getValue();
                                    $member_status = $sheet->getCell("H" . $row)->getValue();

                                    // แปลงเพศเป็นรูปแบบที่ใช้ในฐานข้อมูล (male หรือ female)
                                    $member_gender = ($member_gender == 'ชาย' || $member_gender == 'male') ? 'male' : 'female';

                                    // แปลงสถานะสมาชิกเป็นรูปแบบที่ใช้ในฐานข้อมูล (1 หรือ 0)
                                    $member_status = ($member_status == 'นักเรียนภาคปกติ' || $member_status == '1' ) ? '1' : '0';

                                    // ตรวจสอบข้อมูลก่อนบันทึก
                                    if (!empty($member_year) && !empty($member_number) && !empty($member_fullname) && !empty($member_tel) && !empty($member_username) && !empty($member_password)) {
                                        // บันทึกข้อมูลลงฐานข้อมูล
                                        $sql = "INSERT INTO tb_member (member_year, member_number, member_gender, member_fullname, member_tel, member_username, member_password, member_status) 
                                                VALUES ('$member_year', '$member_number', '$member_gender', '$member_fullname', '$member_tel', '$member_username', '$member_password', '$member_status')";

                                        if ($cls_conn->write_base($sql)) {
                                            $insertedCount++;
                                        } else {
                                            echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูลสมาชิก $member_fullname<br>";
                                        }
                                    } else {
                                        echo "ข้อมูลไม่ครบถ้วนสำหรับนักเรียน $member_fullname, ไม่สามารถบันทึกได้<br>";
                                        $skippedCount++;
                                    }
                                }

                                // แสดงผลลัพธ์การอัปโหลด
                                echo $cls_conn->show_message("บันทึกข้อมูลจาก Excel สำเร็จ: เพิ่ม $insertedCount รายการ และข้าม $skippedCount รายการที่มีอยู่แล้ว");
                                echo $cls_conn->goto_page(1, 'show_member.php');
                            } else {
                                echo $cls_conn->show_message('กรุณาอัปโหลดไฟล์ที่มีรูปแบบ Excel');
                            }
                        } else {
                            echo $cls_conn->show_message('กรุณาเลือกไฟล์ Excel เพื่ออัปโหลด');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
