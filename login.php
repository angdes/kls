<?php include('header.php');?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>KP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="template_login/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="template_login/css/util.css">
    <link rel="stylesheet" type="text/css" href="template_login/css/main.css">  

    <style>
        .limiter {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }
        .wrap-login100 {
            width: 100%;
            max-width: 350px;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            animation: glow 1.5s infinite alternate;
        }
        .logo-container img {
            max-width: 100px;
            border: 2px solid #fff;
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
            transition: transform 0.1s;
        }
        @keyframes glow {
            from {
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
            }
            to {
                box-shadow: 0 0 20px rgba(255, 255, 255, 1);
            }
        }
    </style>
</head>
<body>
    <div class="limiter">
        <div class="wrap-login100">
            <form class="login100-form validate-form" method="post">
                <div class="logo-container">
                    <img src="images/123.jpg" alt="IMG" id="logo">
                </div>
                <div class="text-center">ยินดีต้อนรับเข้าสู่</div>
                <span class="login100-form-title">
                    KP Login
                </span>

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="username" placeholder="Username" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password" required>
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
                if (isset($_POST['submit'])) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    
                    $sql = "SELECT * FROM tb_admin WHERE admin_username='$username' AND admin_password='$password'";
                    $result = $cls_conn->select_base($sql);

                    if (mysqli_num_rows($result) >= 1) {
                        $row = mysqli_fetch_assoc($result);
                        $_SESSION['user'] = $row;
                        $_SESSION['role'] = 'admin';
                        echo $cls_conn->show_message('Login Success');
                        echo $cls_conn->goto_page(1, 'backend/admin/index.php');
                    } else {
                        $sql2 = "SELECT * FROM tb_member WHERE member_username='$username' AND member_password='$password'";
                        $result2 = $cls_conn->select_base($sql2);

                        if (mysqli_num_rows($result2) >= 1) {
                            $row2 = mysqli_fetch_assoc($result2);
                            $_SESSION['user'] = $row2;
                            $_SESSION['role'] = 'member';
                            echo $cls_conn->show_message('Login Success');
                            echo $cls_conn->goto_page(1, 'frontend/member/index.php');
                        } else {
                            $sql3 = "SELECT * FROM tb_teacher WHERE teacher_username='$username' AND teacher_password='$password'";
                            $result3 = $cls_conn->select_base($sql3);

                            if (mysqli_num_rows($result3) >= 1) {
                                $row3 = mysqli_fetch_assoc($result3);
                                $_SESSION['user'] = $row3;
                                $_SESSION['role'] = 'teacher';
                                echo $cls_conn->show_message('Login Success');
                                echo $cls_conn->goto_page(1, 'backend/teacher/index.php');
                            } else {
                                echo $cls_conn->show_message('Login Fail');
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="template_login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="template_login/vendor/bootstrap/js/popper.js"></script>
    <script src="template_login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="template_login/vendor/select2/select2.min.js"></script>
    <script src="template_login/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        // Mouse move effect
        document.addEventListener('mousemove', function(e) {
            var logo = document.getElementById('logo');
            var rect = logo.getBoundingClientRect();
            var mouseX = e.clientX;
            var mouseY = e.clientY;
            var centerX = rect.left + rect.width / 2;
            var centerY = rect.top + rect.height / 2;
            var deltaX = (mouseX - centerX) / 20;
            var deltaY = (mouseY - centerY) / 20;

            logo.style.transform = 'translate(' + deltaX + 'px, ' + deltaY + 'px)';
        });
    </script>
    <script src="template_login/js/main.js"></script>

<?php include('footer.php');?>

</body>
</html>
