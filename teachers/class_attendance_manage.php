<?php
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
include("../inc/paging.php");
define("APP_START", 1);
$tab_array=array("list", "edit", "delete", "student_list", "report", "student_attendance", "print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
if( $tab != "list" && isset($_REQUEST[ "id" ]) ) {
	$sql="select a.id, a.title as section, b.class_name, c.title as class_level from class_section a inner join class b on a.class_id = b.id inner join class_level c on b.class_level_id=c.id inner join subject_teachers d on a.id=d.class_section_id where d.employee_id='".$_SESSION[ "logged_in_teachers" ][ "id" ]."' and is_class_teacher=1 and a.school_id = '".$_SESSION[ "current_school_id" ]."' and a.id='".slash($_REQUEST[ "id" ])."'";
	$record=doquery($sql, $dblink);
	if( numrows( $record ) > 0 ) {
		$record = dofetch( $record );
	}
	else{
		header( "Location: class_attendance_manage.php?err=".url_encode("You do not have rights to view this resource.") );
		die;
	}
}
switch($tab){
	case 'edit':
		include("modules/class_attendance/edit_do.php");
	break;
	case 'delete':
		include("modules/class_attendance/delete_do.php");
	break;
	case "student_list":
		$id = slash( $_REQUEST[ "id" ] );
		$date = slash( $_REQUEST[ "date" ] );
		$current_academic_year = current_academic_year();
		$student_list = array();
		$class_attendance = doquery( "select * from student_daily_attendance a inner join employee b on a.taken_by = b.id where class_section_id = '".$id."' and date='".date_dbconvert( $date )."'", $dblink );
		$students = doquery( "select b.* from student_2_class a inner join student b on a.student_id = b.id where class_section_id = '".$id."' and academic_year_id='".$current_academic_year[ "id" ]."' and a.status=1 and b.school_id = '".$_SESSION[ "current_school_id" ]."' order by b.student_name, b.father_name", $dblink );
		if( numrows( $students ) > 0 ) {
			while( $student = dofetch( $students ) ){
				if( numrows($class_attendance) == 0 || numrows( doquery( "select * from student_attendance where student_id='".$student[ "id" ]."' and date='".date_dbconvert( $date )."'", $dblink ) ) > 0 ) {
					$status = true;
				}
				else {
					$status = false;
				}
				$student_list[] = array(
					"id" => $student[ "id" ],
					"name" => unslash( $student[ "student_name" ] ),
					"father_name" => unslash( $student[ "father_name" ] ),
					"parent_name" => unslash( $student[ "father_name" ] ),
					"status" => $status
				);
			}
		}
		echo json_encode($student_list);
		die;
	break;
	case 'report':
		include("modules/class_attendance/report_do.php");
	break;
	case 'print':
		include("modules/class_attendance/print.php");
	break;
}
?>
<?php include("header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/class_attendance/list.php");
                break;
                case 'edit':
                    include("modules/class_attendance/edit.php");
                break;
				case 'report':
                    include("modules/class_attendance/report.php");
                break;
				case 'student_attendance':
                    include("modules/class_attendance/student_attendance.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("footer.php");?>