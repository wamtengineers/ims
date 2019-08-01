<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["class_attendance_save"]) || isset($_POST["class_attendance_save_sms"])){
	$id = slash( $_POST[ "id" ] );
	$date = date_dbconvert( $_POST[ "date" ] );
	$students = json_decode($_POST["students"]);
	foreach( $students as $student ) {
		if( $student->status ) {
			if( numrows( doquery( "select * from student_attendance where student_id='".$student->id."' and date='".$date."'", $dblink ) ) == 0 ) {
				doquery( "insert into student_attendance(student_id, date, checked_in) values( '".$student->id."', '".$date."', NOW())", $dblink );
			}
		}
		else {
			$rs = doquery( "select * from student_attendance where student_id='".$student->id."' and date='".$date."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				doquery( "delete from student_attendance where id='".$r[ "id" ]."'", $dblink );
			}
		}
	}
	$class_attendance = doquery( "select * from student_daily_attendance a inner join employee b on a.taken_by = b.id where class_section_id = '".$id."' and date='". $date."'", $dblink );
	$status = 1;
	if( isset($_POST["class_attendance_save_sms"]) ) {
		foreach( $students as $student ) {
			if( !$student->status ) {
				$sms_number =  get_student_meta( $student->id, "phone");
				//$sms_number = "923453555821";
				$sms=str_replace(array(
					"%student_name%",
					"%parent_name%",
					"%today%"
				), array(
					unslash($student->name),
					unslash($student->parent_name),
					date("d/m/Y", strtotime($date))
				), get_config("sms_parent_absent_text"));
				//$sms_number = '923333989819';
				sendsms(unslash($sms_number), $sms);
			}
		}
		$status = 2;
	}
	if( numrows( $class_attendance ) > 0 ) {
		$class_attendance = dofetch( $class_attendance );
		doquery( "update student_daily_attendance set status='".$status."' where id='".$class_attendance["id"]."'", $dblink );
	}
	else {
		doquery( "insert into student_daily_attendance(class_section_id, date, taken_by, status) values('".$id."', '".$date."', '".$_SESSION[ "logged_in_teachers" ][ "id" ]."', '".$status."')", $dblink );
	}
	header("Location: class_attendance_manage.php?tab=list&msg=".url_encode( "Attendance has been taken successfully." ));
	die;
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$sql="select a.id, a.title as section, b.class_name from class_section a inner join class b on a.class_id = b.id where a.id = '".slash($_GET["id"])."'";
	$rs=doquery($sql,$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		$date = slash( $_GET[ "date" ] );
		$current_academic_year = current_academic_year();
	}
	else{
		header("Location: class_attendance_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: class_attendance_manage.php?tab=list");
	die;
}