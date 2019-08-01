<?php
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
define("APP_START", 1);
$tab_array=array("profile_edit");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="profile_edit";
}
switch($tab){
	case 'profile_edit':
		include("modules/profile/profile_edit_do.php");
	break;
}
?>
<?php include("header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'profile_edit':
                    include("modules/profile/profile_edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("footer.php");?>