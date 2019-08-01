<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["student_2_class_add"])){
	extract($_POST);
	$err="";
	if(empty($class_section_id) || empty($academic_year_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		doquery("update student_2_class set status='0' where student_id='".$parent_student_id."'",$dblink);
		$sql="INSERT INTO student_2_class (student_id, class_section_id, academic_year_id, board_id, group_id) VALUES ('".slash($parent_student_id)."','".slash($class_section_id)."','".slash($academic_year_id)."','".slash($board_id)."','".slash($group_id)."')";
		doquery($sql,$dblink);
		$id = inserted_id();
		if( isset( $subject_ids ) && count( $subject_ids ) > 0 ) {
			foreach( $subject_ids as $subject_id ) {
				doquery( "insert into student_2_class_2_subject(student_2_class_id, subject_id) values( '".$id."', '".$subject_id."' )", $dblink );
			}
		}
		unset($_SESSION["student_2_class_manage"]["add"]);
		header('Location: student_2_class_manage.php?tab=edit&id='.$id.'&msg='.url_encode("Sucessfully Added")."&done=1");
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["student_2_class_manage"]["add"][$key]=$value;
		header('Location: student_2_class_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}