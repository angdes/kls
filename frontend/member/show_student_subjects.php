<?php include('header.php'); ?>

<?php
// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// ดึงข้อมูลรายวิชาจากฐานข้อมูลที่นักเรียนลงทะเบียน
$student_id = $_SESSION['user']['member_id'];
$sql = "SELECT s.subject_pass, s.subject_name, s.subject_detail, s.subject_cover, s.subject_id, t.teacher_fullname 
        FROM tb_subject s
        JOIN tb_student_subject ss ON s.subject_id = ss.subject_id 
        JOIN tb_teacher t ON s.teacher_id = t.teacher_id
        WHERE ss.member_id = '$student_id'";
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
    <title>แสดงข้อมูลรายวิชาของนักเรียน</title>
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
        .search-container {
            width: 60%;
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

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            width: 100%;
            padding: 30px;
            max-width: 1000px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            margin: 10px;
            width: calc(50% - 20px);
            display: flex;
            flex-direction: row;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .card:nth-child(odd) {
            justify-content: flex-start;
        }

        .card:nth-child(even) {
            justify-content: flex-end;
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

        .stats .total {
            color: black;
        }

        .stats .submitted {
            color: green;
        }

        .stats .not-submitted {
            color: red;
        }

        .stats .checked {
            color: black;
        }

        .teacher-name {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        /* Media Queries สำหรับหน้าจอขนาดเล็ก */
        @media (max-width: 768px) {
            .card {
                height: 50%;
                width: 100%;
                /* กำหนดให้การ์ดเต็มความกว้าง */
                margin: 5px 0;
                /* ตั้งค่าระยะห่างแนวตั้งเล็กน้อยระหว่างการ์ด */
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
                /* ปรับลดเงาให้เบาลง */
                padding: 15px;
                /* เพิ่มพื้นที่ภายในการ์ด */
                flex-direction: column;
                /* จัดเนื้อหาในการ์ดให้อยู่ในแนวตั้ง */
            }

            .card-image img {
                width: 100%;
                height: 30%;

            }

            .card-content {
                height: 50px;
                padding: 10px;
                /* เพิ่ม padding ภายในส่วนของเนื้อหาการ์ด */
                align-items: center;
                /* จัดให้เนื้อหาภายในการ์ดอยู่กึ่งกลาง */
            }

            .search-container>div {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-container {
                width: 100%;
            }

            #search-input {
                font-size: 16px;
            }

            #items_per_page {
                margin-top: 5px;
                width: 25%;
                padding: 3px;
                border-radius: 5px;
            }
        }
    </style>
</head>

<body>

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>รายวิชาของนักเรียน</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- เพิ่มช่องค้นหา -->
                    <div class="search-container">
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            <div style="flex: 1; display: flex; position: relative;">
                                <input type="text" id="search-input" placeholder="ค้นหาวิชา..." onkeyup="searchCards()" style="flex: 1;">
                                <span class="search-icon">&#128269;</span>
                            </div>

                            <!-- Dropdown สำหรับเลือกจำนวน Entries -->
                            <div style="flex-shrink: 0; margin-left: 20px;">
                                <label for="items_per_page">แสดง:</label>
                                <select id="items_per_page" name="items_per_page" onchange="changeEntriesPerPage()">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                </select>
                                รายการต่อหน้า
                            </div>
                        </div>
                    </div>


                    <div class="card-container">
                        <?php
                        // แสดงข้อมูลรายวิชาในรูปแบบการ์ด
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $subject_pass = htmlspecialchars($row['subject_pass']);
                                $subject_name = htmlspecialchars($row['subject_name']);
                                $subject_cover = htmlspecialchars($row['subject_cover']);
                                $subject_id = htmlspecialchars($row['subject_id']);
                                $teacher_fullname = htmlspecialchars($row['teacher_fullname']);

                                // ปรับเส้นทางรูปปกให้ถูกต้อง
                                $image_path = !empty($subject_cover) ? '../../backend/teacher/uploads/' . rawurlencode(basename($subject_cover)) : '../../backend/teacher/uploads/default.png';

                                // คำสั่ง SQL เพื่อดึงข้อมูลจำนวนการบ้านทั้งหมด, ส่งแล้ว, ยังไม่ส่ง, และตรวจแล้ว
                                $total_sql = "SELECT COUNT(*) as total FROM tb_homework WHERE subject_id = '$subject_id'";
                                $submitted_sql = "SELECT COUNT(*) as submitted FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id') AND member_id = '$student_id' AND submission_time IS NOT NULL";
                                $not_submitted_sql = "SELECT COUNT(*) as not_submitted FROM tb_homework WHERE subject_id = '$subject_id' AND homework_id NOT IN (SELECT homework_id FROM tb_student_homework WHERE member_id = '$student_id' AND submission_time IS NOT NULL)";
                                $checked_sql = "SELECT COUNT(*) as checked FROM tb_student_homework WHERE homework_id IN (SELECT homework_id FROM tb_homework WHERE subject_id = '$subject_id') AND member_id = '$student_id' AND checked = 1";

                                // ดึงข้อมูลการบ้าน
                                $total_result = $mysqli->query($total_sql);
                                $submitted_result = $mysqli->query($submitted_sql);
                                $not_submitted_result = $mysqli->query($not_submitted_sql);
                                $checked_result = $mysqli->query($checked_sql);

                                $total = $total_result->fetch_assoc()['total'];
                                $submitted = $submitted_result->fetch_assoc()['submitted'];
                                $not_submitted = $not_submitted_result->fetch_assoc()['not_submitted'];
                                $checked = $checked_result->fetch_assoc()['checked'];

                                echo '<div class="card" onclick="window.location.href=\'show_homework_student.php?subject_id=' . $subject_id . '\'">';
                                echo '<div class="card-image">';
                                echo '<img src="' . $image_path . '" alt="รูปปก">';
                                echo '</div>';
                                echo '<div class="card-content">';
                                echo '<div class="card-title">' . $subject_name . '</div>';
                                echo '<div class="card-pass">รหัสวิชา: ' . $subject_pass . '</div>';
                                echo '<div class="teacher-fullname">ครูผู้สอน: ' . $teacher_fullname . '</div>';
                                echo '<div class="stats">';
                                echo '<p class="total">งานทั้งหมด: ' . $total . '</p>';
                                echo '<p class="submitted">ส่งแล้ว: ' . $submitted . '</p>';
                                echo '<p class="not-submitted">ยังไม่ส่ง: ' . $not_submitted . '</p>';
                                echo '<p class="checked">ครูตรวจแล้ว: ' . $checked . '</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>ไม่มีรายวิชาที่นักเรียนลงทะเบียน</p>';
                        }

                        // ปิดการเชื่อมต่อฐานข้อมูล
                        $mysqli->close();
                        ?>
                    </div>
                    <center>
                        <div class="pagination-container">
                            <button onclick="prevPage()" class="btn-custom">หน้าก่อนหน้า</button>
                            <span id="paginationButtons" ></span>
                            <button onclick="nextPage()" class="btn-custom">หน้าถัดไป</button>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        var currentPage = 1;
        var entriesPerPage = 10; // ค่าตั้งต้น

        // ฟังก์ชันสำหรับแสดงการ์ดตามจำนวน Entries ที่เลือก
        function showEntries() {
            var selectElement = document.getElementById("items_per_page");
            entriesPerPage = parseInt(selectElement.value); // รับค่าจำนวนการ์ดต่อหน้า
            currentPage = 1; // กลับไปหน้าแรกเมื่อเปลี่ยนจำนวน Entries
            updatePagination();
        }

        // ฟังก์ชันสำหรับอัปเดตการแบ่งหน้า
        function updatePagination() {
            var cards = document.getElementsByClassName("card");
            var totalCards = cards.length;
            var totalPages = Math.ceil(totalCards / entriesPerPage);

            // แสดงหรือซ่อนการ์ดตามหน้าปัจจุบันและจำนวนการ์ดต่อหน้า
            for (var i = 0; i < totalCards; i++) {
                cards[i].style.display = (i >= (currentPage - 1) * entriesPerPage && i < currentPage * entriesPerPage) ? "" : "none";
            }

            // อัปเดตปุ่มแบ่งหน้า
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

        // ฟังก์ชันสำหรับเลื่อนไปหน้าถัดไป
        function nextPage() {
            var cards = document.getElementsByClassName("card");
            var totalCards = cards.length;
            var totalPages = Math.ceil(totalCards / entriesPerPage);

            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        }

        // ฟังก์ชันสำหรับเลื่อนไปหน้าก่อนหน้า
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        }

        // เริ่มต้นการแบ่งหน้าเมื่อโหลดหน้า
        window.onload = function() {
            updatePagination();
        };



        function searchCards() {
            var input, filter, cards, i, txtValue, title, pass, teacher;

            input = document.getElementById('search-input');
            filter = input.value.toLowerCase();
            cards = document.getElementsByClassName('card');

            for (i = 0; i < cards.length; i++) {
                title = cards[i].getElementsByClassName("card-title")[0];
                pass = cards[i].getElementsByClassName("card-pass")[0];
                teacher = cards[i].getElementsByClassName("teacher-fullname")[0];

                txtValue = title.textContent || title.innerText;
                txtValue += pass.textContent || pass.innerText;
                txtValue += teacher.textContent || teacher.innerText;

                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>


</body>

</html>