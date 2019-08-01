<?php
function check_teachers_cookie(){
	global $dblink;
	if(isset($_COOKIE["_teachers_logged_in"])){
		$r=doquery("select * from employee where id='".$_COOKIE["_teachers_logged_in"]."' and school_id = '".$_SESSION["current_school_id"]."'", $dblink);
		if(numrows($r)>0){
			$r=dofetch($r);
			$_SESSION["logged_in_teachers"]=$r;
			return true;
		}
	}
	return false;
}
if(!isset($_SESSION["logged_in_teachers"])){
	if(!check_teachers_cookie()){
		header("Location: login.php");
		die;
	}	
}
?>