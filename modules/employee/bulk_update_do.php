<?php
if(!defined("APP_START")) die("No Direct Access");
$employee_main_fields = array(
	"department_id",
	"designation_id",
	"name",
	"father_name",
	"employee_code",
);
$extra="";
if( $_SESSION[ "current_school_id" ] != 0 ) {
	$extra=" and school_id='".$_SESSION[ "current_school_id" ]."'";
}
$employee_meta_fields = array_merge( $employee_main_fields, $employee_meta_fields );
if( isset( $_GET[ "fields_array" ] ) ) {
	$_SESSION[ "employee" ][ "bulk_update" ][ "fields" ] = $_GET[ "fields_array" ];
}
if( isset( $_SESSION[ "employee" ][ "bulk_update" ][ "fields" ] ) ) {
	$fields = $_SESSION[ "employee" ][ "bulk_update" ][ "fields" ];
}
else{
	$fields = array( 'name' );
}
if ( isset($_GET[ "employee_status" ])){
	$_SESSION[ "employee" ][ "bulk_update" ][ "employee_status" ] = $_GET[ "employee_status" ];
}
if( isset( $_SESSION[ "employee" ][ "bulk_update" ][ "employee_status" ] ) ) {
	$employee_status = $_SESSION[ "employee" ][ "bulk_update" ][ "employee_status" ];
}
else{
	$employee_status = "";
}
$sql = "select * from employee where 1 $extra";
if( !empty( $employee_status ) ) {
	$extra .= " and status = '".$employee_status."'";
}
$sql .= ' order by name';

if( isset( $_POST[ "save_employee" ] ) ) {
	extract( $_POST );
	$rs=doquery($sql, $dblink);
	if(numrows($rs)>0){
		$i = 0;
		while($r=dofetch($rs)){ 
			foreach( $fields as $field ) {
				if( isset( $field_value[ $field ][ $r[ "id" ] ] ) ) {
					if( in_array( $field, $employee_main_fields ) ) {
						if( strpos( $field, "date" ) !== false ) {
							$fv = date_dbconvert( $field_value[ $field ][ $r[ "id" ] ] );
						}
						else{
							$fv = slash( $field_value[ $field ][ $r[ "id" ] ] );
						}
						doquery( "update employee set `".$field."` = '".$fv."' where id='".$r[ "id" ]."'", $dblink );
					}
					else {
						if( strpos( $field, "date" ) !== false ) {
							$fv = date_dbconvert( $field_value[ $field ][ $r[ "id" ] ] );
						}
						else{
							$fv = slash( $field_value[ $field ][ $r[ "id" ] ] );
						}
						set_employee_meta( $r[ "id" ], $field, $fv );
					}	
				}
			}
		}
	}
	header( "Location: employee_manage.php?tab=bulk_update&id=".$r["id"]."&msg=".url_encode( "Data saved successfully." ) );
	die;
}