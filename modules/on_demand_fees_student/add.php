<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["on_demand_fees_student_manage"]["add"])){
	extract($_SESSION["on_demand_fees_student_manage"]["add"]);	
}
else{
	$student_id="";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Record
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage On Demand Fees Student
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="on_demand_fees_student_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="on_demand_fees_student_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="student_id">Student <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="student_id" title="Choose Option">
                                <option value="0">Select Student</option>
                                <?php
                                $res=doquery("Select * from student where school_id = '".$_SESSION["current_school_id"]."' order by student_name",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($student_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["student_name"]); echo " S/O " .unslash($rec["father_name"]); ?></option>
                                	<?php			
                                    }			
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="on_demand_fees_student_add" title="Submit Record">
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