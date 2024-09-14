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
?>

<style>
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

    /* ปุ่ม เพิ่มการบ้านใหม่ */
    .btn-custom {
        background-color: #FF33CC;
        /* สีชมพูสด */
        color: white;
        border: 5px #FF33CC;
        padding: 9px 15px;
        font-size: 12px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background-color 0.3s, color 0.3s;
        transition: box-shadow 0.3s ease;
    }

    .btn-custom:hover {
        background-color: #CC0099;
        border-color: #CC0099;
        color: white;
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    /* ปุ่ม ตรวจงานนักเรียนในรายวิชานี้ */
    .btn-check {
        background-color: #66B2FF;
        color: white;
        border-color: #66B2FF;
        padding: 9px 15px;
        font-size: 12px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-check:hover {
        background-color: #3399FF;
    }

    /* ปุ่ม สรุปงานที่มอบ */
    .btn-summary {
        background-color: #66FF99;
        color: white;
        border-color: #66FF99;
        padding: 9px 15px;
        font-size: 12px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-summary:hover {
        background-color: #33CC66;
    }

    /* ปุ่ม แก้ไข */
    .btn-edit {
        background-color: #FFA500;
        color: white;
        border-color: #FFA500;
        padding: 9px 15px;
        font-size: 12px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit:hover {
        background-color: #FF8C00;
    }

    /* ปุ่ม ลบ */
    .btn-delete {
        background-color: #FF6666;
        color: white;
        border-color: #FF6666;
        padding: 9px 15px;
        font-size: 12px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-delete:hover {
        background-color: #FF3333;
    }

    /* อื่นๆ */
    .report-section {
        background-color: #f9f9f9;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    }


    /* จัดวางช่องค้นหาและตัวเลือก Entries ให้อยู่ในแถวเดียวกัน */
    .d-flex {
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .input-group {
        position: relative;
        width: 30%;
        /* ตั้งความกว้างของช่องค้นหาตามที่ต้องการ */
        margin-right: 20px; 
    }

    .input-group-prepend {
        margin-right: -1px;
    }

    .input-group-text {
        border: none;
        background-color: transparent;
        color: #495057;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
    }

    .custom-search-input {
        width: 100%;
        padding-left: 40px;
        /* ปรับ padding ซ้ายเพื่อทำพื้นที่สำหรับไอคอน */
        padding-right: 10px;
        /* ปรับ padding ขวา */
        border-radius: 20px;
        /* ปรับรูปทรงของช่องค้นหา */
        border: 1px solid #ced4da;
        height: 40px;
        /* กำหนดความสูงของช่องค้นหา */
        font-size: 16px;
        /* กำหนดขนาดตัวอักษร */
    }

    .custom-search-input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    .input-group {
        position: relative;
    }


    .pagination-container {
        margin-top: 20px;
        text-align: center;
    }

    .pagination-container button {
        padding: 5px 10px;
        margin: 0 5px;
        background-color: #ddd;
        border: none;
        cursor: pointer;
    }

    .pagination-container button.active {
        background-color: #FF33CC;
        color: white;
    }

    .pagination-container button:hover {
        background-color: #BA55D3;
        color: white;
    }

    .custom-select-wrapper {
        position: relative;
        display: inline-block;
        width: auto;
    }

    /* ปรับสไตล์ของ select ให้ดูทันสมัย */
    .custom-select-modern {
        border-radius: 20px;
        padding: 10px 15px;
        border: 2px solid #ddd;
        font-size: 14px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        outline: none;
    }

    .custom-select-modern:hover {
        border-color: #007bff;
        background-color: #e9ecef;
    }

    .custom-select-modern:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>



<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>รายงานการบ้านสำหรับวิชา <?= htmlspecialchars($subject_pass); ?></h2>
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
                        <label class="me-2 mb-0">Show</label>
                        <div class="custom-select-wrapper">
                            <select id="entriesSelect" onchange="showEntries()" class="form-select custom-select-modern">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                        <label class="mb-0">Entries</label>
                    </div>
                </div>
            </div>

            <a href="add_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>" class="btn btn-custom">
                <i class="fas fa-plus"></i> เพิ่มการบ้าน
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
                            <label style="font-size: 16px; color:#333333;">หัวข้อการบ้าน:</label>
                            <h2 style="color:#333333;"><?= htmlspecialchars($row['title']); ?></h2>
                        </div>

                        <div class="form-row">
                            <label style="color:#333333;">รายละเอียด:</label>
                            <h4><?= htmlspecialchars($row['description']); ?></h4>
                        </div>

                        <div class="form-row">
                            <label style="color:#333333;">วันที่สั่ง:</label>
                            <h4><?= htmlspecialchars($row['assigned_date']); ?></h4>
                        </div>

                        <div class="form-row">
                            <label style="color:#333333;">วันหมดเขต:</label>
                            <h4><?= htmlspecialchars($row['deadline']); ?></h4>
                        </div>

                        <div class="form-row">
                            <label style="color:#333333;">ตรวจแล้ว:</label>
                            <h4 style="color: green;"><?= $checked_count; ?></h4>
                        </div>

                        <div class="form-row">
                            <label style="color:#333333;">ยังไม่ตรวจ:</label>
                            <h4 style="color: black;"><?= $unchecked_count; ?></h4>
                        </div>

                        <div class="form-row">
                            <label style="color:#333333;">ยังไม่ส่ง:</label>
                            <h4 style="color: red;"><?= $not_submitted_count; ?></h4>
                        </div>

                        <div class="form-row">
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
                <button onclick="prevPage()">หน้าก่อนหน้า</button>
                <span id="paginationButtons"></span>
                <button onclick="nextPage()">หน้าถัดไป</button>
            </div>
        </center>

        <div align="right">
            <a href="show_subjectandwork.php"><button class="btn btn-edit">ย้อนกลับ</button></a>
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

    var currentPage = 1;
    var entriesPerPage = 5;



    function showEntries() {
        var selectElement = document.getElementById("entriesSelect");
        entriesPerPage = parseInt(selectElement.value);
        currentPage = 1;
        updatePagination();
    }

    function updatePagination() {
        var reports = document.getElementsByClassName("report-section");
        var totalReports = reports.length;
        var totalPages = Math.ceil(totalReports / entriesPerPage);

        for (var i = 0; i < totalReports; i++) {
            reports[i].style.display = (i >= (currentPage - 1) * entriesPerPage && i < currentPage * entriesPerPage) ? "" : "none";
        }

        var paginationButtons = document.getElementById("paginationButtons");
        paginationButtons.innerHTML = "";

        for (var i = 1; i <= totalPages; i++) {
            var btn = document.createElement("button");
            btn.innerHTML = i;
            btn.className = (i === currentPage) ? "active" : "";
            btn.onclick = (function(i) {
                return function() {
                    currentPage = i;
                    updatePagination();
                };
            })(i);
            paginationButtons.appendChild(btn);
        }
    }

    function nextPage() {
        var reports = document.getElementsByClassName("report-section");
        var totalReports = reports.length;
        var totalPages = Math.ceil(totalReports / entriesPerPage);

        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    }

    window.onload = function() {
        updatePagination();
    };
</script>