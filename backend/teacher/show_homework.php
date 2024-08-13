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

// ดึงข้อมูลการบ้านของครู
$sql = "SELECT tb_homework.*, tb_subject.subject_pass, tb_subject.subject_name
        FROM tb_homework
        JOIN tb_subject ON tb_homework.subject_id = tb_subject.subject_id
        WHERE tb_homework.teacher_id = $teacher_id";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}
?>
<style>
    .btn-hotpink {
        background-color: hotpink;
        border-color: hotpink;
        color: black;
    }
    .btn-blue {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .btn-green {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }
    .btn-info-small {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
        padding: 2px 6px;
        font-size: 12px;
    }
    .homework-date {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
        font-size: 12px;
        color: #555;
    }
</style>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>แสดงข้อมูลการบ้าน</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="text-muted font-13 m-b-30">
                    <div align="left">
                        <a href="add_homework.php">
                            <button class="btn btn-hotpink">เพิ่มการบ้าน</button>
                        </a>
                        <a href="add_student_to_homework.php">
                            <button class="btn btn-blue">เพิ่มนักเรียน</button>
                        </a>
                    </div>
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสรายวิชา</th>
                                <th>ชื่อรายวิชา</th>
                                <th>หัวข้อการบ้าน</th>
                                <th>รายละเอียด</th>
                                <th>วันที่สั่ง/วันหมดเขต</th>
                                <th>ไฟล์</th>
                                <th>ตรวจ</th>
                                <th>แก้ไข</th>
                                <th>ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 1;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['subject_pass']); ?></td>
                                        <td><?= htmlspecialchars($row['subject_name']); ?></td>
                                        <td><?= htmlspecialchars($row['title']); ?></td>
                                        <td><?= htmlspecialchars($row['description']); ?></td>
                                        <td>
                                            <div class="homework-date">
                                                <span>วันที่สั่ง: <?= htmlspecialchars($row['assigned_date']); ?></span>
                                                <span>วันหมดเขต: <?= htmlspecialchars($row['deadline']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($row['file_path']) { ?>
                                                <a href="<?= htmlspecialchars($row['file_path']); ?>" target="_blank" class="btn btn-info">ดูไฟล์</a>
                                            <?php } else { ?>
                                                <button class="btn btn-secondary" disabled>ไม่มีไฟล์</button>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="view_students.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>" class="btn btn-green">ตรวจ</a>
                                        </td>
                                        <td>
                                            <a href="update_homework.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>" onclick="return confirm('คุณต้องการแก้ไขการบ้านนี้หรือไม่?')">
                                                <img src="../../images/edit.png" />
                                            </a>
                                        </td>
                                        <td>
                                            <a href="delete_homework.php?homework_id=<?= htmlspecialchars($row['homework_id']); ?>" onclick="return confirm('คุณต้องการลบการบ้านนี้หรือไม่?')">
                                                <img src="../../images/delete.png"/>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="9">ไม่มีข้อมูลการบ้าน</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$mysqli->close();
include('footer.php');
?>
