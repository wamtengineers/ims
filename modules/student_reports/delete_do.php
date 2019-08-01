<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from student_attendance where id='".slash($_GET["id"])."'",$dblink);
	header("Location: student_reports_manage.php");
	die;
}