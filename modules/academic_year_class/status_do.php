<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("update academic_year_class set status='".slash($_GET["s"])."' where id='".slash($_GET["id"])."' and academic_year_id='".$parent_academic_year_id."'",$dblink);
	header("Location: academic_year_class_manage.php");
	die;
}