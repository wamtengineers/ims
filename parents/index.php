<?php 
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
define("APP_START", 1);
$tab_array=array("student", "result", "attendance", "student_profile", "feedback");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="student";
}
$academic_year = current_academic_year();
switch($tab){
	case 'result':
		include("modules/student/result.php");
	break;
	case 'student_profile':
		include("modules/student/student_profile_do.php");
	break;
	case 'feedback':
		include("modules/student/feedback_do.php");
	break;
}
?>
<?php include("header.php");?>
<div class="main-content-inner">
    	<?php
		switch($tab){
			case 'student':
				include("modules/student/student.php");
			break;
			case 'attendance':
				include("modules/student/attendance.php");
			break;
			case 'student_profile':
				include("modules/student/student_profile.php");
			break;
			case 'feedback':
				include("modules/student/feedback.php");
			break;
		}
	  ?>
</div>
<?php include("footer.php");?>