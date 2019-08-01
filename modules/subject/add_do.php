<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["subject_add"])){
	extract($_POST);
	$err="";
	if(empty($class_id) || empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO subject (school_id, board_id, class_id, title, is_optional, color, sortorder) VALUES ('".$_SESSION["current_school_id"]."', '".slash($board_id)."', '".slash($class_id)."', '".slash($title)."', '".slash($is_optional)."', '".slash($color)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		if( isset( $group_ids ) && count( $group_ids ) > 0 ) {
			foreach( $group_ids as $group_id ) {
				doquery( "insert into subject_2_group(subject_id, group_id) values( '".$id."', '".$group_id."' )", $dblink );
			}
		}
		unset($_SESSION["subject_manage"]["add"]);
		header('Location: subject_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["subject_manage"]["add"][$key]=$value;
		header('Location: subject_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}