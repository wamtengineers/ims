<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Profile
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Profile
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="index.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="profile.php?tab=profile_edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $_SESSION["logged_in_parents"]["id"];?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label no-padding-right">
                            <label for="title">Name </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label no-padding-right">
                            <label for="password">Password </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="password" title="Enter Password" value="" name="password" id="password" class="form-control" />
                            <span id="helpBlock" class="help-block clr">Leave empty for no change.</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-9">
                            <button class="btn btn-info" type="submit" name="profile_edit" title="Update Record">
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