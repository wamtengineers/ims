<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["subject_manage"]["add"])){
	extract($_SESSION["subject_manage"]["add"]);	
}
else{
	$board_id = "";
	$class_id="";
	$title="";
	$sortorder="";
	$color="";
	$is_optional="";
	$group_ids=array();
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Subject
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Subject
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="subject_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="subject_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="board_id">Board </label>
                        <div class="col-sm-9">
                            <select name="board_id" title="Choose Option">
                                <option value="0">Select Board</option>
                                <?php
                                $res=doquery("Select * from board order by title",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($board_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="group_id">Group</label>
                        <div class="col-sm-9">
                            <select name="group_ids[]" id="group_id" multiple="multiple" class="select_multiple" title="Choose Option">
                                <option value="0">Select Group</option>
                                <?php
                                $res=doquery("select * from `group` where status = 1 order by title",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo(isset($group_ids) && in_array( $rec["id"], $group_ids))?"selected":"";?>><?php echo unslash($rec["title"])?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="class_id">Class <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="class_id" title="Choose Option">
                                <option value="0">Select Class</option>
                                <?php
                                $res=doquery("Select * from class where school_id = '".$_SESSION["current_school_id"]."' order by class_name",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($class_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["class_name"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="title">Title <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="is_optional">Is Optional </label>
                        <div class="col-sm-9">
                            <select name="is_optional" id="is_optional">
                            	<option value="0"<?php echo ($is_optional=="0")? " selected":"";?>>No</option>
                                <option value="1"<?php echo ($is_optional=="1")? " selected":"";?>>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="color">Color </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Color" value="<?php echo $color; ?>" name="color" id="color" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="sortorder">Sort Order </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Sortorder" value="<?php echo $sortorder; ?>" name="sortorder" id="sortorder" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="subject_add" title="Submit Record">
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