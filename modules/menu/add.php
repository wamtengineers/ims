<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["menu_manage"]["add"])){
	extract($_SESSION["menu_manage"]["add"]);	
}
else{
	$admin_type_ids=array();
	$school_ids=array();
	$title="";
	$url="";
	$parent_id=0;
	$small_icon="";
	$sortorder=get_new_sort_order("menu");
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Menu
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Menus
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="align-right">
    	<div class="btn-group bottom-20"> <a href="menu_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
  	</div>
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" action="menu_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
					$i=0;
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="admin_type_id">Admin Type <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <select name="admin_type_ids[]" title="Choose Option" multiple="multiple" class="select_multiple">
                                <option value="0">Select Admin Type</option>
                                <?php
                                $res=doquery("Select * from admin_type order by title",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo in_array($rec["id"], $admin_type_ids)?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="school_id">School </label>
                        <div class="col-sm-9">
                            <select name="school_ids[]" title="Choose Option" multiple="multiple" class="select_multiple">
                                <option value="0">Select School</option>
                                <?php
                                $res=doquery("Select * from school order by sortorder",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo in_array($rec["id"], $school_ids)?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="title">Title <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="url">Target URL </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Target URL" value="<?php echo $url; ?>" name="url" id="url" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="parent_id">Parent</label>
                        <div class="col-sm-9">
                            <select name="parent_id" title="Choose Option">
                                <option value="0">NO Parent</option>
                                <?php
                                    $res=doquery("select a.*, b.title as parent from menu a left join menu b on a.parent_id=b.id where 1 $extra order by b.sortorder, a.sortorder, depth ASC",$dblink);
                                    if(numrows($res)>0){
                                        while($rec=dofetch($res)){
                                ?>
                                        <option value="<?php echo $rec["id"]?>"<?php echo($parent_id==$rec["id"])?"selected":"";?>><?php echo str_repeat("- ", $rec["depth"]).unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="small_icon">SubMenu Icon <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <select name="small_icon" id="small_icon" style="font-family:FontAwesome, Arial">
                                <?php
                                foreach(get_fontawesome_icons() as $ficon){
                                    ?>
                                    <option value="<?php echo $ficon[0]?>"<?php if($small_icon==$ficon[0]) echo ' selected="selected"';?>><?php echo "&#x".$ficon[1]." ".$ficon[0]?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="icon">Main Menu Icon <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <input type="file" name="icon" id="icon" title="icon for Main Menu" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="icon">Sort Order </label>
                        <div class="col-sm-9">
                            <?php getSortCombo("menu",$sortorder,"add");?>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="menu_add" title="Submit Record">
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