<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["houses_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO `houses` (title) VALUES ('".slash($title)."')";
		doquery($sql,$dblink);
		unset($_SESSION["houses_manage"]["add"]);
		header('Location: houses_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["houses_manage"]["add"][$key]=$value;
		header('Location: houses_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}