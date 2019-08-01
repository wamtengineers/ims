<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["profile_edit"])){
	extract($_POST);
	$err="";
	if(empty($name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update parents set `name`='".slash($name)."'".(!empty($password)? ", `password`='".slash($password)."'":"")." where id='".$_SESSION["logged_in_parents"]["id"]."'";
		doquery($sql,$dblink);
		unset($_SESSION["profile"]["edit"]);
		header('Location: profile.php?tab=profile_edit&msg='.url_encode("Successfully Updated")."&id='".$_SESSION["logged_in_parents"]["id"]."'");
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["profile"]["edit"][$key]=$value;
		header("Location: profile.php?tab=profile_edit&err=".url_encode($err)."&id='".$_SESSION["logged_in_parents"]["id"]."'");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from parents where id='".$_SESSION["logged_in_parents"]["id"]."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["profile"]["edit"]))
			extract($_SESSION["profile"]["edit"]);
	}
	else{
		header('Location: profile.php?tab=profile_edit&msg='.url_encode("Successfully Updated")."&id=".$_SESSION["logged_in_parents"]["id"]."");
		die;
	}
}
else{
	header("Location: profile.php?id=".$_SESSION["logged_in_parents"]["id"]."");
	die;
}