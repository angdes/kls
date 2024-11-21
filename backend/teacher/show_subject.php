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

// ฟังก์ชันสำหรับลบข้อมูลหลังยืนยัน
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['subject_id'])) {
    $subject_id = intval($_GET['subject_id']);

    // ลบข้อมูล
    $delete_subject_sql = "DELETE FROM tb_subject WHERE subject_id = $subject_id";

    if ($mysqli->query($delete_subject_sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    title: 'ลบข้อมูลสำเร็จ!',
                    text: 'รายวิชาและข้อมูลที่เกี่ยวข้องถูกลบเรียบร้อยแล้ว',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location.href = 'show_subjects.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถลบข้อมูลได้: " . $mysqli->error . "',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
              </script>";
    }
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงข้อมูลรายวิชา</title>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .btn-m {
            background-color: #FF33CC;
            color: #FFFFFF;
            border: 2px solid #FFFFFF;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .btn-m:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-a {
            background-color: #9966CC;
            color: #FFFFFF;
            border: 2px solid #E0E0E0;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .btn-a:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-d {
            color: white;
            background-color: #808080;
            border: 2px solid #E0E0E0;
            /* ขอบสีเทาอ่อน */
            border-radius: 5px;
            /* ทำให้ขอบมนเล็กน้อย */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* เงาเบาบางใต้ปุ่ม */
            transition: box-shadow 0.3s ease;
            /* เพิ่มเอฟเฟกต์ transition เมื่อ hover */
        }

        .btn-d:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
            /* เงาชัดเจนขึ้นเมื่อ hover */
        }

        .btn-view {
            background-color: #800080;
            color: #FFFFFF;
            border: 2px solid #CCCCCC;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .btn-view:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
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
                        <a href="insert_subject.php">
                            <button class="btn btn-m">เพิ่มรายวิชา</button>
                        </a>
                        <a href="add_student_to_subject.php">
                            <button class="btn btn-d">เพิ่มนักเรียนในรายวิชา</button>
                        </a>
                    </div>
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>รหัสรายวิชา</th>
                                <th>ชื่อรายวิชา</th>
                                <th>รายละเอียด</th>
                                <th>รูปปก</th>
                                <th>ข้อมูลนักเรียนในรายวิชา</th>
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
                                            <a href="view_students_in_subject.php?subject_id=<?= htmlspecialchars($row['subject_id']); ?>" class="btn btn-a">ข้อมูลนักเรียน</a>
                                        </td>
                                        <td>
                                            <a href="update_subject.php?id=<?= htmlspecialchars($row['subject_id']); ?>&teacher_id=<?= htmlspecialchars($teacher_id); ?>" onclick="return confirm('คุณต้องการแก้ไขหรือไม่?')"><img src="../../images/edit.png" alt="Edit" /></a>
                                        </td>
                                        <td>
                                            <a onclick="confirmDelete('<?= htmlspecialchars($row['subject_id']); ?>')"><img src="../../images/delete.png" alt="Delete" /></a>
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

    <script>
        function confirmDelete(subjectId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "ข้อมูลที่อยู่ในรายวิชานี้จะถูกลบทั้งหมด!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "delete_subject.php?subject_id=" + subjectId + "&action=delete";
                }
            });
        }
    </script>

    <?php include('footer.php'); ?>
</body>

</html>