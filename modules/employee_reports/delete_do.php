<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from employee_attendance where id='".slash($_GET["id"])."'",$dblink);
	header("Location: employee_reports_manage.php");
	die;
}