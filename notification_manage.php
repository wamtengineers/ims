<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'notification_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "send_sms", "student", "student_list", "employee", "employee_list");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/notification/add_do.php");
	break;
	case 'edit':
		include("modules/notification/edit_do.php");
	break;
	case 'delete':
		include("modules/notification/delete_do.php");
	break;
	case 'status':
		include("modules/notification/status_do.php");
	break;
	case 'bulk_action':
		include("modules/notification/bulkactions.php");
	break;
	case 'send_sms':
		include("modules/notification/send_sms.php");
	break;
	case 'student':
		include("modules/notification/student_do.php");
	break;
	case 'employee':
		include("modules/notification/employee_do.php");
	break;
	case "student_list":
		$id = slash( $_REQUEST[ "id" ] );
		$type = slash( $_REQUEST[ "type" ] );
		$current_academic_year = current_academic_year();
		$student_list = array();
		$r = dofetch( doquery( "select * from notification where id = '".$id."'", $dblink ) );
		$class_attendance = doquery( "select class_section_id from notification_2_class_section where notification_id = '".$r["id"]."'", $dblink );
		$students = doquery("select * from student where id in (select student_id from student_2_class where academic_year_id = '".$current_academic_year[ "id" ]."'".(numrows($class_attendance)>0?" and class_section_id in (select class_section_id from notification_2_class_section where notification_id = '".$r["id"]."')":"").')', $dblink);
		$null = doquery( "select * from notification_2_student where  notification_id = '".$id."'", $dblink );
		$null = numrows( $null ) == 0;
		if( numrows( $students ) > 0 ) {
			while( $student = dofetch( $students ) ){
				$balance = 0;
				if( $type == 0 ) {
					if( $null || numrows( doquery( "select * from notification_2_student where student_id='".$student[ "id" ]."' and notification_id = '".$id."'", $dblink ) ) > 0 ) {
						$status =true ;
					}
					else {
						$status = false;
					}
				}
				else if( $type == 1 ){
					$balance = get_student_balance( $student[ "id" ], $r[ "date" ] );
					if( $balance > 0 ) {
						$status =true ;
					}
					else {
						$status = false;
					}
				}
				$student_list[] = array(
					"id" => $student[ "id" ],
					"name" => unslash( $student[ "student_name" ] ),
					"father_name" => unslash( $student[ "father_name" ] ),
					"parent_name" => unslash( $student[ "father_name" ] ),
					"status" => $status,
					"balance" => $balance
				);
			}
		}
		echo json_encode($student_list);
		die;
	break;
	case "employee_list":
		$id = slash( $_REQUEST[ "id" ] );
		//$date = slash( $_REQUEST[ "date" ] );
		$employee_list = array();
		$r = dofetch( doquery( "select * from notification where id = '".$id."'", $dblink ) );
		$check = doquery( "select department_id from notification_2_department where notification_id = '".$id."'", $dblink );
		$employees = doquery("select * from employee where status=1".(numrows($check)>0?" and department_id in (select department_id from notification_2_department where notification_id = '".$r[ "id" ]."')":""), $dblink);
		if( numrows( $employees ) > 0 ) {
			while( $employee = dofetch( $employees ) ){
				if( numrows($check) == 0 || numrows( doquery( "select * from notification_2_employee where employee_id='".$employee["id"]."' and notification_id = '".$id."'", $dblink ) ) > 0 ) {
					$status =true;
				}
				else {
					$status = false;
				}
				$employee_list[] = array(
					"id" => $employee[ "id" ],
					"name" => unslash( $employee[ "name" ] ),
					"father_name" => unslash( $employee[ "father_name" ] ),
					"parent_name" => unslash( $employee[ "father_name" ] ),
					"status" => $status
				);
			}
		}
		echo json_encode($employee_list);
		die;
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/notification/list.php");
                break;
                case 'add':
                    include("modules/notification/add.php");
                break;
                case 'edit':
                    include("modules/notification/edit.php");
                break;
				case 'student':
					include("modules/notification/student.php");
				break;
				case 'employee':
					include("modules/notification/employee.php");
				break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>