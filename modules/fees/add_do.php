<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["fees_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO fees (school_id, title, has_discount, type, selected_students, sortorder) VALUES ('".$_SESSION["current_school_id"]."', '".slash($title)."', '".slash($has_discount)."', '".slash($type)."', '".slash($selected_students)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		unset($_SESSION["fees_manage"]["add"]);
		header('Location: fees_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["fees_manage"]["add"][$key]=$value;
		header('Location: fees_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}