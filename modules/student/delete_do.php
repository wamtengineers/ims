<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id = slash( $_GET[ "id" ] );
	doquery("delete from fees_chalan_details where fees_chalan_id in ( select id from fees_chalan where student_2_class_id in ( select id from student_2_class where student_id='".$id."'))",$dblink);
	doquery("delete from fees_chalan_receiving where fees_chalan_id in ( select id from fees_chalan where student_2_class_id in ( select id from student_2_class where student_id='".$id."'))",$dblink);
	doquery("delete from fees_chalan where student_2_class_id in ( select id from student_2_class where student_id='".$id."')",$dblink);
	doquery("delete from student_2_class where student_id='".$id."'",$dblink);
	doquery("delete from on_demand_fees_student where student_id='".$id."'",$dblink);
	doquery("delete from student_academic_year_balance where student_id='".$id."'",$dblink);
	doquery("delete from student_attendance where student_id='".$id."'",$dblink);
	doquery("delete from student_meta where student_id='".$id."'",$dblink);
	doquery("delete from student where id='".$id."'",$dblink);
	header("Location: student_manage.php");
	die;
}