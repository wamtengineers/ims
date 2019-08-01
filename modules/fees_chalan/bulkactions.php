<?php
if(!defined("APP_START")) die("No Direct Access");

if(isset($_GET["action"]) && $_GET["action"]!=""){
	$bulk_action=$_GET["action"];
	$id=explode(",",urldecode($_GET["Ids"]));	
	$err="";
	if($bulk_action=="null"){
		$err.="Select Action. <br>";
	}
	if(!isset($_GET["Ids"]) || $_GET["Ids"]==""){
		$err.="Select Records. <br>";	
	}
	if(empty($err)){
		if($bulk_action=="delete"){
			$i=0;
			while($i<count($id)){
				doquery("delete from fees_chalan where id='".$id[$i]."'",$dblink);
				doquery("delete from fees_chalan_details where fees_chalan_id='".slash($id[$i])."'",$dblink);
				doquery("delete from fees_chalan_receiving where fees_chalan_id='".slash($id[$i])."'",$dblink);
				$i++;
			}
			header("Location: fees_chalan_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				doquery("update fees_chalan set status=1 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: fees_chalan_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				doquery("update fees_chalan set status=0 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: fees_chalan_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: fees_chalan_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}