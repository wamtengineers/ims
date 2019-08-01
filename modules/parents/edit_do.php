<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["parents_edit"])){
	extract($_POST);
	$err="";
	if(empty($name) || empty($mobile_number))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update parents set `name`='".slash($name)."', `mobile_number`='".slash($mobile_number)."'".(!empty($password)? ", `password`='".slash($password)."'":"")." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["parents_manage"]["edit"]);
		header('Location: parents_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["parents_manage"]["edit"][$key]=$value;
		header("Location: parents_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from parents where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["parents_manage"]["edit"]))
			extract($_SESSION["parents_manage"]["edit"]);
	}
	else{
		header("Location: parents_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: parents_manage.php?tab=list");
	die;
}