<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["notification_manage"]["add"])){
	extract($_SESSION["notification_manage"]["add"]);	
}
else{
	$target=0;
	$title="";
	$date=date("d/m/Y");
	$text="";
	$class_section_ids=array();
	$department_ids=array();
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Record
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Notification
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="notification_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="notification_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="target">Target <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="target" id="target" class="col-xs-12" title="Choose Option" onChange="$('.selected_class_section').toggle()">
                                <option value="0"<?php echo $target==0?" selected":""?>>Student</option>
                                <option value="1"<?php echo $target==1?" selected":""?>>Employee</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group selected_class_section"<?php echo $target==1?' style="display:none;"':''?>>
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
                <div class="form-group selected_class_section"<?php echo $target==0?' style="display:none;"':''?>>
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="department_id">Department</label>
                        <div class="col-sm-9">
                            <select name="department_ids[]" id="department_id" multiple="multiple" class="select_multiple" title="Choose Option">
                                <option value="0">Select Department</option>
                                <?php
                                $res=doquery("select * from department where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo(isset($department_ids) && in_array( $rec["id"], $department_ids))?"selected":"";?>><?php echo unslash($rec["title"])?></option>
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
                            <label class="form-label" for="date">Date <span class="red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter DateTime" value="<?php echo $date; ?>" name="date" id="date" class="form-control datepicker" >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="title">Title </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="text">Text </label>
                        <div class="col-sm-9">
                            <textarea title="Enter Text" name="text" id="text" class="col-xs-12"><?php echo $text; ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="notification_add" title="Submit Record">
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