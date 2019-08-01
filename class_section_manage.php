<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'class_section_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action","timetable");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
if(isset($_GET["class_id"])){
	$class_id=slash($_GET["class_id"]);
	$_SESSION["class_section_list_class_id"]=$class_id;
}
if(isset($_SESSION["class_section_list_class_id"])){
	$class_id=$_SESSION["class_section_list_class_id"];
}
else
	$class_id="";
if(!empty($class_id))
	$extra.=" and a.class_id like '%".$class_id."%'";
	
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["class_section_manage"]["q"]=$q;
}
if(isset($_SESSION["class_section_manage"]["q"]))
	$q=$_SESSION["class_section_manage"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and title like '%".$q."%'";
	$is_search=true;
}
switch($tab){
	case 'add':
		include("modules/class_section/add_do.php");
	break;
	case 'edit':
		include("modules/class_section/edit_do.php");
	break;
	case 'delete':
		include("modules/class_section/delete_do.php");
	break;
	case 'status':
		include("modules/class_section/status_do.php");
	break;
	case 'bulk_action':
		include("modules/class_section/bulkactions.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class_section="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/class_section/list.php");
                break;
                case 'add':
                    include("modules/class_section/add.php");
                break;
                case 'edit':
                    include("modules/class_section/edit.php");
                break;
				case 'timetable':
                    include("modules/class_section/timetable.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>