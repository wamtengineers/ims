<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Class
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Class
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="class_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="class_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="class_id">Class Level <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="class_level_id" title="Choose Option">
                                <option value="0">Select Class Level</option>
                                <?php
                                $res=doquery("Select * from class_level order by sortorder",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($class_level_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="class_name">Class Name <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Class Name" value="<?php echo $class_name; ?>" name="class_name" id="class_name" class="col-xs-10" />
                        </div>
                    </div>
                </div> 
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="sortorder">Sortorder </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Sortorder" value="<?php echo $sortorder; ?>" name="sortorder" id="sortorder" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="class_edit" title="Update Record">
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