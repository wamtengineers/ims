<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["on_demand_fees_student_add"])){
	extract($_POST);
	$err="";
	if(empty($student_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO on_demand_fees_student (on_demand_fees_id, student_id) VALUES ('".slash($parent_on_demand_fees_id)."','".slash($student_id)."')";
		doquery($sql,$dblink);
		$id = inserted_id();
		unset($_SESSION["on_demand_fees_student_manage"]["add"]);
		header('Location: on_demand_fees_student_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["on_demand_fees_student_manage"]["add"][$key]=$value;
		header('Location: on_demand_fees_student_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}