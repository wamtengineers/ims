<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["class_add"])){
	extract($_POST);
	$err="";
	if(empty($class_level_id) || empty($class_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO class (school_id, class_level_id, class_name, sortorder) VALUES ('".$_SESSION["current_school_id"]."', '".slash($class_level_id)."', '".slash($class_name)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		unset($_SESSION["class_manage"]["add"]);
		header('Location: class_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["class_manage"]["add"][$key]=$value;
		header('Location: class_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}