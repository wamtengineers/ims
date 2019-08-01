<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["on_demand_fees_add"])){
	extract($_POST);
	$err="";
	if(empty($academic_year_id) || empty($fees_title) || empty($fees_amount) || empty($date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO on_demand_fees (school_id, academic_year_id, selected_classes, selected_students, fees_title, fees_amount, date) VALUES ('".$_SESSION["current_school_id"]."', '".slash($academic_year_id)."', '".slash($selected_classes)."', '".slash($selected_students)."', '".slash($fees_title)."', '".slash($fees_amount)."', '".slash(date_dbconvert($date))."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		if( $selected_classes == 1 && isset( $class_section_ids ) && count( $class_section_ids ) > 0 ) {
			foreach( $class_section_ids as $class_section_id ) {
				doquery( "insert into on_demand_fees_classes(on_demand_fees_id, class_section_id) values( '".$id."', '".$class_section_id."' )", $dblink );
			}
		}
		unset($_SESSION["on_demand_fees_manage"]["add"]);
		header('Location: on_demand_fees_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["on_demand_fees_manage"]["add"][$key]=$value;
		header('Location: on_demand_fees_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}