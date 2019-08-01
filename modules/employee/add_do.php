<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_add"])){
	extract($_POST);
	$err="";
	if(empty($department_id) || empty($designation_id) || empty($name) || empty($username) || empty($employee_code))
		$err="Fields with (*) are Mandatory.<br />";
	if(numrows(doquery("select id from employee where employee_code='".slash($employee_code)."'", $dblink))>0)
		$err.='Employee Code already exists.<br />';
	if(numrows(doquery("select id from employee where username='".slash($username)."'", $dblink))>0)
		$err.='Username already exists.<br />';
	if($err==""){
		$sql="INSERT INTO employee (school_id, department_id, designation_id, name, father_name, employee_code, username, password) VALUES ('".$_SESSION["current_school_id"]."', '".slash($department_id)."','".slash($designation_id)."','".slash($name)."','".slash($father_name)."','".slash($employee_code)."','".slash($username)."','".slash($password)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
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
		unset($_SESSION["employee_manage"]["add"]);
		header('Location: employee_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_manage"]["add"][$key]=$value;
		header('Location: employee_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}
if(isset($_SESSION["employee_manage"]["add"])){
	extract($_SESSION["employee_manage"]["add"]);	
}
else{
	$department_id="";
	$designation_id="";
	$name="";
	$father_name="";
	$employee_code="";
	$username="";
	$password="";
	$surname="";
	$birth_place="";
	$gender="";
	$date_of_birth=date("d/m/Y");
	$shift="";
	$team_work_type="";
	$employee_type="";
	$date_of_app=date("d/m/Y");
	$subject="";
	$level="";
	$address="";
	$mobile_number="";
	$telephone_number="";
	$religion="";
	$nationality="";
	$cnic_number="";
	$cnic_expiry_date=date("d/m/Y");
	$qualification="";
	$work_experiance="";
	$blood_group="";
	$present_leave="";
	$leave_date=date("d/m/Y");
	$basic_salary="";
	$timing_from="";
	$timing_to="";
}