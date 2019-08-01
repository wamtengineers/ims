<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["filename"])){
	extract($_SESSION);
}
else{
	$filename="";
	$filelocation="";
}
?>
<div class="page-content">
<div class="page-header">
    <h1>
        Upload New File
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Upload
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="align-right">
            <div class="btn-group bottom-20"> <a href="upload_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
        </div>
        <!-- PAGE CONTENT BEGINS -->
        <form action="upload_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
			<?php
                $i=0;
            ?>
            <div class="form-group">
                <div class="row">
                    <label class="col-sm-3 control-label no-padding-right" for="filename">File Name <span class="red">*</span> </label>
                    <div class="col-sm-9">
                    	<input type="text" title="Enter File Name" value="<?php echo $filename; ?>" name="filename" id="filename" class="col-xs-10" >
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-sm-3 control-label no-padding-right" for="username">Upload New File <span class="red">*</span> </label>
                    <div class="col-sm-9">
                        <input type="file" name="filelocation" id="filelocation"  title="Upload New File" class="col-xs-10" size="30" />
                    </div>
                </div>
            </div>
            <div class="clearfix form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit" name="Submit" title="Submit Record">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div>
</div>