<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["on_demand_fees_edit"])){
	extract($_POST);
	$err="";
	if(empty($academic_year_id) || empty($fees_title) || empty($fees_amount) || empty($date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update on_demand_fees set `academic_year_id`='".slash($academic_year_id)."', `fees_title`='".slash($fees_title)."', `fees_amount`='".slash($fees_amount)."', `date`='".slash(date_dbconvert(unslash($date)))."', selected_classes = '".slash($selected_classes)."', selected_students = '".slash($selected_students)."' where id='".$id."'";
		doquery($sql,$dblink);
		doquery("delete from on_demand_fees_classes where on_demand_fees_id='".$id."'", $dblink);
		if( $selected_classes == 1 && isset( $class_section_ids ) && count( $class_section_ids ) > 0 ) {
			foreach( $class_section_ids as $class_section_id ) {
				doquery( "insert into on_demand_fees_classes(on_demand_fees_id, class_section_id) values( '".$id."', '".$class_section_id."' )", $dblink );
			}
		}
		if( $selected_students == 0 ) {
			doquery("delete from on_demand_fees_student where on_demand_fees_id='".$id."'", $dblink);
		}
		unset($_SESSION["on_demand_fees_manage"]["edit"]);
		header('Location: on_demand_fees_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["on_demand_fees_manage"]["edit"][$key]=$value;
		header("Location: on_demand_fees_manage.php?tab=edit&err=".url_encode($err)."&id='".$id."'");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from on_demand_fees where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$date=date_convert($date);
			$class_section_ids = array();
			$sql="select * from on_demand_fees_classes where on_demand_fees_id='".$id."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$class_section_ids[] = $r1[ "class_section_id" ];
				}
			}
		if(isset($_SESSION["on_demand_fees_manage"]["edit"]))
			extract($_SESSION["on_demand_fees_manage"]["edit"]);
	}
	else{
		header("Location: on_demand_fees_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: on_demand_fees_manage.php?tab=list");
	die;
}