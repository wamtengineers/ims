<?php
if(!defined("APP_START")) die("No Direct Access");
if( isset( $_GET[ "id" ] ) ) {
	$id = slash( $_GET[ "id" ] );
}
else {
	$id = 0;
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Employee's Notification
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Send Notification
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="notification_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="notification_manage.php?tab=employee" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" id="id" value="<?php echo $id;?>">
                <input type="hidden" name="date" id="date" value="<?php echo $date;?>">
                <style>
                .student_list{
					padding:10px;
					border:solid 1px;
					border-radius: 5px;
				}
				.student_item{
					display:block;
					padding:5px;
					border-bottom: solid 1px;
					cursor:pointer;
				}
				#present_list .student_item{ background-color:#CFF;}
				#absent_list .student_item{ background-color:#FCF;}
                </style>
                <div class="form-group" ng-app="employeenotification" ng-controller="employeenotificationController">
                	<input type="hidden" name="employees" id="present" value="{{employees}}">
                	<div class="row">
                       	<div class="col-sm-6">
                        	<h2>Present Employees</h2>
                            <div class="student_list" id="present_list">
                            	<div ng-repeat="employee in employees|filter:{status:true}" class="student_item" ng-dblclick="employee.status=!employee.status">
                                	<span>{{ $index+1 }}.</span> {{ employee.name+" "+employee.father_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        	<h2>Absent Employees</h2>
                            <div class="student_list" id="absent_list">
                            	<div ng-repeat="employee in employees|filter:{status:false}" class="student_item" ng-dblclick="employee.status=!employee.status">
                                	<span>{{ $index+1 }}</span> {{ employee.name+" "+employee.father_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="notification_2_employee_save" title="Save Notification">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Save Notification
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>