<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["notification_edit"])){
	extract($_POST);
	$err="";
	if($target == "" || empty($date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update notification set `target`='".slash($target)."', `date`='".slash(date_dbconvert(unslash($date)))."', `title`='".slash($title)."', `text`='".slash($text)."' where id='".$id."'";
		doquery($sql,$dblink);
		doquery("delete from notification_2_class_section where notification_id='".$id."'", $dblink);
		if( $target == 0 && isset( $class_section_ids ) && count( $class_section_ids ) > 0 ) {
			foreach( $class_section_ids as $class_section_id ) {
				doquery( "insert into notification_2_class_section(notification_id, class_section_id) values( '".$id."', '".$class_section_id."' )", $dblink );
			}
		}
		unset($_SESSION["notification_manage"]["edit"]);
		header('Location: notification_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["notification_manage"]["edit"][$key]=$value;
		header("Location: notification_manage.php?tab=edit&err=".url_encode($err)."&id='".$id."'");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from notification where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$date=date_convert($date);
			$class_section_ids = array();
			$department_ids = array();
			$sql="select * from notification_2_class_section where notification_id='".$id."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$class_section_ids[] = $r1[ "class_section_id" ];
				}
			}
		if(isset($_SESSION["notification_manage"]["edit"]))
			extract($_SESSION["notification_manage"]["edit"]);
	}
	else{
		header("Location: notification_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: notification_manage.php?tab=list");
	die;
}