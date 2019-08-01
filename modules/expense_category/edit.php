<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
	<div class="page-header">
       <h1>
            Add New Expense Category
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Expense Category
            </small>
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="expense_category_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <form class="form-horizontal" role="form" action="expense_category_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id?>" />
                <?php
                    $i=0;
                ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label no-padding-right">
                            <label for="title">Title </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="expense_category_edit" title="Submit Record">
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