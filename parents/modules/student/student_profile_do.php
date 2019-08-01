<?php
if(isset($_POST["update_password"])){
	extract($_POST);
	$msg = '';
	if( strlen($password) < 6 ) {
		$msg .= 'Password must be atleast 6 characters long.<br>';
	}
	if( !empty($password) && $password != $cpassword ) {
		$msg .= 'Confirm password did not match.<br>';
	}
	if( $msg == '' ) {
		set_student_meta($_SESSION["logged_in_students"]["id"], "password", $password);
		//unset($_SESSION["update_students"]["edit"]);
		header('Location: index.php?tab=student_record&id='.$_SESSION["logged_in_students"]["id"].'&msg='.url_encode("Password Changed Successfully"));
		die;
	}
	else{
		$_SESSION["student_record"]["err"]=$msg;
	}
	
}
if(isset($_SESSION["logged_in_students"]["id"]) && $_SESSION["logged_in_students"]["id"]!="" ){
	$rs=doquery("select * from student where id='".$_SESSION["logged_in_students"]["id"]."'",$dblink);
	if(numrows($rs)>0){
		$student=dofetch($rs);
	}
	else{
		header('Location: index.php?tab=student_record&id='.$_SESSION["logged_in_students"]["id"].'&msg='.url_encode("Successfully Updated"));
		die;
	}
}