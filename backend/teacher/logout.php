<?php include('header.php');?>
<?php 
session_destroy();
echo $cls_conn->goto_page(2,'../../login.php');
?>

<?php include('footer.php');?>