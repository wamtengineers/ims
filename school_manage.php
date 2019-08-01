<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'school_manage.php';
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
		include("modules/school/add_do.php");
	break;
	case 'edit':
		include("modules/school/edit_do.php");
	break;
	case 'delete':
		include("modules/school/delete_do.php");
	break;
	case 'status':
		include("modules/school/status_do.php");
	break;
	case 'bulk_action':
		include("modules/school/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/school/list.php");
                break;
                case 'add':
                    include("modules/school/add.php");
                break;
                case 'edit':
                    include("modules/school/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>