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
                            $sql2.=" member_email='$username'";
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