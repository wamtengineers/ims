<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'employee_reports_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "daily", "salary", "delete", "bulk_action");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'delete':
		include("modules/employee_reports/delete_do.php");
	break;
	case 'bulk_action':
		include("modules/employee_reports/bulkactions.php");
	break;
	case 'salary':
		include("modules/employee_reports/salary_do.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee_reports/list.php");
                break;
				case 'daily':
                    include("modules/employee_reports/daily.php");
                break;
				case 'salary':
                    include("modules/employee_reports/salary.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>