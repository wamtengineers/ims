<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Admin Type
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Admin Type
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="admin_type_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="admin_type_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
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
                        <label class="col-sm-3 control-label no-padding-right" for="can_add">Can Add </label>
                        <div class="col-sm-9">
                            <select name="can_add" id="can_add" title="Choose Option">
                                <option value="">Select Can Add</option>
                                <?php
                                foreach ($admin_types as $key=>$value) {
									?>
                                    <option value="<?php echo $key?>"<?php echo $key==$can_add?' selected="selected"':""?>><?php echo $value ?></option>
                                    <?php
								}
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="can_edit">Can Edit </label>
                        <div class="col-sm-9">
                            <select name="can_edit" id="can_edit" title="Choose Option">
                                <option value="">Select Can Edit</option>
                                <?php
                                foreach ($admin_types as $key=>$value) {
									?>
                                    <option value="<?php echo $key?>"<?php echo $key==$can_edit?' selected="selected"':""?>><?php echo $value ?></option>
                                    <?php
								}
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="can_delete">Can Delete </label>
                        <div class="col-sm-9">
                            <select name="can_delete" id="can_delete" title="Choose Option">
                                <option value="">Select Can Delete</option>
                                <?php
                                foreach ($admin_types as $key=>$value) {
									?>
                                    <option value="<?php echo $key?>"<?php echo $key==$can_delete?' selected="selected"':""?>><?php echo $value ?></option>
                                    <?php
								}
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="can_read">Can Read </label>
                        <div class="col-sm-9">
                            <select name="can_read" id="can_read" title="Choose Option">
                                <option value="">Select Can Read</option>
                                <?php
                                foreach ($admin_types as $key=>$value) {
									?>
                                    <option value="<?php echo $key?>"<?php echo $key==$can_read?' selected="selected"':""?>><?php echo $value ?></option>
                                    <?php
								}
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="admin_type_edit" title="Update Record">
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