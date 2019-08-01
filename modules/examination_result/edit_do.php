<?php

	extract($_POST);
	extract($_GET);

	$mr = $marks;
	
	foreach($mr as $id => $marks) {
		
		$result = doquery("select * from examination_marks_students where student_id='".$id."' and subject_id='".$subject_id."'",$dblink);

		if(numrows($result)>0){
			doquery("update examination_marks_students SET marks='".$marks."' where exam_id = '".$examination_type_id."' and student_id = '".$id."' and subject_id = '".$subject_id."'", $dblink);	

		}
		else{
			header("Location: examination_result_manage.php?err='".url_encode("No Result Found")."'");
			die;
		}
			
	}

	header("Location: examination_result_manage.php?msg='".url_encode("Marks Update Successfully.")."'");
	die;
?>
