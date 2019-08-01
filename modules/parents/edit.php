<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Parent
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Parents
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="parents_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="parents_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
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
                        <label class="col-sm-3 control-label no-padding-right" for="mobile_number">Mobile Number <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Mobile Number" value="<?php echo $mobile_number; ?>" name="mobile_number" id="mobile_number" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="password">Password <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="password" title="Enter Password" value="" placeholder="Password" name="password" id="password" class="col-xs-10" />
                            <span id="helpBlock" class="help-block clr">Leave empty for no change.</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="parents_edit" title="Update Record">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>