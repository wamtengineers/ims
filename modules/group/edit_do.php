<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["group_edit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update `group` set `title`='".slash($title)."'" ." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["group_manage"]["edit"]);
		header('Location: group_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["group_manage"]["edit"][$key]=$value;
		header("Location: group_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from `group` where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["group_manage"]["edit"]))
			extract($_SESSION["group_manage"]["edit"]);
	}
	else{
		header("Location: group_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: group_manage.php?tab=list");
	die;
}