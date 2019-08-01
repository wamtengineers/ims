<?php
if(!defined("APP_START")) die("No Direct Access");
$academic_year = current_academic_year();
if(isset($_POST["subject_teachers_edit"])){
	extract($_POST);
	$err="";
	foreach( $employee_id as $subject_id => $employee_id ) {
		$rs = doquery( "select * from subject_teachers where subject_id = '".$subject_id."' and class_section_id = '".$id."'", $dblink );
		if( numrows( $rs ) > 0 ) {
			$r = dofetch( $rs );
			if( $employee_id == "" ) {
				doquery( "delete from subject_teachers where id = '".$r[ "id" ]."'", $dblink );
			}
			else{
				doquery( "update subject_teachers set employee_id = '".$employee_id."', is_class_teacher = '".(isset($class_teacher) && $class_teacher == $subject_id?1:0)."' where id = '".$r[ "id" ]."'", $dblink );
			}
			if(numrows( $rs )>1) {
				while($r = dofetch( $rs )){
					doquery( "delete from subject_teachers where id = '".$r[ "id" ]."'", $dblink );
				}
			}
		}
		else{
			if( $employee_id != "" ) {
				doquery( "insert into subject_teachers(school_id, class_section_id, subject_id, employee_id) values('".$_SESSION["current_school_id"]."', '".$id."', '".$subject_id."', '".$employee_id."' )", $dblink );
			}
		}
	}
	foreach( $marks as $exam_id => $subjects ) {
		foreach( $subjects as $subject_id => $mark ){
			doquery( "delete from examination_marks where exam_id='".$exam_id."' and subject_id='".$subject_id."'", $dblink );
			if( !empty( $mark[ "max" ] ) ) {
				doquery( "insert into examination_marks values( '".$exam_id."', '".$subject_id."', '".$mark[ "max" ]."', '".$mark[ "min" ]."' )", $dblink );
			}
		}
	}
	header('Location: subject_teachers_manage.php?tab=edit&id='.$id.'&msg='.url_encode("Sucessfully Updated"));
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from class_section where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["subject_teachers_manage"]["edit"]))
			extract($_SESSION["subject_teachers_manage"]["edit"]);
	}
	else{
		header("Location: subject_teachers_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: subject_teachers_manage.php?tab=list");
	die;
}