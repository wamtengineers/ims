<?php
function check_students_cookie(){
	global $dblink;
	if(isset($_COOKIE["_students_logged_in"])){
		$r=doquery("select * from students where id='".$_COOKIE["_students_logged_in"]."'", $dblink);
		if(numrows($r)>0){
			$r=dofetch($r);
			$_SESSION["logged_in_students"]=$r;
			return true;
		}
	}
	return false;
}
if(!isset($_SESSION["logged_in_students"])){
	if(!check_students_cookie()){
		header("Location: login.php");
		die;
	}	
}