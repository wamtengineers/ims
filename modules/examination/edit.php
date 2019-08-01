<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Record
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Examination
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-examination bottom-20"> <a href="examination_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="examination_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="examination_type_id">Examination Type <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <select name="examination_type_id" title="Choose Option">
                                <option value="0">Select Examination Type</option>
                                <?php
                                $res=doquery("Select * from examination_type where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($examination_type_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="academic_year_id">Academic Year </label>
                        <div class="col-sm-9">
                            <select name="academic_year_id" title="Choose Option">
                                <option value="0">Select Academic Year</option>
                                <?php
                                $res=doquery("Select * from academic_year where school_id = '".$_SESSION["current_school_id"]."' order by start_date",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($academic_year_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="start_date">Start Date </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Date" value="<?php echo $start_date; ?>" name="start_date" id="start_date" class="col-xs-10 datepicker" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="result_date">Result Date </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Date" value="<?php echo $result_date; ?>" name="result_date" id="result_date" class="col-xs-10 datepicker" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="examination_edit" title="Update Record">
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