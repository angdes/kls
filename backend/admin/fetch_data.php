<?php
// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "myproject");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $mysqli->connect_error);
}

// รับค่า userType ที่ส่งมา
$userType = $_POST['userType'];

$response = [];

if ($userType === 'students') {
    $response['title'] = 'ข้อมูลนักเรียน';
    $response['head'] = '<tr><th>รหัสนักเรียน</th><th>ชื่อนักเรียน</th><th>อีเมล</th></tr>';

    // ดึงข้อมูลนักเรียน
    $student_sql = "SELECT member_number, member_fullname, member_email FROM tb_member";
    $student_result = $mysqli->query($student_sql);

    $response['body'] = '';
    if ($student_result->num_rows > 0) {
        while ($row = $student_result->fetch_assoc()) {
            $response['body'] .= "<tr>
                                    <td>{$row['member_number']}</td>
                                    <td>{$row['member_fullname']}</td>
                                    <td>{$row['member_email']}</td>
                                  </tr>";
        }
    } else {
        $response['body'] = "<tr><td colspan='3'>ไม่พบข้อมูล</td></tr>";
    }
} else if ($userType === 'teachers') {
    $response['title'] = 'ข้อมูลครู';
    $response['head'] = '<tr><th>ชื่อครู</th><th>เบอร์โทรศัพท์</th></tr>';

    // ดึงข้อมูลครู
    $teacher_sql = "SELECT teacher_fullname, teacher_phone FROM tb_teacher";
    $teacher_result = $mysqli->query($teacher_sql);

    $response['body'] = '';
    if ($teacher_result->num_rows > 0) {
        while ($row = $teacher_result->fetch_assoc()) {
            $response['body'] .= "<tr>
                                    <td>{$row['teacher_fullname']}</td>
                                    <td>{$row['teacher_phone']}</td>
                                  </tr>";
        }
    } else {
        $response['body'] = "<tr><td colspan='2'>ไม่พบข้อมูล</td></tr>";
    }
}

// ส่งผลลัพธ์กลับเป็น JSON
echo json_encode($response);

// ปิดการเชื่อมต่อฐานข้อมูล
$mysqli->close();
