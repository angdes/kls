<?php include('header.php'); ?>

<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
    }

    .btn-d {
        color: white;
        background-color: #BA55D3;
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
                                <input type="file" id="excel_file" name="excel_file" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="upload" class="btn btn-m">อัปโหลดและบันทึก</button>
                                <button type="button" class="btn btn-d" onclick="window.location.href='insert_member.php'">กลับไปหน้าหลัก</button>
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
                                $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
                                $sheet = $spreadsheet->getActiveSheet();
                                $highestRow = $sheet->getHighestRow();

                                for ($row = 2; $row <= $highestRow; $row++) { // เริ่มจากแถวที่ 2 เพื่อข้ามหัวตาราง
                                    $member_number = $sheet->getCell("A" . $row)->getValue();
                                    $member_fullname = $sheet->getCell("B" . $row)->getValue();
                                    $member_address = $sheet->getCell("C" . $row)->getValue();
                                    $member_tel = $sheet->getCell("D" . $row)->getValue();
                                    $member_email = $sheet->getCell("E" . $row)->getValue();
                                    $member_username = $sheet->getCell("F" . $row)->getValue();
                                    $member_password = $sheet->getCell("G" . $row)->getValue();
                                    $member_status = $sheet->getCell("H" . $row)->getValue();
                                    $member_datetime = $sheet->getCell("I" . $row)->getValue();

                                    // ตรวจสอบว่าข้อมูลมีในฐานข้อมูลหรือไม่
                                    $check_sql = "SELECT * FROM tb_member WHERE member_number = '$member_number'";
                                    $result = $cls_conn->select_base($check_sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        // อัปเดตข้อมูลที่มีอยู่แล้ว
                                        $update_sql = "UPDATE tb_member SET 
                                                        member_fullname = IF('$member_fullname' != '', '$member_fullname', '-'),
                                                        member_address = IF('$member_address' != '', '$member_address', '-'),
                                                        member_tel = IF('$member_tel' != '', '$member_tel', '-'),
                                                        member_email = IF('$member_email' != '', '$member_email', '-'),
                                                        member_username = IF('$member_username' != '', '$member_username', '-'),
                                                        member_password = IF('$member_password' != '', '$member_password', '-'),
                                                        member_status = IF('$member_status' != '', '$member_status', '-'),
                                                        member_datetime = IF('$member_datetime' != '', '$member_datetime', '-')
                                                      WHERE member_number = '$member_number'";
                                        $cls_conn->write_base($update_sql);
                                    } else {
                                        // แทรกข้อมูลใหม่
                                        $insert_sql = "INSERT INTO tb_member (member_number, member_fullname, member_address, member_tel, member_email, member_username, member_password, member_status, member_datetime)";
                                        $insert_sql .= " VALUES ('$member_number', IF('$member_fullname' != '', '$member_fullname', '-'), IF('$member_address' != '', '$member_address', '-'), IF('$member_tel' != '', '$member_tel', '-'), IF('$member_email' != '', '$member_email', '-'), IF('$member_username' != '', '$member_username', '-'), IF('$member_password' != '', '$member_password', '-'), IF('$member_status' != '', '$member_status', '-'), IF('$member_datetime' != '', '$member_datetime', '-'))";
                                        $cls_conn->write_base($insert_sql);
                                    }
                                }
                                echo $cls_conn->show_message('บันทึกข้อมูลจาก Excel สำเร็จ');
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
