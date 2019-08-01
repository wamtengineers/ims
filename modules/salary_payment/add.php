<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["salary_payment_manage"]["add"])){
	extract($_SESSION["salary_payment_manage"]["add"]);	
}
else{
	$employee_id="";
	$datetime_added=date("d/m/Y H:i A");
	$amount="";
	$account_id="";
	$details="";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Salary Payment
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Salary Payment
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="salary_payment_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="salary_payment_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="employee_id">Employee <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="employee_id" id="employee_id" class="col-xs-12" title="Choose Option">
                                <option value="0">Select Employee</option>
                                <?php
                                $res=doquery("Select * from employee where status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by name",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($employee_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"]); ?></option>
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
                        <div class="col-sm-3 control-label">
                            <label class="form-label" for="datetime_added">DateTime</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter DateTime" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="amount">Amount</label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Amount" value="<?php echo $amount; ?>" name="amount" id="amount" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="account_id">Paid By </label>
                        <div class="col-sm-9">
                            <select name="account_id" id="account_id" class="col-xs-12" title="Choose Option">
                                <option value="0">Select Account</option>
                                <?php
                                $res=doquery("Select * from account where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
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
                        <label class="col-sm-3 control-label no-padding-right" for="details">Details </label>
                        <div class="col-sm-9">
                             <textarea title="Enter Details" value="" name="details" id="details" class="col-xs-12" /><?php echo $details; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="salary_payment_add" title="Submit Record">
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