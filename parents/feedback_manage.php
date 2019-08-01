<?php
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
include("../inc/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "delete");
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
	case 'delete':
		include("modules/feedback/delete_do.php");
	break;
}
?>
<?php include("header.php");?>
		<div feedback="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/feedback/list.php");
                break;
                case 'add':
                    include("modules/feedback/add.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("footer.php");?>