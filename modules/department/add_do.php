<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["department_add"])){
	extract($_POST);
	$err="";
	if(empty($title) || empty($department_code))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO department (school_id, title, department_code) VALUES ('".$_SESSION["current_school_id"]."', '".slash($title)."','".slash($department_code)."')";
		doquery($sql,$dblink);
		unset($_SESSION["department_manage"]["add"]);
		header('Location: department_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["department_manage"]["add"][$key]=$value;
		header('Location: department_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}