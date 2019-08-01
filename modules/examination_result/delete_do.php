<?php

if(!defined("APP_START")) die("No Direct Access");

if((isset($_GET["class_section_id"]) && !empty($_GET["class_section_id"])) && (isset($_GET["subject_id"]) && !empty($_GET["subject_id"]))){
	
	extract($_GET);
	
	$exam = doquery( "update examination_marks_students SET marks=0 where exam_id='".$examination_type_id."' and student_id in (select student_id from student_2_class where class_section_id = '".$class_section_id."' and academic_year_id = '".$academic_year_id."') and subject_id = '".$subject_id."'", $dblink );
	
	header("Location: examination_result_manage.php?err='".url_encode("Records Deleted")."'");
	die;
}
else{
	header("Location: examination_result_manage.php?err='".url_decode("Please Select Class Section")."'");
	die;
}