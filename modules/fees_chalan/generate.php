<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["fees_chalan_add"])){
	extract($_POST);
	$err="";
	if(empty($student_2_class_id) || empty($academic_year_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO fees_chalan (school_id, student_2_class_id, academic_year_id, month, issue_date) VALUES ('".$_SESSION["current_school_id"]."', '".slash($student_2_class_id)."', '".slash($academic_year_id)."', '".slash($month)."', '".slash(date_dbconvert($issue_date))."')";
		doquery($sql,$dblink);
		unset($_SESSION["fees_chalan_manage"]["add"]);
		header('Location: fees_chalan_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["fees_chalan_manage"]["add"][$key]=$value;
		header('Location: fees_chalan_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}