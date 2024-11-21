<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="template_login/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="template_login/css/util.css">
    <link rel="stylesheet" type="text/css" href="template_login/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <style>
        .limiter {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        .wrap-input100 {
            position: relative;
        }

        .field-icon {
            position: absolute;
            right: 10px;
            top: 40%;
            
            cursor: pointer;
            color: #666;
        }


        .input100 {
            padding-right: 30px;
            /* Add padding to prevent text from overlaying the icon */
        }


        .wrap-login100 {
            width: 100%;
            max-width: 350px;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
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

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
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
                <div class="text-center" style="font-size: 16px;">ยินดีต้อนรับเข้าสู่ <br>ระบบมอบหมายงานออนไลน์</div>
                <span class="login100-form-title">
                    KP Login
                </span>

                <?php
                // ตัวแปรเพื่อแสดงข้อความแจ้งเตือน
                $login_error = '';

                if (isset($_POST['submit'])) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    // ตรวจสอบว่า username มีอยู่ใน tb_admin หรือไม่
                    $sql_check_username = "SELECT * FROM tb_admin WHERE admin_username='$username'";
                    $result_check_username = $cls_conn->select_base($sql_check_username);

                    if (mysqli_num_rows($result_check_username) >= 1) {
                        $sql = "SELECT * FROM tb_admin WHERE admin_username='$username' AND admin_password='$password'";
                        $result = $cls_conn->select_base($sql);

                        if (mysqli_num_rows($result) >= 1) {
                            $row = mysqli_fetch_assoc($result);
                            $_SESSION['user'] = $row;
                            $_SESSION['role'] = 'admin';
                            // echo $cls_conn->show_message('Login Success');
                            echo $cls_conn->goto_page(1, 'backend/admin/index.php');
                        } else {
                            $login_error = 'รหัสผ่านไม่ถูกต้อง';
                        }
                    } else {
                        // ตรวจสอบในตาราง tb_member
                        $sql_check_username2 = "SELECT * FROM tb_member WHERE member_username='$username'";
                        $result_check_username2 = $cls_conn->select_base($sql_check_username2);

                        if (mysqli_num_rows($result_check_username2) >= 1) {
                            $sql2 = "SELECT * FROM tb_member WHERE member_username='$username' AND member_password='$password'";
                            $result2 = $cls_conn->select_base($sql2);

                            if (mysqli_num_rows($result2) >= 1) {
                                $row2 = mysqli_fetch_assoc($result2);
                                $_SESSION['user'] = $row2;
                                $_SESSION['role'] = 'member';
                                // echo $cls_conn->show_message('Login Success');
                                echo $cls_conn->goto_page(1, 'frontend/member/index.php');
                            } else {
                                $login_error = 'รหัสผ่านไม่ถูกต้อง';
                            }
                        } else {
                            // ตรวจสอบในตาราง tb_teacher
                            $sql_check_username3 = "SELECT * FROM tb_teacher WHERE teacher_username='$username'";
                            $result_check_username3 = $cls_conn->select_base($sql_check_username3);

                            if (mysqli_num_rows($result_check_username3) >= 1) {
                                $sql3 = "SELECT * FROM tb_teacher WHERE teacher_username='$username' AND teacher_password='$password'";
                                $result3 = $cls_conn->select_base($sql3);

                                if (mysqli_num_rows($result3) >= 1) {
                                    $row3 = mysqli_fetch_assoc($result3);
                                    $_SESSION['user'] = $row3;
                                    $_SESSION['role'] = 'teacher';
                                    // echo $cls_conn->show_message('Login Success');
                                    echo $cls_conn->goto_page(1, 'backend/teacher/index.php');
                                } else {
                                    $login_error = 'รหัสผ่านไม่ถูกต้อง';
                                }
                            } else {
                                // ไม่มีชื่อผู้ใช้ในระบบ
                                $login_error = 'ชื่อผู้ใช้ไม่ถูกต้อง';
                            }
                        }
                    }
                }
                ?>

                <!-- แสดงข้อความแจ้งเตือน -->
                <?php if (!empty($login_error)) { ?>
                    <p class="error-message"><?php echo $login_error; ?></p>
                <?php } ?>

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="username" placeholder="Username" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password" required id="password-field">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                    <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                </div>


                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" name="submit">
                        เข้าสู่ระบบ
                    </button>
                </div>

                <div class="container-login100-form-btn">
                    <a href="index.php" class="w3-container w3-center" name="backhome">
                        กลับไปยังหน้าหลัก
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="template_login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="template_login/vendor/bootstrap/js/popper.js"></script>
    <script src="template_login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="template_login/vendor/select2/select2.min.js"></script>
    <script src="template_login/vendor/tilt/tilt.jquery.min.js"></script>
    <script src="template_login/js/main.js"></script>
    <script>
        $('.toggle-password').on('click', function() {
            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $($(this).attr('toggle'));
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
    </script>

    <?php include('footer.php'); ?>

</body>

</html>