<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["feedback_add"])){
	extract($_POST);
	$err="";
	if(empty($reply))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO feedback (school_id, parent_id, date, message, reply) VALUES ('".$_SESSION["current_school_id"]."', '".$parent_id."', NOW(), '".slash($message)."', '".slash($reply)."')";
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