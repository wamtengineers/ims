<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["academic_year_manage"]["add"])){
	extract($_SESSION["academic_year_manage"]["add"]);	
}
else{
	$title="";
	$start_date=date("d/m/Y");
	$end_date=date("d/m/Y");
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Academic Year
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Academic Year
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="academic_year_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="academic_year_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="title">Title <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="start_date">Start Date <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Start Date" value="<?php echo $start_date; ?>" name="start_date" id="start_date" class="col-xs-10 datepicker" />
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="end_date">End Date <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter End Date" value="<?php echo $end_date; ?>" name="end_date" id="end_date" class="col-xs-10 datepicker" />
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="academic_year_add" title="Submit Record">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>