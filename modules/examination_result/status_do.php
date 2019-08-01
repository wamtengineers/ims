<?php

if(!defined("APP_START")) die("No Direct Access");

if(isset($_GET["examination_type_id"]) && !empty($_GET["examination_type_id"])){
	
	extract($_GET);
	
	doquery("update examination_marks_students SET status='".$status."' where 
	exam_id='".$examination_type_id."' and student_id in 
	(select student_id from student_2_class where 
	class_section_id='".$class_section_id."' and 
	academic_year_id='".$academic_year_id."') and subject_id='".$subject_id."'", $dblink);
	
	header("Location: examination_result_manage.php?err='".url_encode("Status Changed")."'");
	die;
}
else if(isset($_GET["id"]) && !empty($_GET["id"])){

	doquery("update `examination_marks_students` set status=".slash($_GET["s"])." where id=".slash($_GET["id"])."",$dblink);

	header("Location: examination_result_manage.php?err='".url_encode("Status Changed")."'");
	die;

}
else{
	header("Location: examination_result_manage.php?err='".url_encode("Status Changed")."'");
	die;
}