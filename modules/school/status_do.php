<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("update school set status='".slash($_GET["s"])."' where id='".slash($_GET["id"])."'",$dblink);
	header("Location: school_manage.php");
	die;
}