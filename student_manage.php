<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'student_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "idcard", "fees_chalan", "leaving_certificate", "character_certificate", "marksheet", "final_marksheet", "print", "profile_image_upload", "mail_envelop", "result_envelop", "month_star");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$academic_year = current_academic_year();
$extra='';
if( $_SESSION[ "current_school_id" ] != 0 ) {
	$extra=" and a.school_id='".$_SESSION[ "current_school_id" ]."'";
}
if(isset($_GET["class_section_id"])){
	$class_section_id=slash($_GET["class_section_id"]);
	$_SESSION["student_manage"]["list"]["class_section_id"]=$class_section_id;
}
if(isset($_SESSION["student_manage"]["list"]["class_section_id"])){
	$class_section_id=$_SESSION["student_manage"]["list"]["class_section_id"];
}
else{
	$class_section_id="";
}
if(!empty($class_section_id)){
	$extra.=" and b.class_section_id='".$class_section_id."'";
}

if(isset($_GET["year_id"])){
	$year_id=slash($_GET["year_id"]);
	$_SESSION["student_manage"]["list"]["year_id"]=$year_id;
}
if(isset($_SESSION["student_manage"]["list"]["year_id"])){
	$year_id=$_SESSION["student_manage"]["list"]["year_id"];
}
else{
	$year_id=$academic_year["id"];
}
$join = "";
if( !empty( $year_id ) ) {
	$join .= " inner join student_2_class b on a.id=b.student_id and b.academic_year_id='".$year_id."'";
}

else if( !empty( $class_section_id ) ) {
	$join .= " inner join student_2_class b on a.id=b.student_id";
}
else {
	$join .= " left join student_2_class b on a.id=b.student_id";
}
$join .= ' left join class_section a1 on b.class_section_id = a1.id left join class a2 on a1.class_id = a2.id';
if(isset($_GET["student_status"])){
	$student_status=slash($_GET["student_status"]);
	$_SESSION["student_manage"]["list"]["student_status"]=$student_status;
}
if(isset($_SESSION["student_manage"]["list"]["student_status"])){
	$student_status=$_SESSION["student_manage"]["list"]["student_status"];
}
else{
	$student_status=1;
}	
$extra.=" and a.status='".$student_status."'";
if(isset($_GET["is_advanced_search"])){
	$is_advanced_search=slash($_GET["is_advanced_search"]);
	$_SESSION["student_manage"]["list"]["is_advanced_search"]=$is_advanced_search;
}
if(isset($_SESSION["student_manage"]["list"]["is_advanced_search"])){
	$is_advanced_search=$_SESSION["student_manage"]["list"]["is_advanced_search"];	
}
else
	$is_advanced_search=0;
if(isset($_GET["gender"])){
	$gender=slash($_GET["gender"]);
	$_SESSION["student_manage"]["list"]["gender"]=$gender;
}
if(isset($_SESSION["student_manage"]["list"]["gender"])){
	$gender=$_SESSION["student_manage"]["list"]["gender"];	
}
else
	$gender='';
if( !empty( $gender ) ) {
	$extra .= " and gender='".slash($gender)."'";
}
if(isset($_GET["house_id"])){
	$house_id=slash($_GET["house_id"]);
	$_SESSION["student_manage"]["list"]["house_id"]=$house_id;
}
if(isset($_SESSION["student_manage"]["list"]["house_id"])){
	$house_id=$_SESSION["student_manage"]["list"]["house_id"];	
}
else
	$house_id='';
if( !empty( $house_id ) ) {
	$join .= " inner join student_meta c on a.id=c.student_id and c.meta_key='houses_id' and c.meta_value='".$house_id."'";
}
/*if(isset($_GET["group_id"])){
	$group_id=slash($_GET["group_id"]);
	$_SESSION["student_manage"]["list"]["group_id"]=$group_id;
}
if(isset($_SESSION["student_manage"]["list"]["group_id"])){
	$group_id=$_SESSION["student_manage"]["list"]["group_id"];
}
else{
	$group_id="";
}
$join = "";
if( !empty( $group_id ) ) {
	$join .= " inner join student_2_class e on a.id=e.student_id left join `group` f on a.id = e.group_id";
}*/
if(isset($_GET["taekwondo_fees_id"])){
	$taekwondo_fees_id=slash($_GET["taekwondo_fees_id"]);
	$_SESSION["student_manage"]["list"]["taekwondo_fees_id"]=$taekwondo_fees_id;
}
if(isset($_SESSION["student_manage"]["list"]["taekwondo_fees_id"])){
	$taekwondo_fees_id=$_SESSION["student_manage"]["list"]["taekwondo_fees_id"];	
}
else
	$taekwondo_fees_id='';
if( !empty( $taekwondo_fees_id ) ) {
	$join .= " inner join student_meta d on a.id=d.student_id and d.meta_key='fees_".$taekwondo_fees_id."' and d.meta_value > 0";
}
if(isset($_GET["admission_from"])){
	$admission_from=slash($_GET["admission_from"]);
	$_SESSION["student_manage"]["list"]["admission_from"]=$admission_from;
}
if(isset($_SESSION["student_manage"]["list"]["admission_from"])){
	$admission_from=$_SESSION["student_manage"]["list"]["admission_from"];	
}
else
	$admission_from="";
if( !empty( $admission_from ) ) {
	$extra .= " and addmission_date >= '".date_dbconvert( $admission_from )."'";
}
if(isset($_GET["admission_to"])){
	$admission_to=slash($_GET["admission_to"]);
	$_SESSION["student_manage"]["list"]["admission_to"]=$admission_to;
}
if(isset($_SESSION["student_manage"]["list"]["admission_to"])){
	$admission_to=$_SESSION["student_manage"]["list"]["admission_to"];	
}
else
	$admission_to="";
if( !empty( $admission_to ) ) {
	$extra .= " and addmission_date < '".date_dbconvert( $admission_to )."'";
}
if(isset($_GET["fees_from"])){
	$_SESSION["student_manage"]["list"]["page"]=1;
	$fees_from=slash($_GET["fees_from"]);
	$_SESSION["student_manage"]["list"]["fees_from"]=$fees_from;
}
if(isset($_SESSION["student_manage"]["list"]["fees_from"])){
	$fees_from=$_SESSION["student_manage"]["list"]["fees_from"];	
}
else
	$fees_from="";
if($fees_from!=='')
	$extra.=" and sm.meta_value >= ".$fees_from."";
if(isset($_GET["fees_to"])){
	$_SESSION["student_manage"]["list"]["page"]=1;
	$fees_to=slash($_GET["fees_to"]);
	$_SESSION["student_manage"]["list"]["fees_to"]=$fees_to;
}
if(isset($_SESSION["student_manage"]["list"]["fees_to"])){
	$fees_to=$_SESSION["student_manage"]["list"]["fees_to"];	
}
else
	$fees_to="";
if($fees_to!=='')
	$extra.=" and sm.meta_value <= ".$fees_to."";
if(isset($_GET["q"])){
	$_SESSION["student_manage"]["list"]["page"]=1;
	$q=slash($_GET["q"]);
	$_SESSION["student_manage"]["list"]["q"]=$q;
}
if(isset($_SESSION["student_manage"]["list"]["q"])){
	$q=$_SESSION["student_manage"]["list"]["q"];	
}
else
	$q="";
if(!empty($q))
	$extra.=" and (a.id ='".$q."' or student_name like '%".$q."%' or father_name like '%".$q."%' or surname like '%".$q."%')";
	
if(isset($_GET["page"])){
	$page=slash($_GET["page"]);
	$_SESSION["student_manage"]["list"]["page"]=$page;
}
if(isset($_SESSION["student_manage"]["list"]["page"])){
	$pageNum=$_SESSION["student_manage"]["list"]["page"];	
}
$order_by = "a.id";
$order = "asc";
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
$sql="select a.*, concat( a2.class_name, ' (', a1.title, ')') as class_name, concat(a2.sortorder, a1.title) as class, a1.id as class_section_id from student a $join left join student_meta sm on a.id = sm.student_id and sm.meta_key ='fees_".get_config( "monthly_fees_id" )."_approved' where 1 $extra order by $orderby";
switch($tab){
	case 'add':
		include("modules/student/add_do.php");
	break;
	case 'edit':
		include("modules/student/edit_do.php");
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
				$student_image = $id.'.'.$ext;
				$path = $file_upload_root."student/".$student_image;
				$code = base64_decode($code);			
				file_put_contents( $path, $code);
				set_student_meta( $id, "student_image", $student_image );
				die;
			}
		}
	break;
	case 'delete':
		include("modules/student/delete_do.php");
	break;
	case 'status':
		include("modules/student/status_do.php");
	break;
	case 'bulk_action':
		include("modules/student/bulkactions.php");
	break;
	case 'idcard':
		include("modules/student/idcard.php");
	break;
	case 'fees_chalan':
		include("modules/student/fees-chalan.php");
	break;
	case 'leaving_certificate':
		include("modules/student/leaving-certificate.php");
	break;
	case 'character_certificate':
		include("modules/student/character-certificate.php");
	break;
	case 'marksheet':
		include("modules/student/marksheet.php");
	break;
	case 'final_marksheet':
		include("modules/student/final-marksheet.php");
	break;
	case 'print':
		include("modules/student/print_do.php");
	break;
	case 'mail_envelop':
		include("modules/student/mail_envelop.php");
	break;
	case 'result_envelop':
		include("modules/student/result_envelop.php");
	break;
	case 'month_star':
		include("modules/student/starcard.php");
	break;
}
?>
<?php include("inc/header.php");?>
		<div class="main-content-inner">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/student/list.php");
                break;
                case 'edit':
                    include("modules/student/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("inc/footer.php");?>