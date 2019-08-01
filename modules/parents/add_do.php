<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["parents_add"])){
	extract($_POST);
	$err="";
	if(empty($name) || empty($mobile_number) || empty($password))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO parents (school_id, name, mobile_number, password) VALUES ('".$_SESSION["current_school_id"]."', '".slash($name)."', '".slash($mobile_number)."', '".slash($password)."')";
		doquery($sql,$dblink);
		unset($_SESSION["parents_manage"]["add"]);
		header('Location: parents_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["parents_manage"]["add"][$key]=$value;
		header('Location: parents_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}