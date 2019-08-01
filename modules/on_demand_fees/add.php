<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["on_demand_fees_manage"]["add"])){
	extract($_SESSION["on_demand_fees_manage"]["add"]);	
}
else{
	$academic_year_id="";
	$selected_classes = 0;
	$selected_students = 0;
	$fees_title="";
	$fees_amount="";
	$date=date("d/m/Y");
	$class_section_ids=array();
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Record
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage On Demand Fees
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="on_demand_fees_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="on_demand_fees_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="academic_year_id">Academic Year <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="academic_year_id" id="academic_year_id" class="col-xs-12" title="Choose Option">
                                <option value="0">Select Academic Year</option>
                                <?php
                                $res=doquery("Select * from academic_year where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
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
                        <label class="col-sm-3 control-label no-padding-right" for="academic_year_id">Classes <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="selected_classes" id="selected_classes" class="col-xs-12" title="Choose Option" onChange="$('.selected_class_section').toggle()">
                                <option value="0"<?php echo $selected_classes==0?" selected":""?>>All Classes</option>
                                <option value="1"<?php echo $selected_classes==1?" selected":""?>>Selected Classes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group selected_class_section"<?php echo $selected_classes==0?' style="display:none;"':''?>>
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="class_section_id">Class Section</label>
                        <div class="col-sm-9">
                            <select name="class_section_ids[]" id="class_section_id" multiple="multiple" class="select_multiple" title="Choose Option">
                                <option value="0">Select Class Section</option>
                                <?php
                                $res=doquery("select a.*, b.class_name from class_section a inner join class b on a.class_id=b.id where a.school_id = '".$_SESSION["current_school_id"]."' order by sortorder",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo(isset($class_section_ids) && in_array( $rec["id"], $class_section_ids))?"selected":"";?>><?php echo unslash($rec["class_name"])." - ".unslash($rec["title"])?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="academic_year_id">Students <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="selected_students" id="selected_students" class="col-xs-12" title="Choose Option">
                                <option value="0"<?php echo $selected_students==0?" selected":""?>>All Students</option>
                                <option value="1"<?php echo $selected_students==1?" selected":""?>>Selected Students</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="fees_title">Fees Title <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Fees Title" value="<?php echo $fees_title; ?>" name="fees_title" id="fees_title" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="fees_amount">Fees Amount <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Fees Amount" value="<?php echo $fees_amount; ?>" name="fees_amount" id="fees_amount" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label">
                            <label class="form-label" for="date">Date <span class="red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter DateTime" value="<?php echo $date; ?>" name="date" id="date" class="form-control datepicker" >
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="on_demand_fees_add" title="Submit Record">
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