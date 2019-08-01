<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'fees_chalan_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "generate", "receiving", "edit", "status", "delete", "bulk_action", "print_report", "print_report_class_wise", "edit_receiving");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$extra='';
if( $_SESSION[ "current_school_id" ] != 0 ) {
	$extra=" and a.school_id='".$_SESSION[ "current_school_id" ]."'";
}
if( isset( $_GET[ "parent_id" ] ) ) {
	$student_2_class = doquery( "select * from student_2_class where id = '".slash( $_GET[ "parent_id" ] )."'", $dblink );
	if( numrows( $student_2_class ) > 0 ) {
		$student_2_class = dofetch( $student_2_class );
		$_SESSION["fees_chalan_list_academic_year_id"]=$student_2_class[ "academic_year_id" ];
		$_SESSION["fees_chalan_list_student_2_class_id"]=$student_2_class[ "id" ];
	}
}
$is_search=false;
if(isset($_GET["academic_year_id"])){
	$_SESSION["fees_chalan_list_academic_year_id"]=slash($_GET["academic_year_id"]);
}
if(isset($_SESSION["fees_chalan_list_academic_year_id"])){
	$academic_year_id=$_SESSION["fees_chalan_list_academic_year_id"];
}
else {
	$academic_year = current_academic_year();
	$academic_year_id=$academic_year[ "id" ];
}
if(!empty($academic_year_id)) {
	$extra.=" and academic_year_id = '".$academic_year_id."'";
	$is_search=true;
}

if(isset($_GET["fee_month"])){
	$_SESSION["fees_chalan_list_fee_month"]=slash($_GET["fee_month"]);
}
if(isset($_SESSION["fees_chalan_list_fee_month"])){
	$fee_month=$_SESSION["fees_chalan_list_fee_month"];
}
else {
	$fee_month = '';
}
if(!empty($fee_month)) {
	$extra.=" and month = '".$fee_month."'";
	$is_search=true;
}

if(isset($_GET["class_section_id"])){
	$_SESSION["fees_chalan_list_class_section_id"]=slash($_GET["class_section_id"]);
}
if(isset($_SESSION["fees_chalan_list_class_section_id"])){
	$class_section_id=$_SESSION["fees_chalan_list_class_section_id"];
}
else {
	$class_section_id = '';
}
if(!empty($class_section_id)) {
	$extra.=" and student_2_class_id in (select id from student_2_class where class_section_id = '".$class_section_id."' and academic_year_id = '".$academic_year_id."' )";
	$is_search=true;
}
if(isset($_GET["issue_date_from"])){
	$_SESSION["fees_chalan_list_issue_date_from"]=slash($_GET["issue_date_from"]);
}
if(isset($_SESSION["fees_chalan_list_issue_date_from"])){
	$issue_date_from=$_SESSION["fees_chalan_list_issue_date_from"];
}
else {
	$issue_date_from="";
}
if(!empty($issue_date_from)) {
	$extra.=" and issue_date >= '".date_dbconvert( $issue_date_from )."'";
	$is_search=true;
}
if(isset($_GET["issue_date_to"])){
	$_SESSION["fees_chalan_list_issue_date_to"]=slash($_GET["issue_date_to"]);
}
if(isset($_SESSION["fees_chalan_list_issue_date_to"])){
	$issue_date_to=$_SESSION["fees_chalan_list_issue_date_to"];
}
else {
	$issue_date_to="";
}
if(!empty($issue_date_to)) {
	$extra.=" and issue_date <= '".date_dbconvert( $issue_date_to )."'";
	$is_search=true;
}
if(isset($_GET["payment_date_from"])){
	$_SESSION["fees_chalan_list_payment_date_from"]=slash($_GET["payment_date_from"]);
}
if(isset($_SESSION["fees_chalan_list_payment_date_from"])){
	$payment_date_from=$_SESSION["fees_chalan_list_payment_date_from"];
}
else {
	$payment_date_from="";
}
if(!empty($payment_date_from)) {
	$extra.=" and payment_date >= '".date_dbconvert( $payment_date_from )."'";
	$is_search=true;
}
if(isset($_GET["payment_date_to"])){
	$_SESSION["fees_chalan_list_payment_date_to"]=slash($_GET["payment_date_to"]);
}
if(isset($_SESSION["fees_chalan_list_payment_date_to"])){
	$payment_date_to=$_SESSION["fees_chalan_list_payment_date_to"];
}
else {
	$payment_date_to="";
}
if(!empty($payment_date_to)) {
	$extra.=" and payment_date <= '".date_dbconvert( $payment_date_to )."'";
	$is_search=true;
}
if(isset($_GET["student_2_class_id"])){
	$_SESSION["fees_chalan_list_student_2_class_id"]=slash($_GET["student_2_class_id"]);
}
if(isset($_SESSION["fees_chalan_list_student_2_class_id"])){
	$student_2_class_id=$_SESSION["fees_chalan_list_student_2_class_id"];
}
else
	$student_2_class_id="";
if(!empty($student_2_class_id)) {
	$student_2_class = dofetch( doquery( "select * from student_2_class where id = '".slash( $student_2_class_id )."'", $dblink ) );
	$extra.=" and student_2_class_id = '".$student_2_class_id."'";
	$is_search=true;
}
if(isset($_GET["is_received"])){
	$_SESSION["fees_chalan_list_is_received"]=slash($_GET["is_received"]);
}
if(isset($_SESSION["fees_chalan_list_is_received"])){
	$is_received=$_SESSION["fees_chalan_list_is_received"];
}
else
	$is_received="";
if($is_received != "") {
	if( $is_received == "1" )
		$extra.=" and b.id is not null";
	else
		$extra.=" and b.id is null";
	$is_search=true;
}
/*if(isset($_GET["status"])){
	$status=slash($_GET["status"]);
	$_SESSION["fees_chalan"]["list"]["status"]=$status;
}
if(isset($_SESSION["fees_chalan"]["list"]["status"])){
	$status=$_SESSION["fees_chalan"]["list"]["status"];
}
else{
	$status=1;
}	
$extra.=" and a.status='".$status."'";*/
$order_by = "month, issue_date";
$order = "desc";
if( isset($_GET["order_by"]) ){
	$_SESSION["student_manage"]["list"]["order_by"]=slash($_GET["order_by"]);
}
if( isset( $_SESSION["student_manage"]["list"]["order_by"] ) ){
	$order_by = $_SESSION["student_manage"]["list"]["order_by"];
}
if( isset($_GET["order"]) ){
	$_SESSION["student_manage"]["list"]["order"]=slash($_GET["order"]);
}
if( isset( $_SESSION["student_manage"]["list"]["order"] ) ){
	$order = $_SESSION["student_manage"]["list"]["order"];
}
$orderby = $order_by." ".$order;
$sql="select a.*, b.payment_date, b.amount from fees_chalan a left join fees_chalan_receiving b on a.id = b.fees_chalan_id where 1 ".$extra." order by $orderby";
switch($tab){
	case 'generate':
		include("modules/fees_chalan/generate.php");
	break;
	case 'edit':
		include("modules/fees_chalan/edit_do.php");
	break;
	case 'receiving':
		include("modules/fees_chalan/receiving_do.php");
	break;
	case 'delete':
		include("modules/fees_chalan/delete_do.php");
	break;
	case 'status':
		include("modules/fees_chalan/status_do.php");
	break;
	case 'bulk_action':
		include("modules/fees_chalan/bulkactions.php");
	break;
	case 'print_report':
		include("modules/fees_chalan/report.php");
	break;
	case 'print_report_class_wise':
		include("modules/fees_chalan/class-report.php");
	break;
	case 'edit_receiving':
		include("modules/fees_chalan/edit_receiving_do.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/fees_chalan/list.php");
                break;
                case 'edit':
                    include("modules/fees_chalan/edit.php");
                break;
				case 'receiving':
					include("modules/fees_chalan/receiving.php");
				break;
				case 'edit_receiving':
					include("modules/fees_chalan/edit_receiving.php");
				break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>