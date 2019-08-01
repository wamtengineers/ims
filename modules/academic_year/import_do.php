<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["academic_year_import_do"])){
	extract($_POST);
	$err="";
	if( empty($import_school_id) )
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$student_lists = json_decode( stripslashes( file_get_contents( "http://ims.aneeshassan.edu.pk/import_student.php?school_id=".$import_school_id )));
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["academic_year_manage"]["edit"][$key]=$value;
		header("Location: academic_year_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
else{
	$import_school_id = "";
}