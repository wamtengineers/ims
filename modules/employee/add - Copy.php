<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["employee_manage"]["add"])){
	extract($_SESSION["employee_manage"]["add"]);	
}
else{
	$department_id="";
	$designation_id="";
	$name="";
	$father_name="";
	$employee_code="";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Employee
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Employee
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="employee_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="employee_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="department_id">Department <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <select name="department_id" title="Choose Option">
                                <option value="0">Select Department id</option>
                                <?php
                                $res=doquery("Select * from department order by title",$dblink);
                                if(mysql_num_rows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($department_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="designation_id">Designation <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <select name="designation_id" title="Choose Option">
                                <option value="0">Select Designation id</option>
                                <?php
                                $res=doquery("Select * from designation order by title",$dblink);
                                if(mysql_num_rows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($designation_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="name">Name <span class="manadatory">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="father_name">Father Name</label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Father Name" value="<?php echo $father_name; ?>" name="father_name" id="father_name" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="employee_code">Employee Code <span class="manadatory">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Employee Code" value="<?php echo $employee_code; ?>" name="employee_code" id="employee_code" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="employee_add" title="Submit Record">
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