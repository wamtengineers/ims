<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/config_type/add_do.php");
	break;
	case 'edit':
		include("modules/config_type/edit_do.php");
	break;
	case 'delete':
		include("modules/config_type/delete_do.php");
	break;
	case 'bulk_action':
		include("modules/config_type/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/config_type/list.php");
                break;
                case 'add':
                    include("modules/config_type/add.php");
                break;
                case 'edit':
                    include("modules/config_type/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>