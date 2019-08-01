<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["subject_edit"])){
	extract($_POST);
	$err="";
	if(empty($class_id) || empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update subject set board_id='".slash($board_id)."', `class_id`='".slash($class_id)."', `title`='".slash($title)."', `is_optional`='".slash($is_optional)."', `color`='".slash($color)."', `sortorder`='".slash($sortorder)."'" ." where id='".$id."'";
		doquery($sql,$dblink);
		doquery("delete from subject_2_group where subject_id='".$id."'", $dblink);
		if( isset( $group_ids ) && count( $group_ids ) > 0 ) {
			foreach( $group_ids as $group_id ) {
				doquery( "insert into subject_2_group(subject_id, group_id) values( '".$id."', '".$group_id."' )", $dblink );
			}
		}
		unset($_SESSION["subject_manage"]["edit"]);
		header('Location: subject_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["subject_manage"]["edit"][$key]=$value;
		header("Location: subject_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from subject where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$group_ids = array();
			$sql="select * from subject_2_group where subject_id='".$id."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$group_ids[] = $r1[ "group_id" ];
				}
			}
		if(isset($_SESSION["subject_manage"]["edit"]))
			extract($_SESSION["subject_manage"]["edit"]);
	}
	else{
		header("Location: subject_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: subject_manage.php?tab=list");
	die;
}