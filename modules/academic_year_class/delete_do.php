<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from academic_year_class where id='".slash($_GET["id"])."' and academic_year_id='".$parent_academic_year_id."'",$dblink);
	header("Location: academic_year_class_manage.php");
	die;
}