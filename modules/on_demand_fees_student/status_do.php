<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("update on_demand_fees_student set status='1' where id='".slash($_GET["id"])."' and on_demand_fees_id='".$parent_on_demand_fees_id."'",$dblink);
	header("Location: on_demand_fees_student_manage.php");
	die;
}