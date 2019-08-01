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
			$ids = $id;
			while($i<count($ids)){
				$id = $ids[ $i ];
				doquery("delete from fees_chalan_receiving where fees_chalan_id in ( select id from fees_chalan where student_2_class_id in ( select id from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."')))",$dblink);
				doquery("delete from fees_chalan_details where fees_chalan_id in ( select id from fees_chalan where student_2_class_id in ( select id from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."')))",$dblink);
				doquery("delete from fees_chalan where student_2_class_id in ( select id from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."'))",$dblink);
				doquery("delete from on_demand_fees_classes where class_section_id in ( select id from class_section where class_id='".$id."')",$dblink);
				doquery("delete from student_daily_attendance where class_section_id in ( select id from class_section where class_id='".$id."')",$dblink);
				doquery("delete from student_2_class where class_section_id in ( select id from class_section where class_id='".$id."')",$dblink);
				doquery("delete from class_section where class_id='".$id."'",$dblink);
				doquery("delete from class_fees where class_id='".$id."'",$dblink);
				doquery("delete from class where id='".$id."'",$dblink);
				$i++;
			}
			header("Location: class_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				doquery("update class set status=1 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: class_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				doquery("update class set status=0 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: class_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: class_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}