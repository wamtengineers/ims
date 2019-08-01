<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["examination_add"])){
	extract($_POST);
	$err="";
	if(empty($examination_type_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO `examination` (school_id, examination_type_id, academic_year_id, start_date, result_date) VALUES ('".$_SESSION["current_school_id"]."', '".slash($examination_type_id)."', '".slash($academic_year_id)."', '".slash(date_dbconvert($start_date))."', '".slash(date_dbconvert($result_date))."')";
		doquery($sql,$dblink);
		unset($_SESSION["examination_manage"]["add"]);
		header('Location: examination_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["examination_manage"]["add"][$key]=$value;
		header('Location: examination_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}