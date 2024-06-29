<?php session_destroy();
include('../../class_conn.php');
$cls_conn = new class_conn; 
echo $cls_conn->goto_page(1,'../../login.php');
?>

