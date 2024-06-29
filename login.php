<?php session_start(); ?>
<?php include('class_conn.php');?>
<?php $cls_conn=new class_conn();?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>KP</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="template_login/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="template_login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="template_login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="template_login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="template_login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="template_login/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="template_login/css/util.css">
	<link rel="stylesheet" type="text/css" href="template_login/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/123.jpg" alt="IMG">
				</div>

				<form class="login100-form validate-form" method="post">
                    <div class="text-center">ยินดีต้อนรับเข้าสู่</div>
					<span class="login100-form-title">
						KP Login
					</span>

					<div class="wrap-input100 validate-input"  >
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" name="submit">
							เข้าสู่ระบบ
						</button>
					</div>

					<div class="container-login100-form-btn">
    <a href="index.php" class="login50-form-btn" name="backhome">
        กลับไปยังหน้าหลัก
    </a>
</div>

 
 
				</form>
                <?php
               if(isset($_POST['submit']))
			   {
				   $username=$_POST['username'];
				   $password=$_POST['password'];
				   
				   $sql=" select * from tb_admin";
				   $sql.=" where";
				   $sql.=" admin_username='$username'";
				   $sql.=" and";
				   $sql.=" admin_password='$password'";
				   $num=$cls_conn->select_numrows($sql);
				   if($num>=1)
				   {
					   echo $cls_conn->show_message('Login Success');
					   echo $cls_conn->goto_page(1,'backend/admin/index.php');
				   }
				   else
				   {
						   $sql2=" select * from tb_member";
						   $sql2.=" where";
						   $sql2.=" member_username='$username'";
						   $sql2.=" and";
						   $sql2.=" member_password='$password'";
						   $num2=$cls_conn->select_numrows($sql2);
						   if($num2>=1)
						   {
							   echo $cls_conn->show_message('Login Success');
							   echo $cls_conn->goto_page(1,'frontend/member/index.php');
						   }
						   else
						   {
							   $sql3=" select * from tb_member";
							   $sql3.=" where";
							   $sql3.=" member_email='$username'";
							   $sql3.=" and";
							   $sql3.=" member_password='$password'";
							   $num3=$cls_conn->select_numrows($sql3);
							   if($num3>=1)
							   {
								   echo $cls_conn->show_message('Login Success');
								   echo $cls_conn->goto_page(1,'backend/admin/index.php');
							   }
							   else
							   {
							   echo $cls_conn->show_message('Login Fail');
							   }
						   }
					   
					   
				   }
			   }
                ?>
                
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="template_login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="template_login/vendor/bootstrap/js/popper.js"></script>
	<script src="template_login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="template_login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="template_login/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>