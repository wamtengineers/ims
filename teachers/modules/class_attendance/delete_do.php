<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$current_academic_year = current_academic_year();
	doquery("delete from student_daily_attendance where class_section_id='".slash($_GET["id"])."' and date='".date_dbconvert( $_GET[ "date" ] )."'",$dblink);
	doquery("delete from student_attendance where student_id in ( select student_id from student_2_class where class_section_id = '".slash($_GET["id"])."' and academic_year_id='".$current_academic_year[ "id" ]."' and status=1) and date='".date_dbconvert( $_GET[ "date" ] )."'",$dblink);
	header("Location: class_attendance_manage.php");
	die;
}