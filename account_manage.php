<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'account_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/account/add_do.php");
	break;
	case 'edit':
		include("modules/account/edit_do.php");
	break;
	case 'delete':
		include("modules/account/delete_do.php");
	break;
	case 'status':
		include("modules/account/status_do.php");
	break;
	case 'bulk_action':
		include("modules/account/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/account/list.php");
                break;
                case 'add':
                    include("modules/account/add.php");
                break;
                case 'edit':
                    include("modules/account/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>