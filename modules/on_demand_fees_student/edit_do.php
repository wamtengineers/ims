<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["on_demand_fees_student_edit"])){
	extract($_POST);
	$err="";
	if(empty($student_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update on_demand_fees_student set `student_id`='".slash($student_id)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["on_demand_fees_student_manage"]["edit"]);
		header('Location: on_demand_fees_student_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["on_demand_fees_student_manage"]["edit"][$key]=$value;
		header("Location: on_demand_fees_student_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from on_demand_fees_student where id='".slash($_GET["id"])."' and on_demand_fees_id='".$parent_on_demand_fees_id."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["on_demand_fees_student_manage"]["edit"]))
			extract($_SESSION["on_demand_fees_student_manage"]["edit"]);
	}
	else{
		header("Location: on_demand_fees_student_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: on_demand_fees_student_manage.php?tab=list");
	die;
}