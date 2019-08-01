<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_edit"])){
	extract($_POST);
	$err="";
	if(empty($department_id) || empty($designation_id) || empty($name) || empty($employee_code))
		$err="Fields with (*) are Mandatory.<br />";
	if(numrows(doquery("select id from employee where employee_code='".slash($employee_code)."' and id<>'".$id."'", $dblink))>0)
		$err.='Employee Code already exists.<br />';
	if(numrows(doquery("select id from employee where username='".slash($username)."' and id<>'".$id."'", $dblink))>0)
		$err.='Username already exists.<br />';
	if($err==""){
		$sql="Update employee set `department_id`='".slash($department_id)."',`designation_id`='".slash($designation_id)."', `name`='".slash($name)."', `father_name`='".slash($father_name)."', `employee_code`='".slash($employee_code)."',`username`='".slash($username)."'".(!empty($password)? ", `password`='".slash($password)."'":"")." where id='".$id."'";
		doquery($sql,$dblink);
		set_employee_meta($id, "surname", $surname);
		set_employee_meta($id, "birth_place", $birth_place);
		set_employee_meta($id, "gender", $gender);
		set_employee_meta($id, "date_of_birth", date_dbconvert($date_of_birth));
		set_employee_meta($id, "shift", $shift);
		set_employee_meta($id, "team_work_type", $team_work_type);
		set_employee_meta($id, "employee_type", $employee_type);
		set_employee_meta($id, "date_of_app", date_dbconvert($date_of_app));
		set_employee_meta($id, "subject", $subject);
		set_employee_meta($id, "level", $level);
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
		set_employee_meta($id, "present_leave", $present_leave);
		set_employee_meta($id, "leave_date", date_dbconvert($leave_date));
		set_employee_meta($id, "basic_salary", $basic_salary);
		set_employee_meta($id, "timing_from", $timing_from);
		set_employee_meta($id, "timing_to", $timing_to);
		unset($_SESSION["employee_manage"]["edit"]);
		header('Location: employee_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_manage"]["edit"][$key]=$value;
		header("Location: employee_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from employee where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$surname=get_employee_meta($id, "surname");
			$birth_place=get_employee_meta($id, "birth_place");
			$gender=get_employee_meta($id, "gender");
			$date_of_birth=date_convert(get_employee_meta($id, "date_of_birth"));
			$shift=get_employee_meta($id, "shift");
			$team_work_type=get_employee_meta($id, "team_work_type");
			$employee_type=get_employee_meta($id, "employee_type");
			$date_of_app=date_convert(get_employee_meta($id, "date_of_app"));
			$subject=get_employee_meta($id, "subject");
			$level=get_employee_meta($id, "level");
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
			$present_leave=get_employee_meta($id, "present_leave");
			$leave_date=date_convert(get_employee_meta($id, "leave_date"));
			$basic_salary=get_employee_meta($id, "basic_salary");
			$timing_from=get_employee_meta($id, "timing_from");
			$timing_to=get_employee_meta($id, "timing_to");
		if(isset($_SESSION["employee_manage"]["edit"]))
			extract($_SESSION["employee_manage"]["edit"]);
	}
	else{
		header("Location: employee_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: employee_manage.php?tab=list");
	die;
}