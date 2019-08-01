<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'class_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "edit", "delete", "student_list");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
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
		$class_attendance = doquery( "select * from student_daily_attendance a inner join admin b on a.taken_by = b.id where class_section_id = '".$id."' and date='".date_dbconvert( $date )."'", $dblink );
		$students = doquery( "select b.* from student_2_class a inner join student b on a.student_id = b.id where class_section_id = '".$id."' and academic_year_id='".$current_academic_year[ "id" ]."' and b.status=1 order by b.student_name, b.father_name", $dblink );
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
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/class_attendance/list.php");
                break;
                case 'edit':
                    include("modules/class_attendance/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>