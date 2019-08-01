<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Update Configuration Variable
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Configuration Variables
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="align-right">
    	<div class="btn-group"> <a href="config_variable_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
  	</div>
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" action="config_variable_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="config_type_id">Configuration Type <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <select name="config_type_id" id="config_type_id" class="" title="Choose Option">
                                <option value="0">Select Configuration Type</option>
                                <?php
                                    $res=doquery("select * from config_type order by sortorder",$dblink);
                                    if(numrows($res)>0){
                                        while($rec=dofetch($res)){
                                            ?>
                                            <option value="<?php echo $rec["id"]?>"<?php echo($config_type_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="notes">Notes</label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Notes" value="<?php echo $notes; ?>" name="notes" id="notes" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="password">Type <span class="manadatory">*</span> </label>
                        <div class="col-sm-9">
                            <select name="type" title="Choose Type" class="">
                                <option value="null">Select Type</option>
                                <?php
                                $type_arr=array("text","checkbox","radio","textarea","editor","file","combobox");
                                $i=0;
                                while($i<count($type_arr)){
                                    ?>
                                    <option value="<?php echo $type_arr[$i];?>"<?php if($type==$type_arr[$i]) echo ' selected="selected"';?>><?php echo $type_arr[$i];?></option>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="default_values">Default Values (seprated by semi-colon ';')</label>
                        <div class="col-sm-9">
                            <input type="text" name="default_values" id="default_values" title="Enter Default Value: Seprated by ; semi-colon" value="<?php echo $default_values; ?>" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="key">Key</label>
                        <div class="col-sm-9">
                            <input type="text" name="key" id="key" title="Enter Key" value="<?php echo $key; ?>" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="value">Value</label>
                        <div class="col-sm-9">
                            <input type="text" name="value" id="value" title="Enter Value" value="<?php echo $value; ?>" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" title="Select Order">Sort Order</label>
                        <div class="col-sm-9">
                            <?php getSortCombo("config_variable",$sortorder,"edit");?>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="Submit"  title="Update Record">
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