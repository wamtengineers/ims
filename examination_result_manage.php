<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'examination_result_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "result");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/examination_result/add_do.php");
	break;
	case 'edit':
		include("modules/examination_result/edit_do.php");
	break;
	case 'delete':
		include("modules/examination_result/delete_do.php");
	break;
	case 'status':
		include("modules/examination_result/status_do.php");
	break;
	case 'bulk_action':
		include("modules/examination_result/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div houses="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/examination_result/list.php");
                break;
                case 'add':
                    include("modules/examination_result/add.php");
                break;
                case 'edit':
                    include("modules/examination_result/edit.php");
                break;
				case 'result':
                    include("modules/examination_result/result.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>