<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from class_fees where fees_id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from student_meta where meta_key='fees_".slash($_GET["id"])."'",$dblink);
	doquery("delete from fees where id='".slash($_GET["id"])."'",$dblink);
	header("Location: fees_manage.php");
	die;
}