<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["academic_year_promote_do"])){
	extract($_POST);
	$current_year = dofetch( doquery( "select * from academic_year where id  = '".$id."'", $dblink ) );
	$last_year = dofetch( doquery( "select * from academic_year where id  = '".$last_academic_year_id."'", $dblink ) );
	$class = array();
	$msg = '';
	for( $i = 0; $i < count( $class_section_id_from ); $i++ ){
		$students = doquery( "select a.*, b.id as student_2_class_id, b.board_id, b.group_id, d.class_name as class, c.title as section from student a inner join student_2_class b on a.id = b.student_id left join class_section c on b.class_section_id = c.id left join class d on c.class_id = d.id where academic_year_id = '".$last_year[ "id" ]."' and class_section_id = '".$class_section_id_from[ $i ]."' and a.status = 1 and b.status = 1", $dblink );
		if( numrows( $students ) > 0 ) {
			while( $student = dofetch( $students ) ) {
				doquery( "update student_2_class set status = 0 where id = '".$student[ "student_2_class_id" ]."'", $dblink );
				$msg .= $student[ "id" ]." - ".unslash( $student[ "student_name" ]." ".($student[ "gender" ]=="male"?"S/o":"D/o")." ".$student[ "father_name" ] );
				if( !empty( $class_section_id_to[ $i ] ) ) {
					$check = doquery( "select * from student_2_class where academic_year_id = '".$current_year[ "id" ]."' and class_section_id = '".$class_section_id_to[ $i ]."' and student_id = '".$student[ "id" ]."'", $dblink );
					if( numrows( $check ) > 0 ) {
						$check = dofetch( $check );
						doquery( "update student_2_class set status = 1 where id = '".$check[ "id" ]."'", $dblink );
					}
					else{
						doquery( "insert into student_2_class( student_id, class_section_id, academic_year_id, board_id, group_id ) values( '".$student[ "id" ]."', '".$class_section_id_to[ $i ]."', '".$current_year[ "id" ]."', '".$student[ "board_id" ]."', '".$student[ "group_id" ]."' )", $dblink );	
					}
					if( !isset( $class[ $class_section_id_to[ $i ] ] ) ) {
						$class[ $class_section_id_to[ $i ] ] = dofetch( doquery( "select class_name as class, title as section from class a left join class_section b on a.id = b.class_id where b.id = '".$class_section_id_to[ $i ]."'", $dblink ) );
					}
					$msg .= " promoted from class ".$student[ "class" ]."-".$student[ "section" ]." to class ".$class[ $class_section_id_to[ $i ] ][ "class" ]."-".$class[ $class_section_id_to[ $i ] ][ "section" ];
				}
				else{
					$msg .= " left from class ".$student[ "class" ]."-".$student[ "section" ];
				}
				$msg .= '<br>';
			}
		}
	}
}
else {
	if( isset( $_GET[ "id" ] ) ) {
		$id = slash( $_GET[ "id" ] );
	}
	else{
		header( "Location: academic_year_manage.php" ); die;
	}
}