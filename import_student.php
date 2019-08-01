<?php
session_start();
if( isset( $_GET[ "school_id" ] ) ) {
	$_SESSION[ "session_school_id" ] = $_GET[ "school_id" ];
	include("inc/db.php");
	include("inc/utility.php");
	$current_year = current_academic_year();
	$last_year = doquery( "select * from academic_year where start_date < '".$current_year[ "start_date" ]."' order by start_date desc limit 0,1", $dblink );
	$next_class = array();
	$next_section = array();
	$students = array();
	if( numrows( $last_year ) > 0 ) {
		$last_year = dofetch( $last_year );
		$rs = doquery( "select a.*, b.id as student_2_class_id, b.board_id, b.group_id, c.id as section_id, c.title as section, d.id as class_id, d.sortorder as class_order, d.class_name as class from student a inner join student_2_class b on a.id = b.student_id left join class_section c on b.class_section_id = c.id left join class d on c.class_id = d.id where academic_year_id = '".$last_year[ "id" ]."' and a.status = 1 order by d.sortorder, c.title, a.id", $dblink );
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				$c = doquery( "select * from class where sortorder > '".$r[ "class_order" ]."' order by sortorder limit 0,1", $dblink );
				if( numrows( $c ) == 0 ) {
					$next_class[ $r[ "class_id" ] ] = -1;
					$fields = $student_meta_fields;
					$students[] = array(
						"id" => $r[ "id" ],
						"student_name" => unslash( $r[ "student_name" ] ),
						"father_name" => unslash( $r[ "father_name" ] ),
						"surname" => unslash( $r[ "surname" ] ),
						"birth_date" => date_convert( $r[ "birth_date" ] ),
						"address" => unslash( $r[ "address" ] ),
						"addmission_date" => date_convert( $r[ "addmission_date" ] ),
						"gender" => unslash( $r[ "gender" ] ),
						"balance" => get_student_balance( $r[ "id" ] ),
						"class" =>  unslash( $r[ "class" ] ),
						"section" =>  unslash( $r[ "section" ] ),
					);
					foreach( $fields as $field ){
						$student[ $field ] = get_student_meta($r["id"], $field);
					}
				}
			}
		}
	}
	echo json_encode( $students );
}