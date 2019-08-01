<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'subject_teachers_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "edit");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	
	case 'edit':
		include("modules/subject_teachers/edit_do.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div subject="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/subject_teachers/list.php");
                break;
                case 'edit':
                    include("modules/subject_teachers/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>