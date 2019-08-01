<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["academic_year_class_add"])){
	extract($_POST);
	$err="";
	if(empty($class_section_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO academic_year_class (academic_year_id, class_section_id, start_date, end_date) VALUES ('".slash($parent_academic_year_id)."','".slash($class_section_id)."','".slash(date_dbconvert($start_date))."','".slash(date_dbconvert($end_date))."')";
		doquery($sql,$dblink);
		unset($_SESSION["academic_year_class_manage"]["add"]);
		header('Location: academic_year_class_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["academic_year_class_manage"]["add"][$key]=$value;
		header('Location: academic_year_class_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}