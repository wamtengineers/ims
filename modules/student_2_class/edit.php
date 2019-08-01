<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Student's Class
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?php echo $student_name;?> Class(s)
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="student_2_class_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal student-class" role="form" action="student_2_class_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="class_section_id">Class Section <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <select name="class_section_id" title="Choose Option">
                                <option value="0">Select Class Section</option>
                                <?php
                                $res=doquery("select a.*, b.class_name from class_section a inner join class b on a.class_id=b.id where a.school_id = '".$_SESSION["current_school_id"]."' order by class_name",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($class_section_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["class_name"])." - ".unslash($rec["title"])?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="academic_year_id">Year <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="academic_year_id" title="Choose Option">
                                <option value="0">Select Year</option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="group_id">Group </label>
                        <div class="col-sm-9">
                            <select name="group_id" title="Choose Option">
                                <option value="0">Select Group</option>
                                <?php
                                $res=doquery("Select * from `group` order by title",$dblink);
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
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="subject_id">Subject</label>
                        <div class="col-sm-9">
                            <select name="subject_ids[]" id="subject_id" multiple="multiple" class="select_multiple" title="Choose Option">
                                <option value="0">Select Subject</option>
                                <?php
                                $res=doquery("select * from subject where is_optional = 1 and class_id = (select class_id from class_section where id = '".$r["class_section_id"]."') order by sortorder",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo(isset($subject_ids) && in_array($rec["id"], $subject_ids))?"selected":"";?>><?php echo unslash($rec["title"])?></option>
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
                            <button class="btn btn-info" type="submit" name="student_2_class_edit" title="Update Record" id="btnreload">
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
<?php
if(isset($_GET["done"])){
	
}
?>