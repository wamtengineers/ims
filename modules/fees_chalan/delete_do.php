<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from fees_chalan where id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from fees_chalan_details where fees_chalan_id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from fees_chalan_receiving where fees_chalan_id='".slash($_GET["id"])."'",$dblink);
	header("Location: fees_chalan_manage.php");
	die;
}