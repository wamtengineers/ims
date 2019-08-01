<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["student_2_class_edit"])){
	extract($_POST);
	$err="";
	if(empty($class_section_id) || empty($academic_year_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update student_2_class set `class_section_id`='".slash($class_section_id)."',`academic_year_id`='".slash($academic_year_id)."',`board_id`='".slash($board_id)."',`group_id`='".slash($group_id)."' "." where id='".$id."'";
		doquery($sql,$dblink);
		doquery("delete from student_2_class_2_subject where student_2_class_id='".$id."'", $dblink);
		if( isset( $subject_ids ) && count( $subject_ids ) > 0 ) {
			foreach( $subject_ids as $subject_id ) {
				doquery( "insert into student_2_class_2_subject(student_2_class_id, subject_id) values( '".$id."', '".$subject_id."' )", $dblink );
			}
		}
		unset($_SESSION["student_2_class_manage"]["edit"]);
		header('Location: student_2_class_manage.php?tab=edit&id='.$id.'&done=1&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["student_2_class_manage"]["edit"][$key]=$value;
		header("Location: student_2_class_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from student_2_class where id='".slash($_GET["id"])."' and student_id='".$parent_student_id."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$subject_ids = array();
			$sql="select * from student_2_class_2_subject where student_2_class_id='".$id."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$subject_ids[] = $r1[ "subject_id" ];
				}
			}
		if(isset($_SESSION["student_2_class_manage"]["edit"]))
			extract($_SESSION["student_2_class_manage"]["edit"]);
	}
	else{
		header("Location: student_2_class_manage.php?tab=edit&done=1&parent_id=$student_id");
		die;
	}
}
else{
	header("Location: student_2_class_manage.php?tab=edit&parent_id=$student_id");
	die;
}