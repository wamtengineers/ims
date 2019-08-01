<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["notification_2_employee_save"])){
	$id = slash( $_POST[ "id" ] );
	//$date = date_dbconvert( $_POST[ "date" ] );
	$employees = json_decode($_POST["employees"]);
	$notification = dofetch( doquery( "select * from notification where id = '".$id."'", $dblink ) );
	foreach( $employees as $employee ) {
		if( $employee->status ) {
			if( numrows( doquery( "select * from notification_2_employee where employee_id='".$employee->id."'", $dblink ) ) == 0 ) {
				doquery( "insert into notification_2_employee(notification_id, employee_id) values('".$id."', '".$employee->id."')", $dblink );
			}
		}
		else {
			$rs = doquery( "select * from notification_2_employee where notification_id='".$notification["id"]."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				doquery( "delete from notification_2_employee where employee_id='".$employee->id."'", $dblink );
			}
		}
	}
	header("Location: notification_manage.php?tab=list&msg=".url_encode( "Notification has been taken successfully." ));
	die;
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from notification where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$current_academic_year = current_academic_year();
			$employee_id = array();
			$sql="select * from notification_2_employee where notification_id='".$r["id"]."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$employee_id[] = $r1[ "employee_id" ];
				}
			}
		
	}
	else{
		header("Location: notification_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: notification_manage.php?tab=list");
	die;
}