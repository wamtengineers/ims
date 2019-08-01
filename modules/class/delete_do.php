<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id = slash( $_GET[ "id" ] );
	doquery("delete from fees_chalan_receiving where fees_chalan_id in ( select id from fees_chalan where student_2_class_id in ( select id from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."')))",$dblink);
	doquery("delete from fees_chalan_details where fees_chalan_id in ( select id from fees_chalan where student_2_class_id in ( select id from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."')))",$dblink);
	doquery("delete from fees_chalan where student_2_class_id in ( select id from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."'))",$dblink);
	doquery("delete from on_demand_fees_classes where class_section_id in ( select id from class_section where class_id='".$id."')",$dblink);
	doquery("delete from student_daily_attendance where class_section_id in ( select id from class_section where class_id='".$id."')",$dblink);
	doquery("delete from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."')",$dblink);
	doquery("delete from class_section where class_id='".$id."'",$dblink);
	doquery("delete from class_fees where class_id='".$id."'",$dblink);
	doquery("delete from class where id='".$id."'",$dblink);
	header("Location: class_manage.php");
	die;
}