<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_REQUEST["id"]) && $_REQUEST["id"]!=""){
	$rs=doquery("select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.status = 1 and b.status = 1 and academic_year_id = '".$current_academic_year_id."' and a.id='".slash($_REQUEST["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
	}
	else{
		header("Location: examination_result_students_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: examination_result_students_manage.php?tab=list");
	die;
}
$parameters = array(
	'punctual',
	'neatness',
	'hand_writing',
	'content',
	'attitude',
	'discipline',
	'cooperation',
	'gesture',
	'presentation',
	'language_skill',
	'confidence',
	'understanding',
	'fitness',
	'commitment',
	'determination',
	'competition'
);
$sql = "select b.id as studentid, b.*, d.id as exam_result_id, d.* from student_2_class a left join student b on a.student_id = b.id left join examination_result_students d on a.student_id = d.student_id and d.exam_id = '".slash($_REQUEST["id"])."' where class_section_id = '".$class_teacher["class_section_id"]."' and academic_year_id = '".$current_academic_year_id."' order by b.student_name, b.father_name";
if(isset($_POST["examination_result_students_edit"])){
	$id = $_POST[ "id" ];
	$rs = show_page(10, $pageNum, $sql);
	if( numrows( $rs ) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			if( isset( $_POST[ "remarks" ][ $r["studentid"] ] ) ) {
				if( empty( $r[ "exam_result_id" ] ) ) {
					$sql="INSERT INTO examination_result_students (exam_id, student_id, ";
					foreach( $parameters as $parameter ){
						$sql.=$parameter.", ";
					}
					$sql.="remarks) VALUES ('".$id."', '".$r[ "studentid" ]."', ";
					foreach( $parameters as $parameter ){
						$sql.="'".slash($_POST[$parameter][$r["studentid"]])."', ";
					}
					$sql.="'".slash($_POST[ "remarks" ][ $r["studentid"] ])."')";
				}
				else{
					$sql="Update examination_result_students set ";
					foreach( $parameters as $parameter ){
						$sql.="`".$parameter."`='".slash($_POST[$parameter][$r["studentid"]])."', ";
					}
					$sql.="`remarks`='".slash($_POST[ "remarks" ][ $r["studentid"] ])."' where id='".$r[ "exam_result_id" ]."'";
				}
				doquery($sql,$dblink);
			}
		}
	}
	header("Location: examination_result_students_manage.php?tab=edit&id=".$id);
	die;
}
/*----------------------------------------------------------------------------------*/