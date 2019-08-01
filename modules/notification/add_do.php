<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["notification_add"])){
	extract($_POST);
	$err="";
	if($target == "" || empty($date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO notification (school_id, target, date, title, text) VALUES ('".$_SESSION["current_school_id"]."', '".slash($target)."', '".slash(date_dbconvert($date))."', '".slash($title)."', '".slash($text)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		if( $target == 0 && isset( $class_section_ids ) && count( $class_section_ids ) > 0 ) {
			foreach( $class_section_ids as $class_section_id ) {
				doquery( "insert into notification_2_class_section(notification_id, class_section_id) values( '".$id."', '".$class_section_id."' )", $dblink );
			}
		}
		if( $target == 1 && isset( $department_ids ) && count( $department_ids ) > 0 ) {
			foreach( $department_ids as $department_id ) {
				doquery( "insert into notification_2_department(notification_id, department_id) values( '".$id."', '".$department_id."' )", $dblink );
			}
		}
		unset($_SESSION["notification_manage"]["add"]);
		header('Location: notification_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["notification_manage"]["add"][$key]=$value;
		header('Location: notification_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}