<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'academic_year_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "current_year", "view", "print", "promote", "import");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/academic_year/add_do.php");
	break;
	case 'edit':
		include("modules/academic_year/edit_do.php");
	break;
	case 'delete':
		include("modules/academic_year/delete_do.php");
	break;
	case 'status':
		include("modules/academic_year/status_do.php");
	break;
	case 'bulk_action':
		include("modules/academic_year/bulkactions.php");
	break;
	case 'current_year':
		include("modules/academic_year/current_year.php");
	break;
	case 'view':
		include("modules/academic_year/view_do.php");
	break;
	case 'print':
		include("modules/academic_year/print.php");
	break;
	case 'import':
		include("modules/academic_year/import_do.php");
	break;
	case 'promote':
		include("modules/academic_year/promote_do.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/academic_year/list.php");
                break;
                case 'add':
                    include("modules/academic_year/add.php");
                break;
                case 'edit':
                    include("modules/academic_year/edit.php");
                break;
				case 'view':
                    include("modules/academic_year/view.php");
                break;
				case "promote":
					include("modules/academic_year/promote.php");
				break;
				case "import":
					include("modules/academic_year/import.php");
				break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>