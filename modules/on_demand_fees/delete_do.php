<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from on_demand_fees_classes where on_demand_fees_id='".$_GET["id"]."'",$dblink);
	doquery("delete from on_demand_fees_student where on_demand_fees_id='".$_GET["id"]."'",$dblink);
	doquery("delete from on_demand_fees where id='".slash($_GET["id"])."'",$dblink);
	header("Location: on_demand_fees_manage.php");
	die;
}