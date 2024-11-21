<?php include('header.php'); ?>

<?php
// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลของครูที่ล็อกอินอยู่
$teacher_id = $_SESSION['user']['teacher_id'];

// ดึงข้อมูลรายวิชาที่สอนโดยครูที่ล็อกอินอยู่จากฐานข้อมูล
$sql = "SELECT subject_id, subject_pass, subject_name, subject_detail, subject_cover 
        FROM tb_subject 
        WHERE teacher_id = '$teacher_id'";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงข้อมูลรายวิชา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            font-size: 24px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin: 0px;
            width: 100%;
            padding: 30px;
            max-width: 1000px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 10px;
            width: calc(50% - 20px);
            display: flex;
            flex-direction: row;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s;
            position: relative;
            /* เพิ่มเพื่อรองรับไอคอน Excel */
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-image {
            flex: 1;
            background-color: #eaeaea;
            border-radius: 8px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-content {
            flex: 2;
            padding-left: 15px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #C44AFD;
        }

        .card-description {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .card-stats {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .text-green {
            color: green;
        }

        .text-red {
            color: red;
        }

        .text-work {
            color: black;
        }

        .text-stus {
            color: magenta;
        }

        .colorfont {
            color: black;
            font-size: 22px;
        }

        .download-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
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

        .summary-button {
            position: absolute;
            bottom: 7px;
            right: 10px;
            background-color: #EE82EE;
            /* หรือ #D63384 */
            color: white;
            border: none;
            border-radius: 20px;
            padding: 7px 10px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .summary-button:hover {
            background-color: #A32CCD;
            /* เฉดสีเข้มขึ้นเล็กน้อย */
        }

        .summary-button:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* สำหรับมือถือขนาดเล็กกว่า 768px */
        @media (max-width: 768px) {

            /* การ์ดเต็มหน้าจอและปรับขนาดปุ่ม */
            .card {
                flex: 1 1 100%;
                margin: 8px 0;
            }

            .card-container {
                padding: 8px;
            }

            /* ปรับขนาดปุ่มสรุปการส่งงาน */
            .summary-button {
                bottom: 10px;
                right: 10px;
                padding: 6px 10px;
                /* ลดขนาดปุ่ม */
                font-size: 10px;
                /* ลดขนาดฟอนต์ในปุ่ม */
            }

            /* ปรับขนาดตัวเลือก Entries */
            .custom-select-modern {
                padding: 6px 8px;
                /* ลด padding ใน dropdown */
                font-size: 12px;
                /* ลดขนาดฟอนต์ */
            }

            /* ช่องค้นหา */
            .custom-search-input {
                height: 36px;
                font-size: 12px;
                /* ลดขนาดฟอนต์ในช่องค้นหา */
                padding-left: 35px;
                /* ลดขนาด padding ของช่องค้นหา */
            }

            /* ปรับขนาดของ Pagination */
            .pagination-container button {
                padding: 6px 8px;
                font-size: 12px;
                /* ลดขนาดฟอนต์ใน Pagination */
            }

            /* ขนาดตัวอักษรในการ์ด */
            .card-title {
                font-size: 14px;
                /* ลดขนาดหัวข้อ */
            }

            .card-description,
            .card-pass,
            .card-stats {
                font-size: 12px;
                /* ลดขนาดฟอนต์ */
            }
        }

        /* สำหรับมือถือขนาดเล็กกว่า 576px */
        @media (max-width: 576px) {
            .card {
                margin: 5px 0;
            }

            .summary-button {
                padding: 4px 8px;
                /* ขนาดปุ่มเล็กลง */
                font-size: 9px;
                /* ลดขนาดฟอนต์ในปุ่มให้เล็กลงอีก */
            }

            .custom-select-modern {
                padding: 5px 7px;
                /* ลด padding ของ dropdown */
                font-size: 10px;
                /* ลดขนาดฟอนต์ของ dropdown */
            }

            .custom-search-input {
                height: 30px;
                font-size: 10px;
                /* ลดขนาดฟอนต์ของช่องค้นหา */
                padding-left: 30px;
                /* ลด padding ซ้ายในช่องค้นหา */
            }

            .pagination-container button {
                padding: 5px 6px;
                /* ลดขนาดของปุ่ม pagination */
                font-size: 10px;
                /* ลดขนาดฟอนต์ของปุ่ม pagination */
            }

            /* ขนาดตัวอักษรในการ์ด */
            .card-title {
                font-size: 12px;
                /* ลดขนาดหัวข้อ */
            }

            .card-description,
            .card-pass,
            .card-stats {
                font-size: 10px;
                /* ลดขนาดฟอนต์ให้เล็กลงในหน้าจอที่เล็กมาก */
            }
        }
    </style>
</head>

<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2 class="colorfont">รายวิชาที่สอน</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <!-- Search and Entries -->
                <div class="d-flex">
                    <!-- ช่องค้นหา -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="custom-search-input" id="search-input" placeholder="ค้นหารายวิชา..." onkeyup="searchSubjects()">
                    </div>

                    <!-- เลือกจำนวน Entries ต่อหน้า -->
                    <div class="custom-select-wrapper">
                        <select id="items_per_page" class="custom-select-modern" onchange="changeEntriesPerPage()">
                            <option value="5">5 รายการต่อหน้า</option>
                            <option value="10">10 รายการต่อหน้า</option>
                            <option value="20">20 รายการต่อหน้า</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="card-container" id="card-container">
                <?php
                // แสดงข้อมูลรายวิชาในรูปแบบการ์ด
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $subject_id = $row['subject_id'];
                        $subject_pass = htmlspecialchars($row['subject_pass']);
                        $subject_name = htmlspecialchars($row['subject_name']);
                        $subject_detail = htmlspecialchars($row['subject_detail']);
                        $subject_cover = htmlspecialchars($row['subject_cover']);

                        // ใช้ rawurlencode เพื่อเข้ารหัส URL ของรูปภาพ
                        $image_path = !empty($subject_cover) ? '../../backend/teacher/uploads/' . rawurlencode(basename($subject_cover)) : '../teacher/uploads/d.jpg';

                        // คำนวณสถิติการบ้านและข้อมูลเพิ่มเติม
                        $total_homework_sql = "SELECT COUNT(*) as total_homework FROM tb_homework WHERE subject_id = '$subject_id'";
                        $total_students_sql = "SELECT COUNT(*) as total_students FROM tb_student_subject WHERE subject_id = '$subject_id'";
                        $submitted_sql = "SELECT COUNT(DISTINCT member_id) as submitted FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id')";
                        $not_submitted_sql = "SELECT (SELECT COUNT(*) FROM tb_student_subject WHERE subject_id = '$subject_id') - IFNULL((SELECT COUNT(DISTINCT member_id) FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id')), 0) as not_submitted";
                        $unchecked_sql = "SELECT IFNULL((SELECT COUNT(*) FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id') AND checked = 0), 0) as unchecked";

                        $total_homework = $mysqli->query($total_homework_sql)->fetch_assoc()['total_homework'];
                        $total_students = $mysqli->query($total_students_sql)->fetch_assoc()['total_students'];
                        $submitted = $mysqli->query($submitted_sql)->fetch_assoc()['submitted'];
                        $not_submitted = $mysqli->query($not_submitted_sql)->fetch_assoc()['not_submitted'];
                        $unchecked = $mysqli->query($unchecked_sql)->fetch_assoc()['unchecked'];

                        echo '<div class="card" onclick="window.location.href=\'show_homework.php?subject_pass=' . $subject_pass . '\'">';  // ย้าย onclick มาที่ระดับการ์ด
                        echo '<div class="card-image">';
                        echo '<img src="' . $image_path . '" alt="รูปปก">';
                        echo '</div>';
                        echo '<div class="card-content">';  // ลบ onclick ออกจากที่นี่
                        echo '<div class="card-title">' . $subject_name . '</div>';
                        echo '<div class="card-pass text-work">รหัสรายวิชา: ' . $subject_pass . '</div>';
                        echo '<br>'; // เพิ่มบรรทัดว่าง
                        echo '<div class="card-stats ">งานทั้งหมด: ' . $total_homework . ' งาน</div>';
                        echo '<div class="card-stats text-stus">นักเรียนทั้งหมด: ' . $total_students . ' คน</div>';
                        echo '<div class="card-stats text-black">ยังไม่ตรวจ: ' . $unchecked . ' งาน</div>';

                        // ปุ่มสรุปการส่งงานแยกต่างหาก ไม่รวมกับ onclick ของการ์ด
                        //echo '<button class="summary-button" onclick="event.stopPropagation(); window.location.href=\'summary_homework.php?subject_id=' . $subject_id . '\'">สรุปการส่งงาน</button>';

                        echo '</div>';
                        echo '<div class="download-icon">';
                        echo '<a href="download_excel.php?subject_id=' . $subject_id . '">';
                        echo '<img src="icons/excel-icon.png" alt="Download Excel" width="30">';
                        echo '</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>ไม่มีรายวิชาที่จะแสดง</p>';
                }

                // ปิดการเชื่อมต่อฐานข้อมูล
                $mysqli->close();
                ?>
            </div>
            <!-- Pagination -->
            <center>
                <div class="pagination-container">
                    <button onclick="prevPage()">หน้าก่อนหน้า</button>
                    <span id="pagination-info"></span>
                    <button onclick="nextPage()">หน้าถัดไป</button>
                </div>
            </center>

        </div>
    </div>
</div>


<?php include('footer.php'); ?>
<script>
    let currentPage = 1;
    let entriesPerPage = parseInt(document.getElementById('items_per_page').value);

    function changeEntriesPerPage() {
        entriesPerPage = parseInt(document.getElementById('items_per_page').value);
        currentPage = 1; // รีเซ็ตหน้าเมื่อเปลี่ยนจำนวนรายการต่อหน้า
        showEntries();
    }

    function showEntries() {
        const cards = document.querySelectorAll('.card');
        const totalCards = cards.length;
        const totalPages = Math.ceil(totalCards / entriesPerPage);

        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = startIndex + entriesPerPage;

        cards.forEach((card, index) => {
            card.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
        });

        updatePagination(totalPages);
    }

    function updatePagination(totalPages) {
        const paginationInfo = document.getElementById('pagination-info');
        paginationInfo.textContent = `หน้าที่ ${currentPage} จาก ${totalPages}`;
    }

    function nextPage() {
        const totalCards = document.querySelectorAll('.card').length;
        const totalPages = Math.ceil(totalCards / entriesPerPage);

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

    function searchSubjects() {
        const input = document.getElementById('search-input').value.toLowerCase();
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const titleElement = card.querySelector('.card-title');
            const descriptionElement = card.querySelector('.card-pass');

            const titleText = titleElement ? titleElement.textContent.toLowerCase() : '';
            const descriptionText = descriptionElement ? descriptionElement.textContent.toLowerCase() : '';

            if (titleText.includes(input) || descriptionText.includes(input)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // เริ่มต้นการแสดงผล
    window.onload = function() {
        showEntries();
    };
</script>

</html>