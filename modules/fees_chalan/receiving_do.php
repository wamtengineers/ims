<?php
if(!defined("APP_START")) die("No Direct Access");
if( isset( $_GET[ "date" ] ) ) {
	$_SESSION[ "fee_chalan_receiving_date" ] = $_GET[ "date" ];
}
if( isset( $_SESSION[ "fee_chalan_receiving_date" ] ) ) {
	$date = $_SESSION[ "fee_chalan_receiving_date" ];
}
else{
	$date = "d/m/Y";
}
if( count( $_POST ) > 0 ) {
	if( isset( $_POST[ "receiving_id" ] ) ) {
		doquery( "delete from fees_chalan_receiving where id = '".slash( $_POST[ "receiving_id" ] )."'", $dblink );
		die;
	}
	if( isset( $_POST[ "barcode" ] ) ) {
		$id = (int)substr( $_POST[ "barcode" ], 10 );		
	}
	else{
		$id = $_POST[ "id" ];
	}
	$chalan = doquery( "select * from fees_chalan where id = '".slash( $id )."'", $dblink );
	if( numrows( $chalan ) > 0 ) {
		$chalan = dofetch( $chalan );
		$chalan_details = get_chalan_details( $chalan );
		$response[ "id" ] = $id;
		$response[ "amount" ] = $chalan_details[ "total" ];
		if( $chalan_details[ "due_date" ] < date( "Y-m-d" ) ) {
			$response[ "amount" ] = $chalan_details[ "total_after_due_date" ];
		}
		$student = doquery( "select a.*, c.title as section, d.class_name from student a left join student_2_class b on a.id = b.student_id left join class_section c on b.class_section_id = c.id left join class d on c.class_id = d.id where b.id='".$chalan[ "student_2_class_id" ]."'", $dblink );
		if( numrows( $student ) > 0 ) {
			$student = dofetch( $student );
			$response[ "student_id" ] = $student[ "id" ];
			$response[ "class" ] = unslash( $student[ "class_name" ] )." (".unslash( $student[ "section" ] ).")";
			$response[ "student_name" ] = unslash( $student[ "student_name" ] )." ".( $student[ "gender" ] == 'male'?"S/o":"D/o" )." ".unslash( $student[ "father_name" ] );
		}
		$receiving = doquery( "select * from fees_chalan_receiving where fees_chalan_id = '".$chalan[ "id" ]."'", $dblink );
		if( numrows( $receiving ) > 0 ) {
			$receiving = dofetch( $receiving );
			$response[ "status" ] = 2;
			$response[ "receiving_id" ] = $receiving[ "id" ];
		}
		else {
			$response[ "status" ] = 1;
			if( !isset( $_POST[ "do_confirm" ] ) || isset( $_POST[ "amount" ] ) ) {
				if( isset( $_POST[ "do_confirm" ] ) ) {
					$response[ "amount" ] = $_POST[ "amount" ];
				}
				doquery( "insert into fees_chalan_receiving(fees_chalan_id, amount, payment_date ) values('".$chalan[ "id" ]."', '".$response[ "amount" ]."', '".date_dbconvert( $date )."' )", $dblink );
				$response[ "receiving_id" ] = inserted_id();
			}
		}
	}
	else {
		$response[ "status" ] = 0;
	}
	echo json_encode( $response );
	die;
}