<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["board_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO board (title) VALUES ('".slash($title)."')";
		doquery($sql,$dblink);
		unset($_SESSION["board_manage"]["add"]);
		header('Location: board_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["board_manage"]["add"][$key]=$value;
		header('Location: board_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}