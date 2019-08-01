<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["fees_edit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update fees set `title`='".slash($title)."',`has_discount`='".slash($has_discount)."',`type`='".slash($type)."'".", selected_students='".slash($selected_students)."', sortorder='".slash($sortorder)."' where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["fees_manage"]["edit"]);
		header('Location: fees_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["fees_manage"]["edit"][$key]=$value;
		header("Location: fees_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from fees where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["fees_manage"]["edit"]))
			extract($_SESSION["fees_manage"]["edit"]);
	}
	else{
		header("Location: fees_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: fees_manage.php?tab=list");
	die;
}