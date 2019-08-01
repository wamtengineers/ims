<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Fees
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Fees
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="fees_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="fees_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
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
                        <label class="col-sm-3 control-label no-padding-right" for="type">Type </label>
                        <div class="col-sm-9">
                            <select name="type" title="Choose Option">
                                <?php
								foreach ($fees_type as $key=>$value) {
									?>
									<option value="<?php echo $key?>"<?php echo $type!="" && $key==$type?' selected="selected"':""?>><?php echo $value ?></option>
									<?php
								}
								?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="has_discount">Has Discount</label>
                        <div class="col-sm-9">
                            <select name="has_discount" title="Choose Option">
                            	<option value="0"<?php echo $has_discount==0?' selected':''?>>No</option>
                                <option value="1"<?php echo $has_discount==1?' selected':''?>>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="sortorder">For Selected Students? <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="radio" value="0" name="selected_students" id="selected_students"<?php echo !$selected_students?' checked':''?> /> No
                            <input type="radio" value="1" name="selected_students" id="selected_students"<?php echo $selected_students?' checked':''?> /> Yes
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="sortorder">Sort Order <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" value="<?php echo $sortorder; ?>" name="sortorder" id="sortorder" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="fees_edit" title="Update Record">
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