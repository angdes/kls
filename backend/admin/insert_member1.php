<?php include('header.php'); ?>

<style>
    .btn-m {
        color: white;
        background-color: #FF00FF;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-m:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-d {
        color: white;
        background-color: #808080;
        border: 2px solid #E0E0E0;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .btn-d:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="right_col" role="main">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="x_title">
                    <h2>เพิ่มข้อมูลนักเรียนแบบไฟล์แนบ</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
        
                    <!-- ปุ่มสร้างไฟล์ Excel -->
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_profile_pic">สร้างการเพิ่มข้อมูลจากไฟล์<span class="required">:</span> </label>

                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" class="btn btn-m" onclick="window.location.href='generate_excel.php'">สร้างไฟล์ Excel</button>
                        </div>
                    </div>
                    <br>
                    <!-- ปุ่มเพิ่มข้อมูลจากไฟล์ Excel -->
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_profile_pic">รองรับการเพิ่มข้อมูลจากไฟล์<span class="required">:</span> </label>

                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button onclick="window.location.href='upload_excel.php'" class="btn btn-d">เพิ่มข้อมูลจากไฟล์ Excel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('footer.php'); ?>