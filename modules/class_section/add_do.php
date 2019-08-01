<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["class_section_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO class_section (school_id, class_id, title, board_id, group_id) VALUES ('".$_SESSION["current_school_id"]."', '".slash($class_id)."','".slash($title)."','".slash($board_id)."','".slash($group_id)."')";
		doquery($sql,$dblink);
		unset($_SESSION["class_section_manage"]["add"]);
		header('Location: class_section_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["class_section_manage"]["add"][$key]=$value;
		header('Location: class_section_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}