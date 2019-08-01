<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["feedback_edit"])){
	extract($_POST);
	$err="";
	if(empty($reply))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update feedback set parent_id='".slash($parent_id)."', `date`='".slash(date_dbconvert($date))."', `reply`='".slash($reply)."'" ." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["feedback_manage"]["edit"]);
		header('Location: feedback_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["feedback_manage"]["edit"][$key]=$value;
		header("Location: feedback_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from feedback where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$date=date_convert($date);
		if(isset($_SESSION["feedback_manage"]["edit"]))
			extract($_SESSION["feedback_manage"]["edit"]);
	}
	else{
		header("Location: feedback_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: feedback_manage.php?tab=list");
	die;
}