<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["school_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO school (title) VALUES ('".slash($title)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		sorttable("school",$id,$sortorder,"add");
		unset($_SESSION["school_manage"]["add"]);
		header('Location: school_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["school_manage"]["add"][$key]=$value;
		header('Location: school_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}