<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from `examination` where id='".slash($_GET["id"])."'",$dblink);
	header("Location: examination_manage.php");
	die;
}