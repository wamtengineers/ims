<?php 
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
$page="index";
if( isset( $_GET[ "sendsms" ] ) ) {
	sendsms( $_GET[ "sendsms" ], "Test" );
	die;
}
if(isset($_POST["entry_number"])){
	$entry_number=slash($_POST["entry_number"]);
	$student=doquery("select id, student_name from student where student_registration_number='".$entry_number."' and status=1", $dblink);
	$status="";
	$msg="Records not found";
	if(numrows($student)>0){
		$student=dofetch($student);
		$check=doquery("select id from student_attendance where student_id='".$student["id"]."' and checked_in>='".date("Y-m-d 00:00:00")."' and checked_out='".date("0000-00-00 00:00:00")."' order by checked_in desc limit 0,1", $dblink);
		$stutus=1;
		if(numrows($check)>0){
			$check=dofetch($check);
			$time=time();
			doquery("update student_attendance set checked_out='".date("Y-m-d H:i:s", $time)."' where id='".$check["id"]."'", $dblink);
			$sms=str_replace(array(
				"%parent_name%",
				"%student_name%",
				"%check_in_time%"
			), array(
				unslash($student["student_name"]),
				unslash($student["parent_name"]),
				date("Y-m-d H:i:s", $time)
			), get_config("sms_parent_checkin_text"));
			$msg="Checked Out";
		}
		else{
			$time=time();
			doquery("insert into student_attendance (student_id, checked_in) values('".$student["id"]."', '".date("Y-m-d H:i:s", $time)."')", $dblink);
			/*$sms=str_replace(array(
				"%parent_name%",
				"%student_name%",
				"%check_in_time%"
			), array(
				unslash($student["student_name"]),
				unslash($student["parent_name"]),
				date("Y-m-d H:i:s", $time)
			), get_config("sms_parent_checkout_text"));*/
			$msg="Checked In";
		}
		//sendsms(unslash($student["sms_no"]), $sms);
	}
	echo json_encode(array("status"=>$status, "msg"=>$msg));
	die;
}
if(isset($_POST["entry_number_2"])){
	$entry_number=slash($_POST["entry_number_2"]);
	$employee=doquery("select id from employee where employee_code='".$entry_number."' and status=1 and school_id = '".$_SESSION["current_school_id"]."'", $dblink);
	$status=0;
	$msg="Records not found";
	if(numrows($employee)>0){
		$employee=dofetch($employee);
		$check=doquery("select id, checked_in, checked_out from employee_attendance where employee_id='".$employee["id"]."' and checked_in>='".date("Y-m-d 00:00:00")."' and checked_in<'".date("Y-m-d 00:00:00", time()+24*3600)."' order by checked_in desc limit 0,1", $dblink);
		if( isset($_POST["type"]) && $_POST["type"]=='out' ){
			if(numrows($check)>0){
				$check=dofetch($check);
				if( is_null( $check[ "checked_out" ] ) ){
					$stutus=1;
					doquery("update employee_attendance set checked_out='".date("Y-m-d H:i:s")."' where id='".$check["id"]."'", $dblink);
					$msg="Checked Out";
				}
				else {
					$msg="Already Checked Out";
				}
			}
			else{
				$check=doquery("select id from employee_attendance where employee_id='".$employee["id"]."' and checked_out>='".date("Y-m-d 00:00:00")."' and checked_out<'".date("Y-m-d 00:00:00", time()+24*3600)."' order by checked_out desc limit 0,1", $dblink);
				if(numrows($check)==0){
					$stutus=1;
					doquery("insert into employee_attendance (employee_id, checked_out) values('".$employee["id"]."', '".date("Y-m-d H:i:s")."')", $dblink);
					$msg="Checked Out";
				}
				else {
					$msg="Already Checked Out";
				}
			}
		}
		else {
			if(numrows($check)==0){
				$stutus=1;
				doquery("insert into employee_attendance (employee_id, checked_in) values('".$employee["id"]."', '".date("Y-m-d H:i:s")."')", $dblink);
				$msg="Checked In";
			}
			else{
				$msg="Already Checked in";
			}
		}
	}
	echo json_encode(array("status"=>$status, "msg"=>$msg));
	die;
}
?>
<?php include("inc/header.php");?>	
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="<?php echo $site_url?>"><?php echo $site_title?></a>
                    </li>
                    <li class="active">Dashboard</li>
                    <li> <?php if( $_SESSION[ "logged_in_admin" ][ "school_id" ] == 0 ) {?><a href="school.php">School</a><?php }?></li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        Dashboard
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                            Welcome to <?php echo $site_title?>.
                        </small> 
                    </h1>
                </div><!-- /.page-header -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="space-6"></div>
                            <div class="col-md-6 col-xs-12">
                            <?php
							$res=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id left join menu_2_school c on a.id = c.menu_id where parent_id=0 and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' and ( school_id='".($_SESSION[ "current_school_id" ])."' or school_id is null ) order by sortorder",$dblink);
							if(numrows($res)>0){
								while($rec=dofetch($res)){
									$res1=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id left join menu_2_school c on a.id = c.menu_id where parent_id='".$rec["id"]."' and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' and ( school_id='".($_SESSION[ "current_school_id" ])."' or school_id is null ) order by sortorder",$dblink);
									if(numrows($res1)>0){
                                    ?>
									<h4 class="blue bottom-20 bg-head"><?php echo $rec["title"]?></h4>
                                         <div class="clearfix bottom-30">
                                             <div class="row">
                                                <?php
                                                while($rec1=dofetch($res1)){
                                                    ?>
                                                    <a class="col-md-4 col-xs-6" href="<?php echo $rec1["url"]?>">
                                                        <div class="center infobox-blue infobox-dark">
                                                            <div class="infobox-chart">
                                                                <span class="project-icon"><img width="40px" height="40px" title="<?php echo $rec1['title'];?>" alt="Menu Icon" src="<?php echo $file_upload_url?>menu/<?php echo $rec1["icon"]?>"></span>
                                                            </div>
                                                            <div class="infobox-data">
                                                                <div class="infobox-content"><?php echo $rec1["title"];?></div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                             </div>
                                         </div>	
                                    	<?php
                    				}
                				}
            				}
            				?>
                            </div>
                            <div class="col-md-6 col-xs-12">
                            	<h4 class="blue bottom-20 bg-head">Date: <?php echo date("d/m/y");?></h4>
                            	<div class="clock" style="margin:0.5em 0;"></div>
								<div class="message"></div>
                            	<!--<div class="infobox infobox-blue2 custom-percent bottom-20">
                                	<h4 class="blue bottom-20 bg-head">Student Entry Form</h4>
                                    <div class="entry_form">
                                    	<form method="post" id="entry_form">
                                        	<div class="result"></div>
	                                    	<input type="text" name="entry_number" class="" />
    	                                    <input type="submit" value="Checked IN" class="btn btn-sm btn-primary" />
                                            <input type="submit" value="Checked Out" class="btn btn-sm btn-primary" />
                                      	</form>
                                    </div>
                                </div>-->
                                <div class="infobox infobox-blue2 custom-percent bottom-20">
                                	<h4 class="blue bottom-20 bg-head">Employee Entry Form</h4>
                                    <div class="entry_form">
                                    	<form method="post" id="entry_form_2">
                                        	<div class="result"></div>
	                                    	<input type="text" name="entry_number_2" />
    	                                    <input type="submit" id="employee_check_in" value="Checked IN" class="btn btn-sm btn-primary" />
                                            <input type="submit" id="employee_check_out" value="Checked Out" class="btn btn-sm btn-danger" />
                                            
                                      	</form>
                                    </div>
                                </div>
								<?php
                                $total_students=get_total_active_students();
								$rs=dofetch(doquery("select count(1) from student_attendance where checked_in>='".date("Y-m-d H:i:s")."'", $dblink));
								$present=$rs["count(1)"];
								if($total_students>0){
									$percentage=round($present/$total_students);
								}
								else{
									$percentage=0;
								}
								?>
                                <div class="infobox infobox-blue2 custom-percent bottom-20">
                                    <div class="infobox-data col-md-9">
                                        <span class="infobox-text">Student Attendance</span>

                                        <div class="infobox-content">
                                            <span class="bigger-110">~</span>
                                            <?php echo $total_students;?> Students
                                        </div>
                                    </div>
                                    <div class="infobox-progress col-md-3">
                                        <div class="easy-pie-chart percentage" data-percent="<?php echo $percentage;?>" data-size="50">
                                            <span class="percent"><?php echo $percentage;?></span>%
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $total_employees=get_total_active_employees();
								$rs=dofetch(doquery("select count(1) from employee_attendance where checked_in>='".date("Y-m-d H:i:s")."'", $dblink));
								$present=$rs["count(1)"];
								if($total_employees>0){
									$percentage=round($present/$total_employees);
								}
								else{
									$percentage=0;
								}
								?>
                                <div class="infobox infobox-blue2 custom-percent bottom-20">
                                    <div class="infobox-data col-md-9">
                                        <span class="infobox-text">Employees Attendance</span>

                                        <div class="infobox-content">
                                            <span class="bigger-110">~</span>
                                            <?php echo $total_employees;?> Employees
                                        </div>
                                    </div>
                                    <div class="infobox-progress col-md-3">
                                        <div class="easy-pie-chart percentage" data-percent="<?php echo $percentage;?>" data-size="50">
                                            <span class="percent"><?php echo $percentage;?></span>%
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $total_checked_in=dofetch(doquery("select count(1) from visitor where checked_in>='".date("Y-m-d H:i:s")."'", $dblink));
								$total_checked_out=dofetch(doquery("select count(1) from visitor where checked_in>='".date("Y-m-d H:i:s")."' and checked_out>='".date("Y-m-d H:i:s")."'", $dblink));
								if($total_checked_in["count(1)"]>0){
									$percentage=round($total_checked_out["count(1)"]/$total_checked_in["count(1)"]);
								}
								else{
									$percentage=0;
								}
								$remaining=$total_checked_in["count(1)"]-$total_checked_out["count(1)"];
								?>
                                <div class="infobox infobox-blue2 custom-percent bottom-20">
                                    <div class="infobox-data col-md-9">
                                        <span class="infobox-text">Visitors Checked In</span>

                                        <div class="infobox-content">
                                            <span class="bigger-110">~</span>
                                            Total Checked In <?php echo $total_checked_in["count(1)"]?>
                                        </div>
                                    </div>
                                    <div class="infobox-progress col-md-3">
                                        <div class="easy-pie-chart percentage" data-percent="<?php echo $percentage;?>" data-size="50">
                                            <span class="percent"><?php echo $remaining;?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-20"></div>
                                <div class="widget-body">
                                	<h4 class="blue bottom-20 bg-head">
                                        <i class="ace-icon fa fa-signal"></i>
                                        Today's Stats
                                    </h4>
                                    <div class="widget-main padding-4">
                                        <div id="sales-charts"></div>
                                    </div><!-- /.widget-main -->
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <?php include("inc/footer.php");?>	