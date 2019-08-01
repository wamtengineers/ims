<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("update academic_year set is_current_year=0",$dblink);
	doquery("update academic_year set is_current_year=1 where id ='".$_GET["id"]."'",$dblink);
	header("Location: academic_year_manage.php");
	die;
}