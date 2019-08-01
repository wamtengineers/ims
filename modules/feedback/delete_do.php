<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id = slash( $_GET[ "id" ] );
	doquery("delete from feedback where id='".$id."'",$dblink);
	header("Location: feedback_manage.php");
	die;
}