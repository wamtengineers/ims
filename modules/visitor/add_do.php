<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["visitor_add"])){
	extract($_POST);
	$err="";
	if(empty($name) || empty($id_card_number))
		$err="Fields with (*) are Mandatory.<br />";
	if(numrows(doquery("select id from visitor where id_card_number='".slash($id_card_number)."'", $dblink))>0)
		$err.='Id Card Number already exists.<br />';
	if($err==""){
		$sql="INSERT INTO visitor (school_id, name, id_card_number, checked_in, checked_out) VALUES ('".$_SESSION["current_school_id"]."', '".slash($name)."','".slash($id_card_number)."','".slash(datetime_dbconvert($checked_in))."','".slash(datetime_dbconvert($checked_out))."')";
		doquery($sql,$dblink);
		unset($_SESSION["visitor_manage"]["add"]);
		header('Location: visitor_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["visitor_manage"]["add"][$key]=$value;
		header('Location: visitor_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}