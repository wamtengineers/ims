<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["examination_edit"])){
	extract($_POST);
	$err="";
	if(empty($examination_type_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update `examination` set `examination_type_id`='".slash($examination_type_id)."', `academic_year_id`='".slash($academic_year_id)."', `start_date`='".slash(date_dbconvert($start_date))."', `result_date`='".slash(date_dbconvert($result_date))."'" ." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["examination_manage"]["edit"]);
		header('Location: examination_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["examination_manage"]["edit"][$key]=$value;
		header("Location: examination_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from `examination` where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$start_date=date_convert($start_date);
			$result_date=date_convert($result_date);
		if(isset($_SESSION["examination_manage"]["edit"]))
			extract($_SESSION["examination_manage"]["edit"]);
	}
	else{
		header("Location: examination_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: examination_manage.php?tab=list");
	die;
}