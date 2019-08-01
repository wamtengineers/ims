<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["visitor_edit"])){
	extract($_POST);
	$err="";
	if(empty($name) || empty($id_card_number))
		$err="Fields with (*) are Mandatory.<br />";
	if(numrows(doquery("select id from visitor where id_card_number='".slash($id_card_number)."' and id<>'".$id."'", $dblink))>0)
		$err.='Id Card Number already exists.<br />';
	if($err==""){
		$sql="Update visitor set `name`='".slash($name)."', `id_card_number`='".slash($id_card_number)."', `checked_in`='".slash(datetime_dbconvert(unslash($checked_in)))."', `checked_out`='".slash(datetime_dbconvert(unslash($checked_out)))."' "." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["visitor_manage"]["edit"]);
		header('Location: visitor_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["visitor_manage"]["edit"][$key]=$value;
		header("Location: visitor_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from visitor where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$checked_in=datetime_convert($checked_in);
			$checked_out=datetime_convert($checked_out);
		if(isset($_SESSION["visitor_manage"]["edit"]))
			extract($_SESSION["visitor_manage"]["edit"]);
	}
	else{
		header("Location: visitor_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: visitor_manage.php?tab=list");
	die;
}