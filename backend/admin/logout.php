<?php include('header.php');
session_unset(); // ล้างข้อมูลเซสชัน
session_destroy(); // ทำลายเซสชัน ?>
<?php 

echo $cls_conn->goto_page(0,'../../login.php');
?>

<?php include('footer.php');?>