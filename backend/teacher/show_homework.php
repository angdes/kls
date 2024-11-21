<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อดูการบ้าน";
    exit();
}

// ดึงค่า teacher_id จากเซสชัน
$teacher_id = $_SESSION['user']['teacher_id'];

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า subject_pass จาก URL
$subject_pass = $_GET['subject_pass'];

// ดึงข้อมูลการบ้านจากฐานข้อมูลตามรายวิชาที่เลือก
$sql = "SELECT homework_id, title, description, assigned_date, deadline FROM tb_homework WHERE subject_pass = '$subject_pass'";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}
// ดึงข้อมูลจำนวนนักเรียนทั้งหมดในรายวิชา
$sql_total_students = "SELECT COUNT(*) as total_students FROM tb_student_subject WHERE subject_id = (SELECT subject_id FROM tb_subject WHERE subject_pass = '$subject_pass' LIMIT 1)";
$result_total_students = $mysqli->query($sql_total_students);
$total_students = $result_total_students->fetch_assoc()['total_students'] ?? 0;

?>

<style>
    /* การตั้งค่าพื้นฐาน */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* การตั้งค่าการ์ด */
    .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        /* อนุญาตให้รายการหักขึ้นบรรทัดใหม่เมื่อไม่พอดี */
    }

    .form-row label {
        flex: 0 0 150px;
        /* ตายตัว */
        margin-right: 10px;
        font-weight: bold;
        white-space: nowrap;
        /* ป้องกันข้อความถูกหักบรรทัด */
    }

    .form-row h4 {
        margin: 0;
        flex-grow: 1;
        /* ให้ h4 ขยายเต็มพื้นที่ที่เหลือ */
    }

    /* สไตล์ปุ่มทั่วไป */
    .btn-custom,
    .btn-check,
    .btn-summary,
    .btn-edit,
    .btn-delete {
        padding: 9px 15px;
        font-size: 12px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background-color 0.3s, color 0.3s, box-shadow 0.3s ease;
        border-radius: 5px;
        color: white;
        border: none;
        cursor: pointer;
    }

    /* สไตล์ปุ่มตามสีที่เฉพาะเจาะจง */
    .btn-custom {
        background-color: #ea689e;
        color: white;
    }

    .btn-custom:hover {
        background-color: #CC0099;
        color: white;
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-check {
        background-color: #66B2FF;
    }

    .btn-check:hover {
        background-color: #3399FF;
    }

    .btn-summary {
        background-color: #66FF99;
    }

    .btn-summary:hover {
        background-color: #33CC66;
    }

    .btn-edit {
        background-color: #FFA500;
    }

    .btn-edit:hover {
        background-color: #FF8C00;
    }

    .btn-delete {
        background-color: #FF6666;
    }

    .btn-delete:hover {
        background-color: #FF3333;
    }

    .btn-check,
    .btn-summary,
    .btn-edit,
    .btn-delete {
        padding: 6px 15px;
        /* ลด padding ของปุ่ม */
        font-size: 11px;
        /* ลดขนาดตัวอักษรของปุ่ม */
    }

    /* อื่นๆ */
    .report-section {
        background-color: #f9f9f9;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        width: 80%;
        height: 210px;
    }

    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;

    }

    h4,
    label {
        font-size: 14px;
        /* ลดขนาดข้อความ */
    }

    h2 {
        font-size: 18px;
        /* ลดขนาดหัวข้อ */
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
        background-color: #ddd;
        color: black;
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
    @media screen and (max-width: 768px) {
    .form-row label {
        flex: 0 0 100px; /* ลดขนาดพื้นที่ของ label บนมือถือ */
        margin-right: 5px; /* ลดระยะห่างระหว่าง label และข้อความ */
    }
    .form-row {
        flex-wrap: wrap; /* อนุญาตให้ขึ้นบรรทัดใหม่ */
    }

    .form-row h4, .form-row label {
        flex: 100%; /* ให้แต่ละองค์ประกอบใช้พื้นที่เต็มในแต่ละบรรทัด */
        flex-wrap: wrap;
        margin-right: 0; /* เอาค่า margin ด้านขวาออกในมือถือ */
    }

    /* ปรับรูปแบบสีสำหรับ "ยังไม่ส่ง" */
    .form-row h4:last-of-type, .form-row label:last-of-type {
        margin-top: 10px; /* เพิ่มระยะห่างระหว่างบรรทัด */
    }

    .btn-custom,
    .btn-check,
    .btn-summary,
    .btn-edit,
    .btn-delete {
        padding: 5px 10px; /* ลด padding ของปุ่มบนมือถือ */
        font-size: 10px; /* ลดขนาดตัวอักษรของปุ่ม */
    }

    .report-section {
        width: 95%; /* ปรับให้ความกว้างครอบคลุมพื้นที่หน้าจอมือถือ */
        height: auto; /* ให้การ์ดปรับขนาดตามเนื้อหา */
        padding: 10px; /* ลด padding ให้เหมาะกับมือถือ */
    }

    h2, h4 {
        font-size: 14px; /* ลดขนาดตัวอักษร */
    }

    .custom-search-input {
        font-size: 14px; /* ลดขนาด input box สำหรับมือถือ */
        height: 35px;
    }

    .pagination-container button {
        padding: 3px 7px; /* ลดขนาดของปุ่ม pagination */
    }
}

    
   
</style>



<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2 style="color: black;"><b>รายการงานของรหัสวิชา <?= htmlspecialchars($subject_pass); ?></b></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="search-container d-flex justify-content-between align-items-center">
                    <!-- ช่องค้นหา -->
                    <div class="input-group search-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-icon"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="searchInput" class="form-control custom-search-input" placeholder="ค้นหาข้อมูล...">
                    </div>

                    <!-- ตัวเลือก Entries -->
                    <div class="d-flex align-items-center">
                        <label class="me-2 mb-0">แสดง </label>
                        <div class="custom-select-wrapper">
                            <select id="entriesSelect" onchange="showEntries()" class="form-select custom-select-modern">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                        <label class="mb-0"> รายการ</label>
                    </div>
                </div>
            </div>

            <a href="add_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>" class="btn btn-custom">
                <i class="fas fa-plus"></i> เพิ่มงาน
            </a>
            <br><br>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $homework_id = htmlspecialchars($row['homework_id']);
                    $checked_sql = "SELECT COUNT(*) as checked_count FROM tb_student_homework WHERE homework_id = $homework_id AND checked = 1";
                    $unchecked_sql = "SELECT COUNT(*) as unchecked_count FROM tb_student_homework WHERE homework_id = $homework_id AND (checked = 0 OR checked IS NULL)";
                    $checked_result = $mysqli->query($checked_sql);
                    $unchecked_result = $mysqli->query($unchecked_sql);
                    $checked_count = $checked_result->fetch_assoc()['checked_count'] ?? 0;
                    $unchecked_count = $unchecked_result->fetch_assoc()['unchecked_count'] ?? 0;

                    $students_sql = "SELECT COUNT(*) as total_students FROM tb_student_subject WHERE subject_id = (SELECT subject_id FROM tb_subject WHERE subject_pass = '$subject_pass' LIMIT 1)";
                    $submitted_students_sql = "SELECT COUNT(DISTINCT member_id) as submitted_count FROM tb_student_homework WHERE homework_id = $homework_id";
                    $students_result = $mysqli->query($students_sql);
                    $submitted_result = $mysqli->query($submitted_students_sql);
                    $total_students = $students_result->fetch_assoc()['total_students'] ?? 0;
                    $submitted_count = $submitted_result->fetch_assoc()['submitted_count'] ?? 0;
                    $not_submitted_count = $total_students - $submitted_count;
            ?>

                    <div class="report-section">
                        <div class="form-row">
                            <label style="font-size: 16px; color:#333333;">หัวข้องาน:</label>
                            <h2 style="color:#333333;"><?= htmlspecialchars($row['title']); ?></h2>
                        </div>

                        <div class="form-row">
                            <label style="color:#333333;">รายละเอียดงาน:</label>
                            <h4>
                                <?= htmlspecialchars(substr($row['description'], 0, 180)); ?>
                                <?php if (strlen($row['description']) > 150) : ?>
                                    .. <!-- เพิ่มจุดไข่ปลาหากข้อความเกิน 100 ตัวอักษร -->
                                <?php endif; ?>
                            </h4>
                        </div>
                        <div class="form-row" style="display: flex; align-items: center;">
                            <label style="color:#333333; margin-right: 10px;">วันที่สั่ง:</label>
                            <h4 style="margin-right: 30px;"><?= htmlspecialchars($row['assigned_date']); ?></h4>

                            <label style="color:#333333; margin-right: 10px;">วันหมดเขต:</label>
                            <h4><?= htmlspecialchars($row['deadline']); ?></h4>
                        </div>

                        <div class="form-row" style="display: flex; align-items: center; flex-wrap: nowrap;">
                            <label style="color:#333333; margin-right: 3px;">ตรวจแล้ว:</label>
                            <h4 style="color: green; margin-right: 30px;">
                                <?= $checked_count; ?>/<?= $total_students; ?> <!-- แสดงจำนวนนักเรียนที่ตรวจแล้ว/จำนวนนักเรียนทั้งหมด -->
                            </h4>

                            <label style="color:#333333; margin-right: 3px;">ยังไม่ตรวจ:</label>
                            <h4 style="color: black; margin-right: 30px;">
                                <?= $unchecked_count; ?>/<?= $total_students; ?> <!-- แสดงจำนวนนักเรียนที่ยังไม่ตรวจ/จำนวนนักเรียนทั้งหมด -->
                            </h4>

                            <label style="color:#333333; margin-right: 2px;">ยังไม่ส่ง:</label>
                            <h4 style="color: red;">
                                <?= $not_submitted_count; ?>/<?= $total_students; ?> <!-- แสดงจำนวนนักเรียนที่ยังไม่ส่ง/จำนวนนักเรียนทั้งหมด -->
                            </h4>
                        </div>


                        <div class="form-row" style="justify-content: flex-end;">
                            <a href="check_homework.php?homework_id=<?= $homework_id; ?>" class="btn btn-check">
                                <i class="fas fa-search"></i> ตรวจ
                            </a>
                            <a href="summary.php?homework_id=<?= $homework_id; ?>" class="btn btn-summary">
                                <i class="fas fa-chart-bar"></i> สรุปงาน
                            </a>
                            <a href="edit_homework.php?homework_id=<?= $homework_id; ?>&subject_pass=<?= $subject_pass; ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> แก้ไข
                            </a>
                            <a href="delete_homework.php?homework_id=<?= $homework_id; ?>&subject_pass=<?= $subject_pass; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')" class="btn btn-delete">
                                <i class="fas fa-trash"></i> ลบ
                            </a>
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
        <center>
            <div class="pagination-container">
                <button onclick="prevPage()" class="btn btn-custom">หน้าก่อนหน้า</button>
                <span id="paginationButtons"></span>
                <button onclick="nextPage()" class="btn btn-custom">หน้าถัดไป</button>
            </div>
        </center>

        <div align="right">
            <a href="show_subjectandwork.php"><button class="btn btn-success">ย้อนกลับ</button></a>
        </div>
    </div>
</div>
</div>

<?php include('footer.php'); ?>
<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        var filter = this.value.toLowerCase();
        var reports = document.getElementsByClassName("report-section");

        for (var i = 0; i < reports.length; i++) {
            var report = reports[i];
            var title = report.querySelector("h2").innerText.toLowerCase();
            var description = report.querySelector("h4").innerText.toLowerCase();

            // ตรวจสอบว่าชื่อหรือรายละเอียดมีคำค้นหาหรือไม่
            if (title.includes(filter) || description.includes(filter)) {
                report.style.display = ""; // แสดงข้อมูลที่ตรงกับคำค้นหา
            } else {
                report.style.display = "none"; // ซ่อนข้อมูลที่ไม่ตรงกับคำค้นหา
            }
        }
    });

    // กำหนดค่าเริ่มต้นของหน้าและจำนวนรายการต่อหน้า
    var currentPage = 1;
    var entriesPerPage = 5;

    // ฟังก์ชันสำหรับเปลี่ยนแปลงจำนวนรายการที่จะแสดงต่อหน้า
    function showEntries() {
        var selectElement = document.getElementById("entriesSelect");
        entriesPerPage = parseInt(selectElement.value); // อัปเดตจำนวนรายการต่อหน้า
        currentPage = 1; // กลับไปหน้าแรก
        updatePagination(); // อัปเดตการแบ่งหน้า
    }

    // ฟังก์ชันสำหรับอัปเดตการแบ่งหน้า
    function updatePagination() {
        var reports = document.getElementsByClassName("report-section");
        var totalReports = reports.length;
        var totalPages = Math.ceil(totalReports / entriesPerPage); // คำนวณจำนวนหน้าทั้งหมด

        // ซ่อนหรือแสดงการ์ดตามจำนวน Entries ต่อหน้าและหน้าปัจจุบัน
        for (var i = 0; i < totalReports; i++) {
            reports[i].style.display = (i >= (currentPage - 1) * entriesPerPage && i < currentPage * entriesPerPage) ? "" : "none";
        }

        // สร้างปุ่ม pagination ตามจำนวนหน้า
        var paginationButtons = document.getElementById("paginationButtons");
        paginationButtons.innerHTML = "";

        for (var i = 1; i <= totalPages; i++) {
            var btn = document.createElement("button");
            btn.innerHTML = i;
            btn.className = (i === currentPage) ? "active" : ""; // ไฮไลต์ปุ่มของหน้าปัจจุบัน
            btn.onclick = (function(i) {
                return function() {
                    currentPage = i; // เปลี่ยนหน้าปัจจุบัน
                    updatePagination(); // อัปเดตการแสดงผลการ์ด
                };
            })(i);
            paginationButtons.appendChild(btn); // เพิ่มปุ่มใน DOM
        }
    }

    // ฟังก์ชันสำหรับเลื่อนไปหน้าถัดไป
    function nextPage() {
        var reports = document.getElementsByClassName("report-section");
        var totalReports = reports.length;
        var totalPages = Math.ceil(totalReports / entriesPerPage); // คำนวณจำนวนหน้าทั้งหมด

        if (currentPage < totalPages) { // ตรวจสอบว่าไม่เกินจำนวนหน้าสุดท้าย
            currentPage++; // เพิ่มหน้าปัจจุบัน
            updatePagination(); // อัปเดตการแสดงผล
        }
    }

    // ฟังก์ชันสำหรับเลื่อนไปหน้าก่อนหน้า
    function prevPage() {
        if (currentPage > 1) { // ตรวจสอบว่าหน้าไม่ต่ำกว่า 1
            currentPage--; // ลดค่าหน้าปัจจุบัน
            updatePagination(); // อัปเดตการแสดงผล
        }
    }

    // ฟังก์ชันสำหรับเริ่มต้นการแบ่งหน้า
    window.onload = function() {
        updatePagination(); // อัปเดตการแสดงผลเมื่อหน้าโหลดเสร็จ
    };
</script>