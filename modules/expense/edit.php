<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
	<div class="page-header">
       <h1>
            Edit Expense
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Expense
            </small>
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="expense_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
    		<form class="form-horizontal" role="form" action="expense_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            <input type="hidden" name="id" value="<?php echo $id?>" />
			<?php
                $i=0;
            ?>
            <div class="form-group">
                <div class="row">
                     <div class="col-sm-3 control-label no-padding-right">
                        <label for="datetime_added">Date/Time <span class="manadatory">*</span></label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" title="Enter datetime" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3 control-label no-padding-right">
                        <label class="form-label" for="expense_category_id">Expense Category </label>
                    </div>
                    <div class="col-sm-9">
                        <select name="expense_category_id" title="Choose Option">
                            <option value="0">Select Expense Category</option>
                            <?php
                            $res=doquery("select * from expense_category where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by title", $dblink);
                            if(numrows($res)>0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>"<?php echo($expense_category_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                                <?php			
                                }			
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3 control-label no-padding-right">
                        <label class="form-label" for="account_id">Paid By </label>
                    </div>
                    <div class="col-sm-9">
                        <select name="account_id" title="Choose Option">
                            <option value="0">Select Account</option>
                            <?php
                            $res=doquery("select * from account where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by title", $dblink);
                            if(numrows($res)>0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>"<?php echo($account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                                <?php			
                                }			
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3 control-label no-padding-right">
                        <label class="form-label" for="details">Details </label>
                    </div>
                    <div class="col-sm-9">
                         <textarea title="Enter Details" value="" name="details" id="details" class="form-control" /><?php echo $details; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3 control-label no-padding-right">
                        <label class="form-label" for="amount">Amount <span class="manadatory">*</span> </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" title="Enter Amount" value="<?php echo $amount; ?>" name="amount" id="amount" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="clearfix form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit" name="expense_edit" title="Submit Record">
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