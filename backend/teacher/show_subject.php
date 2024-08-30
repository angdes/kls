<?php
include('header.php');

// ตรวจสอบว่ามีการล็อกอินและมีข้อมูลผู้ใช้ในเซสชันหรือไม่
if (!isset($_SESSION['user'])) {
    echo "คุณต้องล็อกอินก่อนเพื่อเพิ่มรายวิชา";
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

// เตรียมคำสั่ง SQL
$sql = "SELECT * FROM tb_subject WHERE teacher_id = $teacher_id";
$result = $mysqli->query($sql);

// ตรวจสอบการดึงข้อมูล
if ($result === false) {
    die("การดึงข้อมูลล้มเหลว: " . $mysqli->error);
}

// แสดงข้อมูล
?>
<style>
    .btn-danger {
        background-color: hotpink;
        border-color: black;
        color: black;
    }
    .btn-warning {
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
        <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="x_title">
                <h2>แสดงข้อมูลรายวิชา</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="text-muted font-13 m-b-30">
                <div align="left">
                    <!-- เพิ่มข้อมูลผ่าน URL parameter teacher_id -->
                    <a href="insert_subject.php">
                        <button class="btn btn-danger">เพิ่มรายวิชา</button>
                    </a>
                    <a href="add_student_to_subject.php">
                        <button class="btn btn-warning">เพิ่มนักเรียนในรายวิชา</button>
                    </a>
                </div>
                <table id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>รหัสรายวิชา</th>
                            <th>ชื่อรายวิชา</th>
                            <th>รายละเอียด</th>
                            <th>รูปปก</th>
                            <th>ดูนักเรียน</th>
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
                                    <td><?= $index++; ?></td>
                                    <td><?= htmlspecialchars($row['subject_pass']); ?></td>
                                    <td><?= htmlspecialchars($row['subject_name']); ?></td>
                                    <td><?= htmlspecialchars($row['subject_detail']); ?></td>
                                    <td>
                                        <?php if (!empty($row['subject_cover'])): ?>
                                            <img src="<?= htmlspecialchars($row['subject_cover']); ?>" alt="Cover Image" style="width: 100px; height: auto;">
                                        <?php else: ?>
                                            ไม่มีรูปภาพ
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="view_students_in_subject.php?subject_id=<?= htmlspecialchars($row['subject_id']); ?>" class="btn btn-info">ดูนักเรียน</a>
                                    </td>
                                    <td>
                                        <a href="update_subject.php?id=<?= htmlspecialchars($row['subject_id']); ?>&teacher_id=<?= htmlspecialchars($teacher_id); ?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')"><img src="../../images/edit.png" alt="Edit" /></a>
                                    </td>
                                    <td>
                                        <a href="delete_subject.php?id=<?= htmlspecialchars($row['subject_id']); ?>&teacher_id=<?= htmlspecialchars($teacher_id); ?>" onclick="return confirm('คุณต้องการลบหรือไม่?')"><img src="../../images/delete.png" alt="Delete" /></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="8">ไม่มีข้อมูล</td></tr>';
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
