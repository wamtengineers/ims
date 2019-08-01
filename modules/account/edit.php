<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
       <h1>
            Edit Account
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Account
            </small>
        </h1>
    </div>
  	<div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="account_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <form class="form-horizontal" role="form" action="account_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
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
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label no-padding-right">
                            <label for="description">Description </label>
                        </div>
                        <div class="col-sm-9">
                            <textarea title="Enter Description" name="description" id="description" class="form-control"><?php echo $description; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label no-padding-right">
                            <label for="balance">Balance </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Balance" value="<?php echo $balance; ?>" name="balance" id="balance" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label no-padding-right">
                            <label for="is_petty_cash">Account Type </label>
                        </div>
                        <div class="col-sm-9">
                            <select name="account_type" id="is_petty_cash">
                                <option value="0"<?php echo $account_type=="0"?' selected="selected"':''?>>General</option>
                                <option value="1"<?php echo $account_type=="1"?' selected="selected"':''?>>Petty Cash</option>
                                <option value="2"<?php echo $account_type=="2"?' selected="selected"':''?>>Bank</option>
                                <option value="3"<?php echo $account_type=="3"?' selected="selected"':''?>>Fixed Assets</option>
                                <option value="4"<?php echo $account_type=="4"?' selected="selected"':''?>>Capital</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="account_edit" title="Submit Record">
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