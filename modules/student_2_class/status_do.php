<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("update student_2_class set status='0' where student_id='".$parent_student_id."'",$dblink);
	doquery("update student_2_class set status='1' where id='".slash($_GET["id"])."' and student_id='".$parent_student_id."'",$dblink);
	header("Location: student_2_class_manage.php?done=1");
	die;
}