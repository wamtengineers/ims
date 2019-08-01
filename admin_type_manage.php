<?php
$main_db = true;
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'admin_type_manage.php';
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
		include("modules/admin_type/add_do.php");
	break;
	case 'edit':
		include("modules/admin_type/edit_do.php");
	break;
	case 'delete':
		include("modules/admin_type/delete_do.php");
	break;
	case 'status':
		include("modules/admin_type/status_do.php");
	break;
	case 'bulk_action':
		include("modules/admin_type/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/admin_type/list.php");
                break;
                case 'add':
                    include("modules/admin_type/add.php");
                break;
                case 'edit':
                    include("modules/admin_type/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>