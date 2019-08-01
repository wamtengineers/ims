<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["examination_type_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO `examination_type` (school_id, title, generate_marksheet) VALUES ('".$_SESSION["current_school_id"]."', '".slash($title)."', '".slash($generate_marksheet)."')";
		doquery($sql,$dblink);
		unset($_SESSION["examination_type_manage"]["add"]);
		header('Location: examination_type_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["examination_type_manage"]["add"][$key]=$value;
		header('Location: examination_type_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}