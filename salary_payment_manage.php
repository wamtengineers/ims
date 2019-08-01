<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'salary_payment_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
if( $_SESSION[ "current_school_id" ] != 0 ) {
	$extra=" and school_id='".$_SESSION[ "current_school_id" ]."'";
}
$is_search=false;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["salary_payment"]["list"]["date_from"]=$date_from;
}
if(isset($_SESSION["salary_payment"]["list"]["date_from"]))
	$date_from=$_SESSION["salary_payment"]["list"]["date_from"];
else
	$date_from="";
if($date_from != ""){
	$extra.=" and datetime_added>='".datetime_dbconvert($date_from)."'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["salary_payment"]["list"]["date_to"]=$date_to;
}
if(isset($_SESSION["salary_payment"]["list"]["date_to"]))
	$date_to=$_SESSION["salary_payment"]["list"]["date_to"];
else
	$date_to="";
if($date_to != ""){
	$extra.=" and datetime_added<'".datetime_dbconvert($date_to)."'";
}
if(isset($_GET["employee_id"])){
	$employee_id=slash($_GET["employee_id"]);
	$_SESSION["salary_payment_list_employee_id"]=$employee_id;
}
if(isset($_SESSION["salary_payment_list_employee_id"])){
	$employee_id=$_SESSION["salary_payment_list_employee_id"];
}
else
	$employee_id="";
if(!empty($employee_id))
	$extra.=" and employee_id = '".$employee_id."'";
	$is_search=true;
$sql="select * from salary_payment where 1 ".$extra." order by datetime_added desc";
switch($tab){
	case 'add':
		include("modules/salary_payment/add_do.php");
	break;
	case 'edit':
		include("modules/salary_payment/edit_do.php");
	break;
	case 'delete':
		include("modules/salary_payment/delete_do.php");
	break;
	case 'status':
		include("modules/salary_payment/status_do.php");
	break;
	case 'bulk_action':
		include("modules/salary_payment/bulkactions.php");
	break;
	case 'print':
		include("modules/salary_payment/print.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/salary_payment/list.php");
                break;
                case 'add':
                    include("modules/salary_payment/add.php");
                break;
                case 'edit':
                    include("modules/salary_payment/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>