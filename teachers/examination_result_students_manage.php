<?php
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
include("../inc/paging.php");
define("APP_START", 1);
$current_academic_year = current_academic_year();
$current_academic_year_id = $current_academic_year[ "id" ];
$filename = 'examination_result_students_manage.php';
$class_teacher=doquery("select a.class_section_id, b.title as section, c.class_name as class from subject_teachers a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id where employee_id = '".$_SESSION[ "logged_in_teachers" ][ "id" ]."' and is_class_teacher='1'",$dblink);
if( numrows( $class_teacher ) > 0 ) {
	$class_teacher = dofetch( $class_teacher );
}
else{
	header( "Location: index.php" );	
	die;
}
$tab_array=array("list", "edit");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
switch($tab){
	case 'edit':
		include("modules/examination_result_students/edit_do.php");
	break;
}
?>
<?php include("header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/examination_result_students/list.php");
                break;
                case 'edit':
                    include("modules/examination_result_students/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("footer.php");?>