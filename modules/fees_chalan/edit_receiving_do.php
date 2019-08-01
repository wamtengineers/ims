<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["fees_chalan_receiving_edit"])){
	extract($_POST);
	$err="";
	if(empty($payment_date) || empty($amount))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$r = doquery( "select * from fees_chalan_receiving where fees_chalan_id = '".$id."'", $dblink );
		if( numrows( $r ) > 0 ) {
			$r = dofetch( $r );
			$sql="Update fees_chalan_receiving set `payment_date`='".slash(date_dbconvert($payment_date))."', amount = '".slash( $amount )."' where id='".$r[ "id" ]."'";
		}
		else{
			$sql = "insert into fees_chalan_receiving(fees_chalan_id, payment_date, amount) values('".$id."', '".slash(date_dbconvert($payment_date))."', '".slash($amount)."')";
		}
		
		doquery($sql,$dblink);
		unset($_SESSION["fees_chalan_manage"]["receiving_edit"]);
		header('Location: fees_chalan_manage.php?tab=edit_receiving&id='.$id.'&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["fees_chalan_manage"]["receiving_edit"][$key]=$value;
		header("Location: fees_chalan_manage.php?tab=edit_receiving&err=".url_encode($err)."&id='".$id."'");
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
		$total_amount = 0;
		$chalan_details = get_chalan_details( $id );
		foreach( $chalan_details[ "details" ] as $chalan_detail ) {
			$total_amount += $chalan_detail[ "amount" ];
		}
		$r = doquery( "select * from fees_chalan_receiving where fees_chalan_id = '".$id."'", $dblink );
		if( numrows( $r ) > 0 ) {
			$r = dofetch( $r );
			if( isset( $_GET[ "delete" ] ) ) {
				doquery( "delete from fees_chalan_receiving where fees_chalan_id = '".$id."'", $dblink );
				header("Location: fees_chalan_manage.php?tab=list");
				die;
			}
			$payment_date = date_convert( $r[ "payment_date" ] );
			$amount = $r[ "amount" ];
		}
		else{
			$payment_date = date( "d/m/Y" );
			$amount = $total_amount;
		}
		if(isset($_SESSION["fees_chalan_manage"]["receiving_edit"]))
			extract($_SESSION["fees_chalan_manage"]["receiving_edit"]);
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