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
				doquery("delete from student_2_class_2_subject where student_2_class_id='".$id[$i]."'",$dblink);
				doquery("delete from student_2_class where id='".$id[$i]."' and student_id='".$parent_student_id."'",$dblink);
				$i++;
			}
			header("Location: student_2_class_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				doquery("update student_2_class set status=1 where id='".$id[$i]."' and student_id='".$parent_student_id."'",$dblink);
				$i++;
			}
			header("Location: student_2_class_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				doquery("update student_2_class set status=0 where id='".$id[$i]."' and student_id='".$parent_student_id."'",$dblink);
				$i++;
			}
			header("Location: student_2_class_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: student_2_class_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}