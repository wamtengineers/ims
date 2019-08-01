<?php
if(!defined("APP_START")) die("No Direct Access");
$student_main_fields = array(
	"id",
	"student_name",
	"father_name",
	"surname",
	"birth_date",
	"addmission_date",
	"gender",
	"address",
);
$student_meta_fields = array_merge( $student_main_fields, $student_meta_fields );
if( isset( $_GET[ "id" ] ) ) {
	$academic_year_id = slash( $_GET[ "id" ] );
}
else {
	header( "Location: academic_year_manage.php" );
	die;
}
if( isset( $_GET[ "fields_array" ] ) ) {
	$_SESSION[ "academic_year" ][ "view" ][ "fields" ] = $_GET[ "fields_array" ];
}
if( isset( $_SESSION[ "academic_year" ][ "view" ][ "fields" ] ) ) {
	$fields = $_SESSION[ "academic_year" ][ "view" ][ "fields" ];
}
else{
	$fields = array( 'balance' );
}
if ( isset($_GET[ "class_section_id" ])){
	$_SESSION[ "academic_year" ][ "view" ][ "class_section_id" ] = $_GET[ "class_section_id" ];
}
if( isset( $_SESSION[ "academic_year" ][ "view" ][ "class_section_id" ] ) ) {
	$class_section_id = $_SESSION[ "academic_year" ][ "view" ][ "class_section_id" ];
}
else{
	$class_section_id = "";
}
$sql = "select a.*, b.class_section_id, b.id as student_2_class_id, b.group_id from student a inner join student_2_class b on a.id = b.student_id where a.status=1 and b.status=1 and academic_year_id = '".$academic_year_id."'";
if( !empty( $class_section_id ) ) {
	$sql .= " and class_section_id = '".$class_section_id."'";
}
$sql .= ' order by student_name';

$fee_id = dofetch(doquery( "select id from fees where title like '%Tuition Fee%'", $dblink ));
$fee_id = $fee_id[ "id" ];

if( isset( $_POST[ "save_balance" ] ) ) {
	extract( $_POST );
	//print_r($field_value); die;
	$rs=doquery($sql, $dblink);
	if(numrows($rs)>0){
		$fees = array();
		$i = 0;
		foreach( $fields as $field ) {
			if( substr( $field, 0, 5 ) === 'fees_' ) {
				$fee_id = str_replace( "fees_", "", $field );
				$fees[ $field ] = dofetch( doquery( "select * from fees where id = '".$fee_id."'", $dblink ));
				$fields[ $i ] = $field = "fees_".$fees[ $field ][ "id" ].($fees[ $field ][ "has_discount" ]?"_approved":"");
			}
			$i++;
		}
		//print_r($fields);
		//die;
		while($r=dofetch($rs)){ 
			foreach( $fields as $field ) {
				if( isset( $field_value[ $field ][ $r[ "id" ] ] ) ) {
					if( substr( $field, 0, 5 ) === 'fees_' ) {
						set_student_meta( $r[ "id" ], $field, str_replace( ",", "", $field_value[ $field ][ $r[ "id" ] ] ) );
					}
					else if( $field == 'balance' && 0 ) {
						$check = doquery( "select * from student_academic_year_balance where academic_year_id='".$academic_year_id."' and student_id='".$r[ "id" ]."'", $dblink );
						if( numrows( $check ) > 0 ) {
							$check = dofetch( $check );
							doquery( "update student_academic_year_balance set balance = '".slash( str_replace( ",", "", $field_value[ $field ][ $r[ "id" ] ] ) )."' where id='".$check[ "id" ]."'", $dblink );
						}
						else {
							doquery( "insert into student_academic_year_balance(academic_year_id, student_id, balance) values( '".$academic_year_id."', '".$r[ "id" ]."', '".slash( str_replace( ",", "", $field_value[ $field ][ $r[ "id" ] ] ) )."' )", $dblink );
						}
					}
					else if( in_array( $field, $student_main_fields ) ) {
						if( strpos( $field, "date" ) !== false ) {
							$fv = date_dbconvert( $field_value[ $field ][ $r[ "id" ] ] );
						}
						else{
							$fv = slash( $field_value[ $field ][ $r[ "id" ] ] );
						}
						doquery( "update student set `".$field."` = '".$fv."' where id='".$r[ "id" ]."'", $dblink );
					}
					else if( $field == 'group' ){
						doquery( "update student_2_class set group_id='".$field_value[ 'group' ][ $r[ "id" ] ]."' where id = '".$r[ "student_2_class_id" ]."'", $dblink );
					}
					else if( $field == 'optional_subjects' ){
						doquery("delete from student_2_class_2_subject where student_2_class_id='".$r[ "student_2_class_id" ]."'", $dblink);
						if( isset($field_value[ 'optional_subjects' ][ $r[ "id" ] ]) && is_array( $field_value[ 'optional_subjects' ][ $r[ "id" ] ] ) && count( $field_value[ 'optional_subjects' ][ $r[ "id" ] ] ) > 0 ) {
							foreach( $field_value[ 'optional_subjects' ][ $r[ "id" ] ] as $subject_id ) {
								doquery( "insert into student_2_class_2_subject(student_2_class_id, subject_id) values( '".$r[ "student_2_class_id" ]."', '".$subject_id."' )", $dblink );
							}
						}
					}
					else {
						if( strpos( $field, "date" ) !== false ) {
							$fv = date_dbconvert( $field_value[ $field ][ $r[ "id" ] ] );
						}
						else{
							$fv = slash( $field_value[ $field ][ $r[ "id" ] ] );
						}
						set_student_meta( $r[ "id" ], $field, $fv );
					}	
				}
				else if( $field == 'optional_subjects' ){
					doquery("delete from student_2_class_2_subject where student_2_class_id='".$r[ "student_2_class_id" ]."'", $dblink);
				}
			}
		}
	}
	header( "Location: academic_year_manage.php?tab=view&id=".$academic_year_id."&msg=".url_encode( "Data saved successfully." ) );
	die;
}