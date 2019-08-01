<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["class_level_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO class_level (title) VALUES ('".slash($title)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		sorttable("class_level",$id,$sortorder,"add");
		unset($_SESSION["class_level_manage"]["add"]);
		header('Location: class_level_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["class_level_manage"]["add"][$key]=$value;
		header('Location: class_level_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}