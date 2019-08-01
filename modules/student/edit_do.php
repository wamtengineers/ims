<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["student_add"])){
	extract($_POST);
	$err="";
	if(empty($student_name) || empty($father_name)) {
		$err="Fields with (*) are Mandatory.<br />";
	}
	if($err==""){
		if( isset( $id ) ) {
			$sql="Update student set `student_name`='".slash($student_name)."', `father_name`='".slash($father_name)."', `surname`='".slash($surname)."', `birth_date`='".date_dbconvert($birth_date)."', `addmission_date`='".date_dbconvert($addmission_date)."',`gender`='".slash($gender)."', address='".slash( $address )."' where id='".$id."'";
			doquery($sql,$dblink);
		}
		else {
			$sql="INSERT INTO student (school_id, student_name, father_name, surname, birth_date, address, addmission_date, gender, created_at) VALUES ('".$_SESSION["current_school_id"]."', '".slash($student_name)."','".slash($father_name)."','".slash($surname)."','".date_dbconvert($birth_date)."', '".slash($address)."', '".date_dbconvert($addmission_date)."','".slash($gender)."',NOW())";
			doquery($sql,$dblink);
			$id = inserted_id();
		}
		$fields = $student_meta_fields;
		foreach( $fields as $field ){
			if( isset( $$field ) ){
				set_student_meta($id, $field, $$field);
			}
		}
		$student_2_class = doquery( "select a.*, b.title as section, c.class_name as class, d.title as level, e.title as `group`, f.title as board, g.title as academic_year from student_2_class a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id left join class_level d on c.class_level_id = d.id left join `group` e on a.group_id = e.id left join board f on a.board_id = f.id left join academic_year g on a.academic_year_id = g.id where student_id='".$id."' order by g.start_date desc", $dblink );
		if( numrows( $student_2_class ) > 0 ){
			while( $student_class = dofetch( $student_2_class ) ){
				if( isset( $_POST[ "balance_".$student_class[ "academic_year_id" ] ] ) ) {
					set_student_meta($id, "balance_".$student_class[ "academic_year_id" ], $_POST[ "balance_".$student_class[ "academic_year_id" ] ]);
				}
			}
		}
		$fees = doquery( "select * from fees where status=1 order by sortorder", $dblink );
		if( numrows( $fees ) > 0 ) {
			while( $fee = dofetch( $fees ) ) {
				set_student_meta($id, "fees_".$fee[ "id" ], $_POST[ "fees_".$fee["id"] ]);
				if( $fee[ "selected_students" ] ) {
					set_student_meta($id, "fees_".$fee[ "id" ]."_required", isset($_POST[ "fees_".$fee["id"]."_required" ])?1:0);
				}
				if( $fee[ "has_discount" ] ) {
					set_student_meta($id, "fees_".$fee[ "id" ]."_discount", $_POST[ "fees_".$fee["id"]."_discount" ]);
					set_student_meta($id, "fees_".$fee[ "id" ]."_approved", $_POST[ "fees_".$fee["id"]."_approved" ]);
				}
			}
		}
		unset($_SESSION["student_manage"]["edit"]);
		header('Location: student_manage.php?tab=edit&id='.$id.'&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		if( isset( $id ) ) {
			foreach($_POST as $key=>$value)
				$_SESSION["student_manage"]["edit"][ $id ][$key]=$value;
		}
		else {
			foreach($_POST as $key=>$value)
				$_SESSION["student_manage"]["add"][$key]=$value;
		}
		header("Location: student_manage.php?tab=edit&err=".url_encode($err).(isset($id)?"&id=$id":"&add"));
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if( !isset( $_GET[ "add" ] ) ) {
	if( !isset( $_GET[ "id" ] ) ) {
		$student = doquery( $sql, $dblink );
		if( numrows( $student ) > 0 ) {
			$student = dofetch( $student );
			$id = $student[ "id" ];
		}
		else {
			header( "Location: student_manage.php?tab=list&err=".url_encode( "No Result found" ) );
			die;
		}
	}
}
if(isset($_GET["id"]) && $_GET["id"]!="" || isset( $id ) ){
	if( !isset( $id ) ) {
		$id = slash( $_GET["id"] );
	}
	$rs=doquery("select * from student where id='".$id."'",$dblink);
	if(numrows($rs)>0){
		$student=dofetch($rs);
		$addmission_date=date_convert(get_student_meta($id, "date_of_birth"));
		$birth_date=date_convert(get_student_meta($id, "birth_date"));
	}
	else{
		header("Location: student_manage.php?tab=list");
		die;
	}
	$sql1 = str_replace( ' order by a.id', " and a.id > '".$id."' order by a.id", $sql );
	$r = doquery( $sql1, $dblink );
	if( numrows( $r ) > 0 ) {
		$next = dofetch( $r );
	}
	$sql1 = str_replace( ' order by a.id', " and a.id < '".$id."' order by a.id", $sql );
	$r = doquery( $sql1, $dblink );
	if( numrows( $r ) > 0 ) {
		$prev = dofetch( $r );
	}
}
/*
else{
	header("Location: student_manage.php?tab=list");
	die;
}*/