<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id = slash( $_GET[ "id" ] );
	doquery("delete from subject_2_group where subject_id='".$id."'",$dblink);
	doquery("delete from subject where id='".$id."'",$dblink);
	header("Location: subject_manage.php");
	die;
}