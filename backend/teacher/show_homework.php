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
    .btn-danger {
        background-color: hotpink;
        border-color: black;
        color: black;
    }
    .btn-Warning {
        background-color: yellow;
        border-color: black;
        color: black;
    }
    .btn-info {
        background-color: blue;
        border-color: black;
        color: white;
    }
</style>
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>รายการการบ้านสำหรับวิชา <?= htmlspecialchars($subject_pass); ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <a href="add_homework.php?subject_pass=<?= htmlspecialchars($subject_pass); ?>" class="btn btn-success">เพิ่มการบ้านใหม่</a>
                <table id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>หัวข้อการบ้าน</th>
                            <th>รายละเอียด</th>
                            <th>วันที่สั่ง</th>
                            <th>วันหมดเขต</th>
                            <th>ตรวจแล้ว</th>
                            <th>ยังไม่ตรวจ</th>
                            <th>ตรวจงาน</th>
                            <th>สรุปงาน</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $homework_id = htmlspecialchars($row['homework_id']);
                                
                                // ตัวอย่างการคำนวณ "ตรวจแล้ว" และ "ยังไม่ตรวจ"
                                $checked_count = rand(1, 10); // สมมติข้อมูล
                                $unchecked_count = rand(1, 10); // สมมติข้อมูล
                                ?>
                                <tr>
                                    <td><?= $index++; ?></td>
                                    <td><?= htmlspecialchars($row['title']); ?></td>
                                    <td><?= htmlspecialchars($row['description']); ?></td>
                                    <td><?= htmlspecialchars($row['assigned_date']); ?></td>
                                    <td><?= htmlspecialchars($row['deadline']); ?></td>
                                    <td><?= $checked_count; ?></td>
                                    <td><?= $unchecked_count; ?></td>
                                    <td>
                                        <a href="check_homework.php?homework_id=<?= $homework_id; ?>" class="btn btn-info">ตรวจงาน</a>
                                    </td>
                                    <td>
                                        <a href="summary.php?homework_id=<?= $homework_id; ?>" class="btn btn-Warning">สรุปงาน</a>
                                    </td>
                                    <td>
                                        <a href="delete_homework.php?homework_id=<?= $homework_id; ?>&subject_pass=<?= $subject_pass; ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" /></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="10">ไม่มีการบ้านที่จะแสดง</td></tr>';
                        }

                        $mysqli->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
