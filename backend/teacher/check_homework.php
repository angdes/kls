<?php
include('header.php');

// Start output buffering
ob_start();

$alert_message = '';

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    $alert_message = '<div class="alert alert-danger">คุณต้องล็อกอินก่อนเพื่อดูการบ้าน</div>';
    ob_end_flush();
    exit();
}
$teacher_id = $_SESSION['user']['teacher_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    $alert_message = '<div class="alert alert-danger">การเชื่อมต่อล้มเหลว: ' . $mysqli->connect_error . '</div>';
    ob_end_flush();
    exit();
}

// รับค่า homework_id จาก URL
$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;

// ตรวจสอบว่า homework_id ถูกต้อง
if ($homework_id <= 0) {
    $alert_message = '<div class="alert alert-danger">ข้อมูลไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง.</div>';
    ob_end_flush();
    exit();
}

// ดึงข้อมูลการบ้านจากฐานข้อมูล
$homework_sql = "SELECT title, description, assigned_date, deadline, subject_id FROM tb_homework WHERE homework_id = $homework_id";
$homework_result = $mysqli->query($homework_sql);

if ($homework_result === false || $homework_result->num_rows === 0) {
    $alert_message = '<div class="alert alert-danger">ไม่พบข้อมูลการบ้านในระบบ.</div>';
    ob_end_flush();
    exit();
}

$homework = $homework_result->fetch_assoc();
$subject_id = $homework['subject_id'];
$assigned_date = $homework['assigned_date'];
$deadline = $homework['deadline'];

// ดึงข้อมูล subject_pass
$subject_sql = "SELECT subject_pass FROM tb_subject WHERE subject_id = $subject_id";
$subject_result = $mysqli->query($subject_sql);

if ($subject_result === false || $subject_result->num_rows === 0) {
    $alert_message = '<div class="alert alert-danger">ไม่พบข้อมูลวิชาในระบบ.</div>';
    ob_end_flush();
    exit();
}

$subject_row = $subject_result->fetch_assoc();
$subject_pass = $subject_row['subject_pass'];

// Pagination
$items_per_page = isset($_GET['items_per_page']) ? intval($_GET['items_per_page']) : 5; // จำนวนรายการต่อหน้า
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($current_page - 1) * $items_per_page;

// ดึงข้อมูลนักเรียนที่ลงทะเบียนในรายวิชา
$students_sql = "SELECT m.member_id, m.member_number, m.member_fullname, s.submission_time, s.file_path, s.grade, s.feedback, s.checked 
                 FROM tb_student_subject ss
                 JOIN tb_member m ON ss.member_id = m.member_id
                 LEFT JOIN tb_student_homework s ON m.member_id = s.member_id AND s.homework_id = $homework_id
                 WHERE ss.subject_id = $subject_id
                 LIMIT $offset, $items_per_page";
$students_result = $mysqli->query($students_sql);

// นับจำนวนนักเรียนทั้งหมดเพื่อคำนวณหน้าทั้งหมด
$total_students_sql = "SELECT COUNT(*) as total FROM tb_student_subject WHERE subject_id = $subject_id";
$total_students_result = $mysqli->query($total_students_sql);
$total_students_row = $total_students_result->fetch_assoc();
$total_students = $total_students_row['total'];

// คำนวณจำนวนหน้าทั้งหมด
$total_pages = ceil($total_students / $items_per_page);

// บันทึกหรือแก้ไขการตรวจงาน (ส่วนนี้ยังคงเดิม)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ...โค้ดการบันทึกผลการตรวจงานยังคงเดิม...
}

ob_end_flush();
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจงานการบ้าน</title>
    <style>
        /* ตั้งค่าพื้นฐาน */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
            /* เพื่อให้หักบรรทัดอัตโนมัติเมื่อพื้นที่ไม่เพียงพอ */
        }

        .form-row label {
            flex: 0 0 150px;
            margin-right: 10px;
            font-weight: bold;
            white-space: nowrap;
            /* ป้องกันไม่ให้ข้อความหักบรรทัด */
        }

        .form-row h4 {
            margin: 0;
            flex-grow: 1;
        }

        .btn {
            padding: 8px 10px;
            font-size: 12px;
            margin-right: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.5s, box-shadow 0.5s;
            border-radius: 5px;
            border: 2px solid transparent;
            cursor: pointer;
        }

        /* สไตล์ปุ่ม */
        .btn-m {
            background-color: #FF00FF;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        .btn-m:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.5);
        }

        .btn-d {
            background-color: #808080;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        .btn-d:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.5);
        }

        .x_panel {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .x_title h2 {
            color: black;
        }

        .form-section {
            padding: 10px 0;
        }

        .report-section1 {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 8px;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

        .input-group {
            position: relative;

            /* ให้เต็มความกว้างเมื่ออยู่บนมือถือ */
            margin: 10px 0;
            margin-right: 20px;
        }

        .input-group-text {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            color: #495057;
            background-color: transparent;
        }

        .custom-search-input {
            width: 100%;
            padding-left: 40px;
            padding-right: 10px;
            border-radius: 20px;
            border: 1px solid #ced4da;
            height: 40px;
            font-size: 16px;
        }

        .custom-search-input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .pagination-container {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-container button {
            padding: 5px 10px;
            color: #fff;
            background-color: #b856d6;
            border: none;
        }

        .pagination-container button.active {
            background-color: #79099c;
            color: white;
        }

        .pagination-container button:hover {
            background-color: #79099c;
        }

        .custom-select-wrapper {
            position: relative;
            display: inline-block;
            width: auto;
        }

        .custom-select-modern {
            border-radius: 20px;
            padding: 10px 15px;
            border: 2px solid #ddd;
            font-size: 14px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .custom-select-modern:hover {
            border-color: #007bff;
            background-color: #e9ecef;
        }

        .custom-select-modern:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }


        /* ทำให้ responsive โดยใช้ Media Queries */
        @media (max-width: 768px) {
            .form-row label {
                flex: 1 1 100%;
                /* ให้ label เต็มความกว้าง */
            }

            .btn {
                font-size: 14px;
                padding: 10px;
                margin: 5px 0;
                flex: 1 1 100%;
                /* ให้ปุ่มเต็มความกว้างในมือถือ */
            }

            .x_panel {
                padding: 15px;
                /* ลดพื้นที่ในแต่ละ panel */
            }

            .report-section1 {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <?php if (!empty($alert_message)) {
                    echo $alert_message;
                } ?>
                <div class="x_title">
                    <h2 class="section-title">ตรวจงานสำหรับหัวข้อ: <?= htmlspecialchars($homework['title']); ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="report-section1">
                        <p style="font-size: 15px; color: black;"><strong>รายละเอียดงาน:</strong> <?= htmlspecialchars($homework['description']); ?></p>
                        <p><strong>วันที่สั่ง:</strong> <?= htmlspecialchars($assigned_date); ?></p>
                        <p><strong>วันหมดเขต:</strong> <?= htmlspecialchars($deadline); ?></p>
                    </div>
                    <br>
                    <div class="x_title">
                        <div class="clearfix"></div>
                    </div>

                    <h2 class="section-title" style="color: black;">การส่งงานของนักเรียน</h2>
                    <div class="d-flex">
                        <!-- ฟิลด์ค้นหา -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="custom-search-input" id="search-input" placeholder="ค้นหารหัสนักเรียนหรือชื่อ" onkeyup="searchStudents()">
                        </div>

                        <!-- เลือกจำนวน Entries ต่อหน้า -->
                        <div class="custom-select-wrapper">
                            <select id="items_per_page" class="custom-select-modern" onchange="changeEntriesPerPage()">
                                <option value="5" <?= $items_per_page == 5 ? 'selected' : '' ?>>5 รายการต่อหน้า</option>
                                <option value="10" <?= $items_per_page == 10 ? 'selected' : '' ?>>10 รายการต่อหน้า</option>
                                <option value="20" <?= $items_per_page == 20 ? 'selected' : '' ?>>20 รายการต่อหน้า</option>
                            </select>
                        </div>
                    </div>



                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสนักเรียน</th>
                                <th>ชื่อนักเรียน</th>
                                <th>เวลาการส่ง</th>
                                <th>สถานะการตรวจ</th>
                                <th>คะแนน</th>
                                <th>ความคิดเห็น</th>
                                <th>ไฟล์งาน</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody id="student-rows">
                            <?php if ($students_result->num_rows > 0) {
                                while ($row = $students_result->fetch_assoc()) {
                                    // ตรวจสอบการส่งงานและเวลาการส่ง
                                    $submission_status_color = 'color: red;';
                                    $submission_status_text = 'ยังไม่ได้ส่งงาน';
                                    if (!empty($row['submission_time'])) {
                                        $submission_time = strtotime($row['submission_time']);
                                        $formatted_submission_time = date('d/m/Y H:i:s', $submission_time);
                                        $deadline_time = strtotime($deadline);

                                        if ($submission_time <= $deadline_time) {
                                            $submission_status_color = 'color: green;';
                                            $submission_status_text = '' . $formatted_submission_time . ')';
                                        } else {
                                            $submission_status_text = '' . $formatted_submission_time . ')';
                                        }
                                    }

                                    // ตรวจสอบสถานะการตรวจงาน
                                    $checked_status_color = $row['checked'] ? 'color: green;' : 'color: red;';
                                    $checked_status_text = $row['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ';
                            ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['member_number']); ?></td>
                                        <td><?= htmlspecialchars($row['member_fullname']); ?></td>
                                        <td style="<?= $submission_status_color; ?>"><?= $submission_status_text; ?></td>
                                        <td style="<?= $checked_status_color; ?>"><?= $checked_status_text; ?></td>
                                        <td>
                                            <form method="post" action="">
                                                <input type="text" name="grade" value="<?= htmlspecialchars($row['grade'] ?? ''); ?>" placeholder="ใส่คะแนน" style="width: 60px;">
                                        </td>
                                        <td>
                                            <textarea name="feedback" rows="2" placeholder="ใส่ความคิดเห็น"><?= htmlspecialchars($row['feedback'] ?? ''); ?></textarea>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['file_path'])) { ?>
                                                <a href="submission_details.php?homework_id=<?= htmlspecialchars($homework_id); ?>&member_id=<?= htmlspecialchars($row['member_id']); ?>" class="btn btn-m">ไฟล์งาน</a>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="checked" <?= $row['checked'] ? 'checked' : ''; ?>> ตรวจแล้ว
                                            <button type="submit" class="btn btn-m">บันทึก</button>
                                            <input type="hidden" name="member_id" value="<?= $row['member_id']; ?>">
                                            </form>

                                            <!-- ปุ่มลบ -->
                                            <form method="post" action="" style="display:inline;">
                                                <input type="hidden" name="member_id" value="<?= $row['member_id']; ?>">
                                                <input type="hidden" name="delete_submission" value="1">
                                                <button type="submit" class="btn btn-d" onclick="return confirm('คุณต้องการลบหรือไม่?')">ลบ</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php }
                            } else {
                                echo '<tr><td colspan="8">ไม่มีนักเรียนในรายวิชานี้</td></tr>';
                            } ?>
                        </tbody>
                    </table>
                </div>

                <center>
                    <div class="pagination-container">
                        <button onclick="prevPage()" class="btn btn-custom">หน้าก่อนหน้า</button>
                        <span id="pagination-info"></span> <!-- จุดที่จะแสดงข้อมูลเลขหน้า -->
                        <button onclick="nextPage()" class="btn btn-custom">หน้าถัดไป</button>
                    </div>
                </center>

                <div align="right">
                    <a href="show_homework.php?subject_pass=<?= urlencode($subject_pass) ?>"><button class="btn btn-d">ย้อนกลับ</button></a>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>
<script>
    let currentPage = 1;
    let entriesPerPage = parseInt(document.getElementById('items_per_page').value);

    function changeEntriesPerPage() {
        const itemsPerPage = document.getElementById('items_per_page').value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('items_per_page', itemsPerPage);
        currentUrl.searchParams.set('page', 1); // กลับไปหน้าแรก
        window.location.href = currentUrl;
    }

    function searchStudents() {
        const input = document.getElementById('search-input').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr'); // เลือกแถวของตารางใน tbody

        rows.forEach(row => {
            // ดึงค่า member_number และ member_fullname จาก td ตามลำดับ
            const memberNumber = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const memberFullname = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

            // ตรวจสอบว่ามีคำค้นหาตรงกับ member_number หรือ member_fullname หรือไม่
            if (memberNumber.includes(input) || memberFullname.includes(input)) {
                row.style.display = ''; // แสดงแถวที่ตรงกับคำค้นหา
            } else {
                row.style.display = 'none'; // ซ่อนแถวที่ไม่ตรงกับคำค้นหา
            }
        });
    }

    function showEntries() {
        const rows = document.querySelectorAll('#student-rows tr'); // เลือกแถวทั้งหมดใน tbody ที่มี id student-rows
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / entriesPerPage); // คำนวณจำนวนหน้าทั้งหมด

        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = startIndex + entriesPerPage;

        rows.forEach((row, index) => {
            row.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
        });

        updatePagination(totalPages); // อัปเดตเลขหน้าทุกครั้งเมื่อมีการแสดงรายการใหม่
    }


    function updatePagination(totalPages) {
        const paginationInfo = document.getElementById('pagination-info');
        paginationInfo.textContent = `หน้าที่ ${currentPage} จาก ${totalPages}`;
    }

    function nextPage() {
        const rows = document.querySelectorAll('#student-rows tr').length;
        const totalPages = Math.ceil(rows / entriesPerPage);

        if (currentPage < totalPages) {
            currentPage++;
            showEntries();
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            showEntries();
        }
    }

    // เริ่มต้นการแสดงผล
    window.onload = function() {
        showEntries();
    };
</script>