<?php
include('header.php'); // รวมไฟล์การตั้งค่าฐานข้อมูล

if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['selected_members'])) {
        $selected_members = $_POST['selected_members'];
        $errors = [];
        
        foreach ($selected_members as $member_id) {
            // ลบข้อมูลที่เกี่ยวข้องใน tb_student_homework ก่อน
            $delete_homework_sql = "DELETE FROM tb_student_homework WHERE member_id = $member_id";
            if (!$cls_conn->write_base($delete_homework_sql)) {
                $errors[] = "Error deleting homework data for member ID $member_id: " . mysqli_error($cls_conn->conn);
            }

            // ลบข้อมูลที่เกี่ยวข้องใน tb_student_subject ต่อไป
            $delete_subject_sql = "DELETE FROM tb_student_subject WHERE member_id = $member_id";
            if (!$cls_conn->write_base($delete_subject_sql)) {
                $errors[] = "Error deleting subject data for member ID $member_id: " . mysqli_error($cls_conn->conn);
            }

            // ลบข้อมูลใน tb_member ต่อไป
            $delete_member_sql = "DELETE FROM tb_member WHERE member_id = $member_id";
            if (!$cls_conn->write_base($delete_member_sql)) {
                $errors[] = "Error deleting member ID $member_id: " . mysqli_error($cls_conn->conn);
            }
        }

        if (empty($errors)) {
            echo "<script>alert('ลบสมาชิกที่เลือกเรียบร้อยแล้ว'); window.location.href = 'show_member.php';</script>";
        } else {
            foreach ($errors as $error) {
                echo "<script>alert('{$error}');</script>";
            }
            echo "<script>window.location.href = 'show_member.php';</script>";
        }

    } else {
        echo "<script>alert('กรุณาเลือกสมาชิกที่ต้องการลบ'); window.location.href = 'show_member.php';</script>";
    }
}

if (isset($_POST['delete_all'])) {
    // ลบข้อมูลที่เกี่ยวข้องใน tb_student_homework ก่อน
    $delete_all_homework_sql = "DELETE FROM tb_student_homework";
    $cls_conn->write_base($delete_all_homework_sql);

    // ลบข้อมูลที่เกี่ยวข้องใน tb_student_subject ต่อไป
    $delete_all_subject_sql = "DELETE FROM tb_student_subject";
    $cls_conn->write_base($delete_all_subject_sql);

    // ลบข้อมูลใน tb_member ต่อไป
    $delete_all_member_sql = "DELETE FROM tb_member";
    if ($cls_conn->write_base($delete_all_member_sql)) {
        echo "<script>alert('ลบสมาชิกทั้งหมดเรียบร้อยแล้ว'); window.location.href = 'show_member.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบสมาชิกทั้งหมด'); window.location.href = 'show_member.php';</script>";
    }
}
?>
