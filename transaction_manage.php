<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'transaction_manage.php';
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
	$_SESSION["transaction"]["list"]["date_from"]=$date_from;
}
if(isset($_SESSION["transaction"]["list"]["date_from"]))
	$date_from=$_SESSION["transaction"]["list"]["date_from"];
else
	$date_from="";
if($date_from != ""){
	$extra.=" and datetime_added>='".datetime_dbconvert($date_from)."'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["transaction"]["list"]["date_to"]=$date_to;
}
if(isset($_SESSION["transaction"]["list"]["date_to"]))
	$date_to=$_SESSION["transaction"]["list"]["date_to"];
else
	$date_to="";
if($date_to != ""){
	$extra.=" and datetime_added<'".datetime_dbconvert($date_to)."'";
}
if(isset($_GET["reference_id"])){
	$reference_id=slash($_GET["reference_id"]);
	$_SESSION["transaction"]["list"]["reference_id"]=$reference_id;
}
if(isset($_SESSION["transaction"]["list"]["reference_id"]))
	$reference_id=$_SESSION["transaction"]["list"]["reference_id"];
else
	$reference_id="";
if($reference_id!=""){
	$extra.=" and reference_id='".$reference_id."'";
	$is_search=true;
}
if(isset($_GET["account_id"])){
	$account_id=slash($_GET["account_id"]);
	$_SESSION["transaction"]["list"]["account_id"]=$account_id;
}
if(isset($_SESSION["transaction"]["list"]["account_id"]))
	$account_id=$_SESSION["transaction"]["list"]["account_id"];
else
	$account_id="";
if($account_id!=""){
	$extra.=" and account_id='".$account_id."'";
	$is_search=true;
}
$sql="select * from transaction where 1 $extra order by datetime_added desc";
switch($tab){
	case 'add':
		include("modules/transaction/add_do.php");
	break;
	case 'edit':
		include("modules/transaction/edit_do.php");
	break;
	case 'delete':
		include("modules/transaction/delete_do.php");
	break;
	case 'status':
		include("modules/transaction/status_do.php");
	break;
	case 'bulk_action':
		include("modules/transaction/bulkactions.php");
	break;
	case 'print':
		include("modules/transaction/print.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/transaction/list.php");
                break;
                case 'add':
                    include("modules/transaction/add.php");
                break;
                case 'edit':
                    include("modules/transaction/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>