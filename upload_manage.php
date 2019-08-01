<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "delete", "bulk_action", "upload_center");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/upload/add_do.php");
	break;
	case 'delete':
		include("modules/upload/delete_do.php");
	break;
	case 'upload_center':
		include("modules/upload/upload_center_do.php");
	break;
	case 'bulk_action':
		include("modules/upload/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
  <div class="main-content-inner">
      <?php
		switch($tab){
			case 'list':
				include("modules/upload/list.php");
			break;
			case 'add':
				include("modules/upload/add.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("inc/footer.php");?>