<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["class_edit"])){
	extract($_POST);
	$err="";
	if(empty($class_level_id) || empty($class_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update class set class_level_id='".slash($class_level_id)."', `class_name`='".slash($class_name)."', `sortorder`='".slash($sortorder)."'" ." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["class_manage"]["edit"]);
		header('Location: class_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["class_manage"]["edit"][$key]=$value;
		header("Location: class_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from class where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["class_manage"]["edit"]))
			extract($_SESSION["class_manage"]["edit"]);
	}
	else{
		header("Location: class_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: class_manage.php?tab=list");
	die;
}