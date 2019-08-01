<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["group_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO `group` (school_id, title) VALUES ('".$_SESSION["current_school_id"]."', '".slash($title)."')";
		doquery($sql,$dblink);
		unset($_SESSION["group_manage"]["add"]);
		header('Location: group_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["group_manage"]["add"][$key]=$value;
		header('Location: group_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}