<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from student_2_class_2_subject where student_2_class_id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from student_2_class where id='".slash($_GET["id"])."' and student_id='".$parent_student_id."'",$dblink);
	header("Location: student_2_class_manage.php?done=1");
	die;
}