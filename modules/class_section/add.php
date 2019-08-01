<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["class_section_manage"]["add"])){
	extract($_SESSION["class_section_manage"]["add"]);	
}
else{
	$class_id="";
	$title="";
	$board_id="";
	$group_id="";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Class Section
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Class Section
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="class_section_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="class_section_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="class_id">Class</label>
                        <div class="col-sm-9">
                            <select name="class_id" title="Choose Option">
                                <option value="0">Select Class</option>
                                <?php
                                $res=doquery("Select * from class where school_id = '".$_SESSION["current_school_id"]."' order by sortorder",$dblink);
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
                        <label class="col-sm-3 control-label no-padding-right" for="board_id">Board</label>
                        <div class="col-sm-9">
                            <select name="board_id" title="Choose Option">
                                <option value="0">Select Board</option>
                                <?php
                                $res=doquery("Select * from board order by id",$dblink);
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
                            <select name="group_id" title="Choose Option">
                                <option value="0">Select Group</option>
                                <?php
                                $res=doquery("Select * from `group` order by id",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($group_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                            <button class="btn btn-info" type="submit" name="class_section_add" title="Submit Record">
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