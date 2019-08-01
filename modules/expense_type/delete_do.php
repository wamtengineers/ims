<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from expense_type where id='".slash($_GET["id"])."'",$dblink);
	header("Location: expense_type_manage.php");
	die;
}