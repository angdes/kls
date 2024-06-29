<?php include('header.php');?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>ลบข้อมูลผู้ใช้งาน</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                       
                        <?php
                        if(isset($_GET['id']))
                        {
                            $id=$_GET['id'];
                            $sql.=" delete from tb_user";
                            $sql.=" where";
                            $sql.=" user_id=$id";
                          
                            if($cls_conn->write_base($sql)==true)
                            {
                                echo $cls_conn->show_message('ลบข้อมูลสำเร็จ');
                                echo $cls_conn->goto_page(1,'show_user.php');
                            }
                            else
                            {
                                 echo $cls_conn->show_message('ลบข้อมูลไม่สำเร็จ');
                                 echo $sql;
                            }
                        }
                        
                        ?>
                        
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php');?>