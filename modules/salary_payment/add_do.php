<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["salary_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($employee_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO salary_payment (school_id, employee_id, datetime_added, amount, account_id, details) VALUES ('".$_SESSION["current_school_id"]."', '".slash($employee_id)."', '".slash(datetime_dbconvert($datetime_added))."', '".slash($amount)."', '".slash($account_id)."', '".slash($details)."')";
		doquery($sql,$dblink);
		unset($_SESSION["salary_payment_manage"]["add"]);
		header('Location: salary_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["salary_payment_manage"]["add"][$key]=$value;
		header('Location: salary_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}