<?php include('header.php');?>
<?php
session_destroy();
echo $cls_conn->goto_page(3,'../../login.php');

?>
<?php include('footer.php');?>