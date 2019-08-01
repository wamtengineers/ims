<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from class_level where id='".slash($_GET["id"])."'",$dblink);
	header("Location: class_level_manage.php");
	die;
}