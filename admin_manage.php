<?php
$main_db = true;
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'admin_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "send_email");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/admin/add_do.php");
	break;
	case 'edit':
		include("modules/admin/edit_do.php");
	break;
	case 'delete':
		include("modules/admin/delete_do.php");
	break;
	case 'status':
		include("modules/admin/status_do.php");
	break;
	case 'bulk_action':
		include("modules/admin/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/admin/list.php");
                break;
                case 'add':
                    include("modules/admin/add.php");
                break;
                case 'edit':
                    include("modules/admin/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>