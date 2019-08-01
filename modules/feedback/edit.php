<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Reply Feedback
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Feedback
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="feedback_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="feedback_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="parent_id">Parent</label>
                        <div class="col-sm-9">
                            <select name="parent_id" title="Choose Option">
                                <option value="0">Select Parent</option>
                                <?php
                                $res=doquery("Select * from parents where status = 1 order by id",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($parent_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"]); ?></option>
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
                         <div class="col-sm-3 control-label no-padding-right">
                            <label for="date">Date </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Date" value="<?php echo $date; ?>" name="date" id="date" class="form-control date-picker" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="reply">Reply <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <textarea title="Enter Reply" name="reply" id="reply" class="col-xs-10"><?php echo $reply; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="feedback_edit" title="Update Record">
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