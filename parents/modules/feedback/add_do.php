<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["feedback_add"])){
	extract($_POST);
	$err="";
	if(empty($message))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO feedback (parent_id, date, message) VALUES ('".$_SESSION["logged_in_parents"]["id"]."', NOW(), '".slash($message)."')";
		doquery($sql,$dblink);
		unset($_SESSION["feedback_manage"]["add"]);
		header('Location: feedback_manage.php?tab=list&msg='.url_encode("Sucessfully Send"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["feedback_manage"]["add"][$key]=$value;
		header('Location: feedback_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}