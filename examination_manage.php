<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'examination_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "result", "print_result");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/examination/add_do.php");
	break;
	case 'edit':
		include("modules/examination/edit_do.php");
	break;
	case 'delete':
		include("modules/examination/delete_do.php");
	break;
	case 'status':
		include("modules/examination/status_do.php");
	break;
	case 'bulk_action':
		include("modules/examination/bulkactions.php");
	break;
	case 'print_result':
		include("modules/examination/print_result.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div houses="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/examination/list.php");
                break;
                case 'add':
                    include("modules/examination/add.php");
                break;
                case 'edit':
                    include("modules/examination/edit.php");
                break;
				case 'result':
                    include("modules/examination/result.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>