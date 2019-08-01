<?php
function check_teachers_cookie(){
	global $dblink;
	if(isset($_COOKIE["_parents_logged_in"])){
		$r=doquery("select * from parents where id='".$_COOKIE["_parents_logged_in"]."'", $dblink);
		if(numrows($r)>0){
			$r=dofetch($r);
			$_SESSION["logged_in_parents"]=$r;
			return true;
		}
	}
	return false;
}
if(!isset($_SESSION["logged_in_parents"])){
	if(!check_teachers_cookie()){
		header("Location: login.php");
		die;
	}	
}