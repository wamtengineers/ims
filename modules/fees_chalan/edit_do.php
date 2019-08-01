<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["fees_chalan_edit"])){
	extract($_POST);
	$err="";
	if(empty($issue_date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update fees_chalan set `issue_date`='".slash(date_dbconvert(unslash($issue_date)))."', month='".$fee_month."' where id='".$id."'";
		doquery($sql,$dblink);
		$fees = doquery( "SELECT a.*, b.title FROM `fees_chalan_details` a left join fees b on a.fees_id = b.id where fees_chalan_id = '".$id."' order by sortorder", $dblink );
		if( numrows( $fees ) > 0 ) {
			while( $fee = dofetch( $fees ) ){
				if( isset( $chalan_fees[ $fee[ "id" ] ] ) ) {
					if( !isset( $chalan_fees_deleted[ $fee[ "id" ] ] ) || $chalan_fees_deleted[ $fee[ "id" ] ] != "1" ) {
						doquery( "update fees_chalan_details set fees_month='".$chalan_month[ $fee[ "id" ] ]."', fees_amount = '".slash( $chalan_fees[ $fee[ "id" ] ] )."' where id = '".$fee[ "id" ]."'", $dblink );
					}
					else {
						doquery( "delete from fees_chalan_details where id = '".$fee[ "id" ]."'", $dblink );
					}
				}
			}
		}
		if( isset( $extra_fees ) ){
			foreach( $extra_fees as $extra_fee ) {
				if( isset( $chalan_extra_fees[ $extra_fee ] ) ) {
					doquery( "insert into fees_chalan_details(fees_chalan_id, fees_id, fees_month, fees_amount ) values('".$id."', '".$extra_fee."', '".$chalan_extra_fees_month[ $extra_fee ]."', '".$chalan_extra_fees[ $extra_fee ]."' )", $dblink );
				}
			}
		}
		unset($_SESSION["fees_chalan_manage"]["edit"]);
		header('Location: fees_chalan_manage.php?tab=edit&id='.$id.'&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["fees_chalan_manage"]["edit"][$key]=$value;
		header("Location: fees_chalan_manage.php?tab=edit&err=".url_encode($err)."&id='".$id."'");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from fees_chalan where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$issue_date=date_convert($issue_date);
		if(isset($_SESSION["fees_chalan_manage"]["edit"]))
			extract($_SESSION["fees_chalan_manage"]["edit"]);
	}
	else{
		header("Location: fees_chalan_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: fees_chalan_manage.php?tab=list");
	die;
}