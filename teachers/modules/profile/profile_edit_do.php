<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["profile_edit"])){
	extract($_POST);
	$err="";
	if(empty($name) || empty($father_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update employee set `name`='".slash($name)."', `father_name`='".slash($father_name)."'".(!empty($password)? ", `password`='".slash($password)."'":"")." where id='".$_SESSION["logged_in_teachers"]["id"]."'";
		doquery($sql,$dblink);
		set_employee_meta($id, "surname", $surname);
		set_employee_meta($id, "birth_place", $birth_place);
		set_employee_meta($id, "gender", $gender);
		set_employee_meta($id, "date_of_birth", date_dbconvert($date_of_birth));
		set_employee_meta($id, "subject", $subject);
		set_employee_meta($id, "address", $address);
		set_employee_meta($id, "mobile_number", $mobile_number);
		set_employee_meta($id, "telephone_number", $telephone_number);
		set_employee_meta($id, "religion", $religion);
		set_employee_meta($id, "nationality", $nationality);
		set_employee_meta($id, "cnic_number", $cnic_number);
		set_employee_meta($id, "cnic_expiry_date", date_dbconvert($cnic_expiry_date));
		set_employee_meta($id, "qualification", $qualification);
		set_employee_meta($id, "work_experiance", $work_experiance);
		set_employee_meta($id, "blood_group", $blood_group);
		unset($_SESSION["profile"]["edit"]);
		header('Location: profile.php?tab=profile_edit&msg='.url_encode("Successfully Updated")."&id='".$_SESSION["logged_in_teachers"]["id"]."'");
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["profile"]["edit"][$key]=$value;
		header("Location: profile.php?tab=profile_edit&err=".url_encode($err)."&id='".$_SESSION["logged_in_teachers"]["id"]."'");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from employee where id='".$_SESSION["logged_in_teachers"]["id"]."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$surname=get_employee_meta($id, "surname");
			$birth_place=get_employee_meta($id, "birth_place");
			$gender=get_employee_meta($id, "gender");
			$date_of_birth=date_convert(get_employee_meta($id, "date_of_birth"));
			$subject=get_employee_meta($id, "subject");
			$address=get_employee_meta($id, "address");
			$mobile_number=get_employee_meta($id, "mobile_number");
			$telephone_number=get_employee_meta($id, "telephone_number");
			$religion=get_employee_meta($id, "religion");
			$nationality=get_employee_meta($id, "nationality");
			$cnic_number=get_employee_meta($id, "cnic_number");
			$cnic_expiry_date=date_convert(get_employee_meta($id, "cnic_expiry_date"));
			$qualification=get_employee_meta($id, "qualification");
			$work_experiance=get_employee_meta($id, "work_experiance");
			$blood_group=get_employee_meta($id, "blood_group");
		if(isset($_SESSION["profile"]["edit"]))
			extract($_SESSION["profile"]["edit"]);
	}
	else{
		header('Location: profile.php?tab=profile_edit&msg='.url_encode("Successfully Updated")."&id=".$_SESSION["logged_in_teachers"]["id"]."");
		die;
	}
}
else{
	header("Location: profile.php?id=".$_SESSION["logged_in_teachers"]["id"]."");
	die;
}