<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'employee_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "profile_image_upload", "status", "delete", "bulk_action", "idcard", "bulk_update");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$sql="select * from employee where status=1";
switch($tab){
	case 'add':
		include("modules/employee/add_do.php");
	break;
	case 'edit':
		include("modules/employee/edit_do.php");
	break;
	case "profile_image_upload":
		if( isset( $_POST[ "id" ] ) ) {
			$id = slash( $_POST[ "id" ] );
			if( !empty( $_POST[ "img" ] ) ) {
				list( $img_type, $code) = explode(';', $_POST[ "img" ] );
				$ext = "jpg";
				switch( $img_type ) {
					case "data:image/jpeg":
					case "data:image/jpg": $ext = "jpg"; break;
					case "data:image/png": $ext = "png"; break;
				}
				list(, $code)      = explode(',', $code);
				$employee_image = $id.'.'.$ext;
				$path = $file_upload_root."employee/".$employee_image;
				$code = base64_decode($code);			
				file_put_contents( $path, $code);
				set_employee_meta( $id, "employee_image", $employee_image );
				die;
			}
		}
	break;
	case 'delete':
		include("modules/employee/delete_do.php");
	break;
	case 'status':
		include("modules/employee/status_do.php");
	break;
	case 'bulk_action':
		include("modules/employee/bulkactions.php");
	break;
	case 'idcard':
		include("modules/employee/idcard.php");
	break;
	case 'bulk_update':
		include("modules/employee/bulk_update_do.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee/list.php");
                break;
                case 'add':
                    include("modules/employee/add.php");
                break;
                case 'edit':
                    include("modules/employee/edit.php");
                break;
				case 'bulk_update':
                    include("modules/employee/bulk_update.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>