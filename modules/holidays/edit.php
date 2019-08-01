<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Holidays
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Holidays
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="holidays_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="holidays_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="is_working_day">Is Working Day </label>
                        <div class="col-sm-9">
                            <select name="is_working_day" title="Choose Option">
                                <option value="">Select Working Day</option>
                                <?php
								foreach ($working_days as $key=>$value) {
									?>
									<option value="<?php echo $key?>"<?php echo $key==$is_working_day?' selected="selected"':""?>><?php echo $value ?></option>
									<?php
								}
								?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="date">Date <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Date" value="<?php echo $date; ?>" name="date" id="date" class="col-xs-10 datepicker" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="holidays_edit" title="Update Record">
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