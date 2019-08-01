<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["feedback_manage"]["add"])){
	extract($_SESSION["feedback_manage"]["add"]);	
}
else{
	$message = "";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            New Feedback
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Feedback
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="feedback_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="feedback_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="message">Feedback <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <textarea title="Enter Message" name="message" id="message" class="col-xs-10"><?php echo $message; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="feedback_add" title="Submit Record">
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