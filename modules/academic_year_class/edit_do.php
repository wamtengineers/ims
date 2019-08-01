<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["academic_year_class_edit"])){
	extract($_POST);
	$err="";
	if(empty($class_section_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update academic_year_class set `class_section_id`='".slash($class_section_id)."', `start_date`='".slash(date_dbconvert(unslash(($start_date))))."', `end_date`='".slash(date_dbconvert(unslash(($end_date))))."' "." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["academic_year_class_manage"]["edit"]);
		header('Location: academic_year_class_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["academic_year_class_manage"]["edit"][$key]=$value;
		header("Location: academic_year_class_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from academic_year_class where id='".slash($_GET["id"])."' and academic_year_id='".$parent_academic_year_id."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$start_date = date_convert($start_date);
			$end_date = date_convert($end_date);
		if(isset($_SESSION["academic_year_class_manage"]["edit"]))
			extract($_SESSION["academic_year_class_manage"]["edit"]);
	}
	else{
		header("Location: academic_year_class_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: academic_year_class_manage.php?tab=list");
	die;
}