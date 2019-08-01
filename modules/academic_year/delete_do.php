<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id = slash($_GET["id"]);
	doquery("delete from fees_chalan_details where fees_chalan_id in ( select id from fees_chalan where academic_year_id='".$id."')",$dblink);
	doquery("delete from fees_chalan_receiving where fees_chalan_id in ( select id from fees_chalan where academic_year_id='".$id."')",$dblink);
	doquery("delete from fees_chalan where academic_year_id='".$id."'",$dblink);
	doquery("delete from on_demand_fees_classes where on_demand_fees_id in ( select id from on_demand_fees where academic_year_id='".$id."')",$dblink);
	doquery("delete from on_demand_fees_student where on_demand_fees_id in ( select id from on_demand_fees where academic_year_id='".$id."')",$dblink);
	doquery("delete from on_demand_fees where academic_year_id='".$id."'",$dblink);
	doquery("delete from student_2_class where academic_year_id='".$id."'",$dblink);
	doquery("delete from student_academic_year_balance where academic_year_id='".$id."'",$dblink);
	doquery("delete from academic_year where id='".$id."'",$dblink);
	header("Location: academic_year_manage.php");
	die;
}