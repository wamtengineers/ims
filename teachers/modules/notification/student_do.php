<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["notification_2_student_save"]) || isset($_POST["notification_2_student_save_sms"])){
	$id = slash( $_POST[ "id" ] );
	$students = json_decode($_POST["students"]);
	doquery( "delete from notification_2_student where notification_id='".$id."'", $dblink );
	foreach( $students as $student ) {
		if( $student->status ) {
			doquery( "insert into notification_2_student(notification_id, student_id) values('".$id."', '".$student->id."')", $dblink );
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
		$student_id = array();
			$sql="select * from notification_2_student where notification_id='".$r["id"]."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$student_id[] = $r1[ "student_id" ];
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