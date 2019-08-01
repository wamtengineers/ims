<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from employee_attendance where employee_id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from employee_meta where employee_id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from salary where employee_id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from salary_payment where employee_id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from employee where id='".slash($_GET["id"])."'",$dblink);
	header("Location: employee_manage.php");
	die;
}