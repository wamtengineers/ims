<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["school_edit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update school set `title`='".slash($title)."'" ." where id='".$id."'";
		doquery($sql,$dblink);
		sorttable("school",$id,$sortorder,"edit");
		unset($_SESSION["school_manage"]["edit"]);
		header('Location: school_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["school_manage"]["edit"][$key]=$value;
		header("Location: school_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from school where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["school_manage"]["edit"]))
			extract($_SESSION["school_manage"]["edit"]);
	}
	else{
		header("Location: school_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: school_manage.php?tab=list");
	die;
}