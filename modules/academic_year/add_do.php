<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["academic_year_add"])){
	extract($_POST);
	$err="";
	if(empty($title) || empty($start_date) || empty($end_date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO academic_year (school_id, title, start_date, end_date) VALUES ('".$_SESSION["current_school_id"]."', '".slash($title)."','".slash(date_dbconvert($start_date))."','".slash(date_dbconvert($end_date))."')";
		doquery($sql,$dblink);
		unset($_SESSION["academic_year_manage"]["add"]);
		header('Location: academic_year_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["academic_year_manage"]["add"][$key]=$value;
		header('Location: academic_year_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}