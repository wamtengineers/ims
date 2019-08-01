<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["visitor_manage"]["add"])){
	extract($_SESSION["visitor_manage"]["add"]);	
}
else{
	$name="";
	$id_card_number="";
	$checked_in=date("d-m-Y H:i:s");
	$checked_out=date("d-m-Y H:i:s");
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Visitor
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Visitor
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="visitor_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="visitor_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="name">Name <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="id_card_number">Id Card Number <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Id Card Number" value="<?php echo $id_card_number; ?>" name="id_card_number" id="id_card_number" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="checked_in">Checked In</label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter checked in" data-format="dd/MM/yyyy hh:mm:ss" value="<?php echo $checked_in; ?>" name="checked_in" id="checked_in" class="col-xs-10 date-timepicker" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="checked_out">Checked Out</label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter checked out" data-format="dd/MM/yyyy hh:mm:ss" value="<?php echo $checked_out; ?>" name="checked_out" id="checked_out" class="col-xs-10 date-timepicker" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="visitor_add" title="Submit Record">
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