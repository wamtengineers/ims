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
		include("modules/feedback/add_do.php");
	break;
	case 'edit':
		include("modules/feedback/edit_do.php");
	break;
	case 'delete':
		include("modules/feedback/delete_do.php");
	break;
	case 'status':
		include("modules/feedback/status_do.php");
	break;
	case 'bulk_action':
		include("modules/feedback/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div feedback="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/feedback/list.php");
                break;
                case 'add':
                    include("modules/feedback/add.php");
                break;
				case 'edit':
                    include("modules/feedback/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>