<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id = slash( $_GET[ "id" ] );
	$r = dofetch( doquery( "select * from notification where id = '".$id."'", $dblink ) );
	if($r[ "target" ]==0){
		$academic_year = current_academic_year();
		$check = doquery( "select class_section_id from notification_2_class_section where notification_id = '".$r[ "id" ]."'", $dblink );
		$check2 = doquery( "select student_id from notification_2_student where notification_id = '".$r[ "id" ]."'", $dblink );
		if( numrows( $check2 ) > 0 ) {
			$rs2 = doquery("select * from student where id in (select student_id from notification_2_student where notification_id = '".$r[ "id" ]."')", $dblink);
		}
		else{
			$rs2 = doquery("select * from student where id in (select student_id from student_2_class where academic_year_id = '".$academic_year[ "id" ]."'".(numrows($check)>0?" and class_section_id in (select class_section_id from notification_2_class_section where notification_id = '".$r[ "id" ]."')":"").")", $dblink);
		}
		if( numrows( $rs2 ) > 0 ) {
			while( $r2 = dofetch( $rs2 ) ) {
				$sms_number =  get_student_meta( $r2[ "id" ], "phone");
				//echo unslash( $r2[ "student_name" ] )." / ".$sms_number."<br />";
				$sms=str_replace(array(
					"%student_name%",
					"%parent_name%",
					"%today%",
					"%dues%"
				), array(
					unslash($r2[ "student_name" ]),
					unslash($r2[ "father_name" ]),
					date("d/m/Y", strtotime($r[ "date" ])),
					curr_format( get_student_balance( $r2[ "id" ], $r[ "date" ] ))
				), unslash( $r[ "text" ] ));
				$sms_number = get_student_meta( $r2[ "id" ], "phone");
				sendsms(unslash($sms_number), $sms);
			}
		}
	}
	header("Location: notification_manage.php?msg=".url_encode( "SMS has been sent." ));
	die;
}