<?php
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
define("APP_START", 1);
$tab_array=array("profile_edit", "profile_image_upload", "idcard");
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
	case "profile_image_upload":
		if( isset( $_POST[ "id" ] ) ) {
			$id = $_SESSION["logged_in_teachers"]["id"];
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
				$path = $file_upload_url."employee/".$employee_image;
				$code = base64_decode($code);			
				file_put_contents( $path, $code);
				set_employee_meta( $id, "employee_image", $employee_image );
				die;
			}
		}
	break;
	case 'idcard':
		include("modules/profile/idcard.php");
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