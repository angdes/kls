<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูการบ้าน";
    exit();
}

// ดึงค่า student_id จากเซสชัน
$student_id = $_SESSION['user']['member_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า subject_id จาก URL
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;

if (!$subject_id) {
    echo "ไม่พบรหัสวิชา";
    exit();
}


// ดึงข้อมูลการบ้านจากฐานข้อมูลตามรายวิชาที่เลือก รวมถึงไฟล์ที่อาจารย์อัปโหลด
// ดึงข้อมูลการบ้านจากฐานข้อมูลตามรายวิชาที่เลือก รวมถึงไฟล์ที่อาจารย์อัปโหลด
$sql = "SELECT h.homework_id, h.title, h.description, h.assigned_date, h.deadline, h.file_path AS teacher_file, 
               sh.submission_time, sh.checked, sh.grade 
        FROM tb_homework h
        LEFT JOIN tb_student_homework sh ON h.homework_id = sh.homework_id AND sh.member_id = '$student_id'
        WHERE h.subject_id = '$subject_id' 
        AND h.assigned_date <= NOW()"; // เพิ่มเงื่อนไขตรวจสอบให้แสดงเฉพาะการบ้านที่วันที่สั่งไม่เกินวันที่ปัจจุบัน";
$result = $mysqli->query($sql);


// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // จำนวนรายการต่อหน้า
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$countResult = $mysqli->query("SELECT COUNT(homework_id) AS id FROM tb_homework WHERE subject_id = '$subject_id'");
$homeworkCount = $countResult->fetch_assoc();
$total = $homeworkCount['id'];
$pages = ceil($total / $limit);
$prev = max($page - 1, 1);
$next = min($page + 1, $pages);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการการบ้านในวิชา</title>
    <style>
        .btn-custom {
            background-color: #6600FF;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 4px;
        }

        .btn-custom:hover {
            background-color: #66CCFF;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.5);
        }

        .report-section {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-row label {
            flex: 0 0 150px;
            margin-right: 10px;
            font-weight: bold;
        }

        .form-row h4 {
            margin: 0;
        }

        /* ปุ่มดูไฟล์งาน - สีฟ้า */
        .btn-blue {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            padding: 8px 10px;
            border: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-blue:hover {
            background-color: #0056b3;
        }

        /* ปุ่มยืนยันการส่ง - สีเขียว */
        .btn-green {
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            padding: 8px 10px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-green:hover {
            background-color: #218838;
        }

        /* ปุ่มยกเลิกการส่ง - สีส้ม */
        .btn-orange {
            background-color: #fd7e14;
            color: white;
            border: none;
            font-size: 16px;
            padding: 8px 10px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-orange:hover {
            background-color: #e66900;
        }

        /* ปุ่มส่งแล้ว - สีเทาอ่อน */
        .btn-disabled {
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            font-size: 16px;
            padding: 7px 10px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ปุ่มสรุปการส่งงาน - สีม่วง */
        .btn-purple {
            background-color: #6f42c1;
            color: white;
            border: none;
            font-size: 16px;
            padding: 8px 10px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-purple:hover {
            background-color: #5a30a0;
        }

        /* ขนาดไอคอน */
        button i {
            margin-right: 8px;
            font-size: 16px;
        }

        .search-container {
            width: 50%;
            margin-bottom: 20px;
        }

        #search-input {
            padding: 8px 30px 8px 15px;
            border: 1px solid #ddd;
            border-radius: 15px;
            font-size: 14px;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #888;
        }

        #items_per_page {
            padding: 5px;
            border-radius: 5px;
        }


        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                /* ปรับแต่ง label และ content ให้อยู่ในแนวตั้ง */
                align-items: flex-start;
            }

            .form-row label {
                flex-basis: auto;
                margin-bottom: 5px;
                /* ให้มีระยะห่างระหว่าง label และ input */
            }

            .form-row h4 {
                align-self: stretch;
                /* ให้ h4 กินพื้นที่เต็มความกว้าง */
                text-align: left;
                /* จัดข้อความให้ชิดซ้าย */
            }

            /* Adjust input fields to fill the width */
            input[type="text"],
            textarea {
                width: 100%;
                /* ให้ input และ textarea กินพื้นที่เต็มความกว้าง */
            }

            .btn {
                width: auto;
                /* ปรับปุ่มให้กว้างตามเนื้อหา */
                margin: 10px 0;
                /* ให้มีระยะห่างระหว่างปุ่ม */
            }

            .report-section {
                width: 100%;
                /* ให้ report-section กินพื้นที่เต็มความกว้าง */
                padding: 10px;
                /* ลดระยะห่างด้านในเพื่อความเหมาะสมกับมือถือ */
            }

            .search-container>div {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-container {
                width: 100%;

            }

            #search-input {
                font-size: 14px;
            }

            #items_per_page {
                margin-top: 8px;

                border-radius: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="right_col" role="main">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>รายการงานในวิชา</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <!-- ฟิลด์ค้นหา -->
                <div class="search-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <div style="flex: 1; display: flex; position: relative;">
                            <input type="text" id="search-input" placeholder="ค้นหางาน..." onkeyup="searchReportSection()" />
                            <span class="search-icon">&#128269;</span>
                        </div>

                        <!-- Dropdown สำหรับเลือกจำนวน Entries -->
                        <div style="flex-shrink: 0; margin-left: 20px;">
                            <label for="items_per_page">แสดง:</label>
                            <select id="items_per_page" name="items_per_page" onchange="changeEntriesPerPage()">
                                <option value="10">5</option>
                                <option value="20">10</option>
                            </select>
                            รายการต่อหน้า
                        </div>
                    </div>
                </div>



                <?php
                if ($result->num_rows > 0) {
                    $index = 1;
                    while ($row = $result->fetch_assoc()) {
                        $submission_status = $row['checked'] ? 'ตรวจแล้ว' : 'ยังไม่ตรวจ';
                        $submission_time = $row['submission_time'] ? htmlspecialchars($row['submission_time']) : '<span style="color: red">ยังไม่ได้ส่ง</span>';

                        // แยกไฟล์ที่อาจารย์อัปโหลด
                        $teacher_files = json_decode($row['teacher_file'], true);
                ?>
                        <div class="report-section">

                            <div class="form-row">
                                <label>หัวข้องาน:</label>
                                <h4><?= htmlspecialchars($row['title'] ?? ''); ?></h4>
                            </div>
                            <div class="form-row">
                                <label>รายละเอียดงาน:</label>
                                <h4><?= htmlspecialchars($row['description'] ?? ''); ?></h4>
                            </div>
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 10px;">
                                <label style="margin-right: 10px;">วันที่สั่ง:</label>
                                <h4 style="color: #28a745; margin-right: 20px;"><?= htmlspecialchars($row['assigned_date'] ?? ''); ?></h4>

                                <label style="margin-right: 10px;">วันหมดเขต:</label>
                                <h4 style="color: red; margin-right: 5px;"><?= htmlspecialchars($row['deadline'] ?? ''); ?></h4>
                            </div>

                            <div class="form-row">
                                <label>ไฟล์งานที่อาจารย์แนบ:</label>
                                <h4>
                                    <?php if (!empty($teacher_files)) { ?>
                                        <a href="teacher_file_summary.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>" class="btn-blue">
                                            <i class="fas fa-folder-open" style="margin-right: 5px;"></i>ดูไฟล์งาน
                                        </a>
                                    <?php } else { ?>
                                        <span style="color: gray">ไม่มีไฟล์</span>
                                    <?php } ?>
                                </h4>
                            </div>
                            <div class="form-row">
                                <label>เวลาที่ส่ง:</label>
                                <h4>
                                    <?php
                                    if ($row['submission_time']) {
                                        // เปรียบเทียบเวลาที่ส่งกับวันหมดเขต
                                        $submission_time = new DateTime($row['submission_time']);
                                        $deadline = new DateTime($row['deadline']);

                                        if ($submission_time > $deadline) {
                                            // ถ้าเวลาที่ส่งเกินกว่าวันหมดเขต แสดงเป็นส่งล่าช้า
                                            echo '<span style="color: red;">ส่งล่าช้า: ' . htmlspecialchars($row['submission_time']) . '</span>';
                                        } else {
                                            // ถ้าเวลาที่ส่งไม่เกินวันหมดเขต แสดงเป็นส่งตามกำหนด
                                            echo '<span style="color: green;">ส่งตามกำหนด: ' . htmlspecialchars($row['submission_time']) . '</span>';
                                        }
                                    } else {
                                        echo '<span style="color: red;">ยังไม่ได้ส่ง</span>';
                                    }
                                    ?>
                                </h4>
                            </div>

                            <div class="form-row">
                                <label>คะแนน:</label>
                                <h4><?= htmlspecialchars($row['grade'] ?? 'ยังไม่มีคะแนน'); ?></h4>
                            </div>
                            <div class="form-row">
                                <label>สถานะการตรวจ:</label>
                                <h4><?= $submission_status; ?></h4>
                            </div>
                            <div class="form-row">
                                <label>การส่งงาน: <label style="color: red;">(ไฟล์เอกสาร PDF , ไฟล์รูปภาพ JPG ขนาดไม่เกิน 5MB)</label></label>
                                <h4>
                                    <?php if (!$row['submission_time']) { ?>
                                        <form id="homework_form_<?= $row['homework_id']; ?>" action="submit_homework.php?subject_id=<?= $subject_id ?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="homework_id" value="<?= $row['homework_id']; ?>">

                                            <!-- ส่วนแสดงผลของไฟล์ที่เลือก -->
                                            <input type="file" id="homework_files_<?= $row['homework_id']; ?>" name="homework_files[]" multiple onchange="handleFileSelect(<?= $row['homework_id']; ?>)" required>
                                            <ul id="file_list_<?= $row['homework_id']; ?>"></ul>

                                            <br>
                                            <!-- แสดงขนาดไฟล์รวม -->
                                            <p>ขนาดไฟล์รวม: <span id="total_size_<?= $row['homework_id']; ?>">0 MB</span></p>

                                            <button type="button" class="btn-green" onclick="confirmSubmit(<?= $row['homework_id']; ?>)">
                                                <i class="fas fa-upload"></i> ยืนยันการส่ง
                                            </button>
                                            <button type="button" class="btn-orange" onclick="cancelSubmit(<?= $row['homework_id']; ?>)">
                                                <i class="fas fa-times"></i> ยกเลิก
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <button class="btn-disabled" disabled>
                                            <i class="fas fa-check"></i> ส่งแล้ว
                                        </button>
                                    <?php } ?>
                                </h4>
                            </div>

                            <div class="form-row">
                                <label>สรุปการส่งงาน:</label>
                                <h4>
                                    <?php if ($row['submission_time']) { ?>
                                        <a href="submission_details_member.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>&member_id=<?= $student_id; ?>" class="btn-purple">
                                            <i class="fas fa-file-alt" style="margin-right: 5px;"></i>สรุปการส่งงาน
                                        </a>
                                    <?php } else { ?>
                                        <span style="color: red">ยังไม่ได้ส่ง</span>
                                    <?php } ?>
                                </h4>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>ไม่มีการบ้านที่จะแสดง</p>';
                }


                $mysqli->close();
                ?>

            </div>
            <!-- Pagination -->
            <center>
                <div class="pagination-container">
                    <button onclick="prevPage()" class="btn-custom">หน้าก่อนหน้า</button>
                    <span id="pagination-info"></span>
                    <button onclick="nextPage()" class="btn-custom">หน้าถัดไป</button>
                </div>
            </center>
        </div>

        <div class="x_title">
            <div class="clearfix"></div>
        </div>
        <div align="right">
            <a href="show_student_subjects.php"><button class="btn-purple">ย้อนกลับ</button></a>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        // ฟังก์ชันจัดการไฟล์ที่เลือก
        // ฟังก์ชันจัดการไฟล์ที่เลือก
        function handleFileSelect(homework_id) {
            const input = document.getElementById('homework_files_' + homework_id);
            const fileList = document.getElementById('file_list_' + homework_id);
            const totalSizeElement = document.getElementById('total_size_' + homework_id);
            fileList.innerHTML = ''; // ล้างรายการไฟล์ก่อนหน้า
            let totalSize = 0;
            const allowedExtensions = ['.doc', '.docx', '.pdf', '.jpg'];

            Array.from(input.files).forEach((file, index) => {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

                // ตรวจสอบประเภทไฟล์
                if (!allowedExtensions.includes(fileExtension)) {
                    alert('สามารถอัปโหลดได้เฉพาะไฟล์เอกสาร .doc, .docx, .pdf และรูปภาพ .jpg เท่านั้น');
                    input.value = ''; // ล้างการเลือกไฟล์
                    return;
                }

                totalSize += parseFloat(fileSizeMB);

                // แสดงรายการไฟล์และปุ่มลบ
                const li = document.createElement('li');
                li.innerHTML = `${file.name} (${fileSizeMB} MB) <button type="button" onclick="removeFile(${index}, ${homework_id})">ลบ</button>`;
                fileList.appendChild(li);
            });

            // ตรวจสอบว่าขนาดไฟล์รวมเกิน 5MB หรือไม่
            if (totalSize > 5) {
                alert('ขนาดไฟล์รวมเกิน 5MB!');
                input.value = ''; // ล้างการเลือกไฟล์
                fileList.innerHTML = ''; // ล้างรายการไฟล์
                totalSizeElement.textContent = '0 MB'; // รีเซ็ตขนาดไฟล์รวม
            } else {
                totalSizeElement.textContent = `${totalSize.toFixed(2)} MB`; // แสดงขนาดไฟล์รวม
            }
        }


        // ฟังก์ชันลบไฟล์ที่เลือก
        function removeFile(index, homework_id) {
            const input = document.getElementById('homework_files_' + homework_id);
            const fileList = Array.from(input.files);

            fileList.splice(index, 1); // ลบไฟล์ตาม index ที่เลือก

            // สร้างรายการไฟล์ใหม่หลังจากลบไฟล์ที่เลือก
            const newFileList = new DataTransfer();
            fileList.forEach(file => {
                newFileList.items.add(file);
            });

            input.files = newFileList.files; // ตั้งค่ารายการไฟล์ใหม่
            handleFileSelect(homework_id); // เรียกฟังก์ชันเพื่ออัปเดตรายการไฟล์ใหม่
        }

        // ฟังก์ชันยืนยันการส่งงาน
        function confirmSubmit(homework_id) {
            const form = document.getElementById('homework_form_' + homework_id);
            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการส่งงานนี้?')) {
                form.submit();
            }
        }

        // ฟังก์ชันยกเลิกการส่งงาน
        function cancelSubmit(homework_id) {
            const form = document.getElementById('homework_form_' + homework_id);
            form.reset();
            document.getElementById('file_list_' + homework_id).innerHTML = ''; // ล้างรายการไฟล์
            document.getElementById('total_size_' + homework_id).textContent = '0 MB'; // รีเซ็ตขนาดไฟล์รวม
        }


        let currentPage = 1;
        let entriesPerPage = parseInt(document.getElementById('items_per_page').value);

        function changeEntriesPerPage() {
            entriesPerPage = parseInt(document.getElementById('items_per_page').value);
            currentPage = 1; // รีเซ็ตหน้าเมื่อเปลี่ยนจำนวนรายการต่อหน้า
            showEntries();
        }

        function showEntries() {
            const reportSections = document.querySelectorAll('.report-section');
            const totalEntries = reportSections.length;
            const totalPages = Math.ceil(totalEntries / entriesPerPage);

            const startIndex = (currentPage - 1) * entriesPerPage;
            const endIndex = startIndex + entriesPerPage;

            reportSections.forEach((section, index) => {
                section.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
            });

            updatePagination(totalPages);
        }

        function updatePagination(totalPages) {
            const paginationInfo = document.getElementById('pagination-info');
            paginationInfo.textContent = `หน้าที่ ${currentPage} จาก ${totalPages}`;
        }

        function nextPage() {
            const totalEntries = document.querySelectorAll('.report-section').length;
            const totalPages = Math.ceil(totalEntries / entriesPerPage);

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

        function searchReportSection() {
            const input = document.getElementById('search-input').value.toLowerCase();
            const reportSections = document.querySelectorAll('.report-section');

            reportSections.forEach(section => {
                const titleElement = section.querySelector('div.form-row h4:nth-of-type(1)');
                const descriptionElement = section.querySelector('div.form-row h4:nth-of-type(2)');

                const titleText = titleElement ? titleElement.textContent.toLowerCase() : '';
                const descriptionText = descriptionElement ? descriptionElement.textContent.toLowerCase() : '';

                if (titleText.includes(input) || descriptionText.includes(input)) {
                    section.style.display = '';
                } else {
                    section.style.display = 'none';
                }
            });
        }

        // เริ่มต้นการแสดงผล
        window.onload = function() {
            showEntries();
        };
    </script>
</body>

</html>