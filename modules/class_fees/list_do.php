<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["class_fees_edit"]) || isset( $_POST[ "class_fees_edit_update_student" ] )){
	extract($_POST);
    $current_year = current_academic_year();
	$rs = doquery( "select id, class_name from class where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by id", $dblink );
	if( numrows( $rs ) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			$rs2 = doquery( "select * from fees where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by id", $dblink );
			if( numrows( $rs2 ) > 0 ) {
				while( $r2 = dofetch( $rs2 ) ) {
					$rs3 = doquery( "select fees from class_fees where class_id='".$r[ "id" ]."' and fees_id='".$r2[ "id" ]."'", $dblink );
					if( isset( $_POST[ "class_fees_edit_update_student" ] ) ) {
						$old_fees = 0;
						if( numrows( $rs3 ) > 0 ) {
							$r3 = dofetch( $rs3 );
							$old_fees = $r3[ "fees" ];
						}
						//if( $old_fees != $_POST[ "class_fee" ][ "class_".$r[ "id" ] ][ "fees_".$r2[ "id" ] ] ) {
							$students = doquery( "SELECT student_id from student_2_class a inner join class_section b on a.class_section_id = b.id where b.class_id = '".$r[ "id" ]."' and academic_year_id = '".$current_year[ "id" ]."'", $dblink );
							if( numrows( $students ) > 0 ) {
								while( $student = dofetch( $students ) ) {
									$required = get_student_meta( $student[ "student_id" ], "fees_".$r2[ "id" ]."_required");
									if( !$r2[ "selected_students" ] || $required == 1 ){
										set_student_meta( $student[ "student_id" ], "fees_".$r2[ "id" ], $_POST[ "class_fee" ][ "class_".$r[ "id" ] ][ "fees_".$r2[ "id" ] ]);
										if( $r2[ "has_discount" ] ) {
											$discount = get_student_meta( $student[ "student_id" ], "fees_".$r2[ "id" ]."_discount" );
											set_student_meta( $student[ "student_id" ], "fees_".$r2[ "id" ]."_approved", $_POST[ "class_fee" ][ "class_".$r[ "id" ] ][ "fees_".$r2[ "id" ] ] - $discount );
										}
									}
								}
							}
						//}
					}
					if( numrows( $rs3 ) > 0 ) {
						doquery( "update class_fees set fees = '".$_POST[ "class_fee" ][ "class_".$r[ "id" ] ][ "fees_".$r2[ "id" ] ]."'  where class_id='".$r[ "id" ]."' and fees_id='".$r2[ "id" ]."'", $dblink );
					}
					else{
						doquery( "insert into class_fees values( '".$r[ "id" ]."', '".$r2[ "id" ]."', '".$_POST[ "class_fee" ][ "class_".$r[ "id" ] ][ "fees_".$r2[ "id" ] ]."' )", $dblink );
					}
				}
			}
		}
	}
	header('Location: class_fees_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
}