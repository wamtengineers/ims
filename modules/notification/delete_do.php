<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from notification_2_class_section where notification_id='".$_GET["id"]."'",$dblink);
	doquery("delete from notification_2_department where notification_id='".$_GET["id"]."'",$dblink);
	doquery("delete from notification where id='".slash($_GET["id"])."'",$dblink);
	header("Location: notification_manage.php");
	die;
}