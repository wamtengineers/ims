<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["department_edit"])){
	extract($_POST);
	$err="";
	if(empty($title) || empty($department_code))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update department set `title`='".slash($title)."',`department_code`='".slash($department_code)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["department_manage"]["edit"]);
		header('Location: department_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["department_manage"]["edit"][$key]=$value;
		header("Location: department_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from department where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["department_manage"]["edit"]))
			extract($_SESSION["department_manage"]["edit"]);
	}
	else{
		header("Location: department_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: department_manage.php?tab=list");
	die;
}