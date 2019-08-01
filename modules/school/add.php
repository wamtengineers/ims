<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["school_manage"]["add"])){
	extract($_SESSION["school_manage"]["add"]);	
}
else{
	$title="";
	$sortorder=get_new_sort_order("school");
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New School
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage School
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="school_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="school_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="title">Class Name <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="icon">Sort Order </label>
                        <div class="col-sm-9">
                            <?php getSortCombo("school",$sortorder,"add");?>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="school_add" title="Submit Record">
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