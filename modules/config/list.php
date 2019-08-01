<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["config_id"]))
	$config_id=slash($_GET["config_id"]);
else
	$config_id=0;
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Configuration
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Site Settings.
            </small>
        </h1>
    </div>
    <div class="clearfix">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="btn-group"> <a href="index.php" class="btn btn-sm btn-primary">Home</a> </div>
        </div>
        <form name="search_form" action="" method="get" class="config-item col-md-6 col-sm-6 col-xs-6 align-right">
            <span>Show Items: </span>
            <select onchange="window.location.href='config_manage.php?config_id='+this.value">
                <option value="0" <?php if($config_id==0) echo "selected";?>>All Category</option>
				<?php
                $res=doquery("Select id, title from config_type order by sortorder",$dblink);
                if(numrows($res)>=0){
                    while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"];?>" <?php echo ($config_id==$rec["id"])?"selected='selected'":"";?>><?php echo unslash($rec["title"])?></option>
                        <?php
                    
                    }
                }	
                1?>
            </select>
        </form>
    </div>
    <form action="config_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
		<input type="hidden" value="<?php echo $_GET["config_id"]; ?>" name="config_id" />
        <div class="list config-form">
            <?php
            if($config_id!=0)
                $extra=" and id='".$config_id."'";
            else
                $extra="";
            $res=doquery("Select * from config_type where 1 $extra order by sortorder ASC",$dblink);
            if(numrows($res)>0){
                $sn=0;
                while($rec=dofetch($res)){
            ?>
                <div style="text-align:left; font-weight:bold; margin:20px 0;" class="col-md-12"><?php echo htmlentities(unslash($rec["title"]));?></div>
                
                <?php
                $res1=doquery("Select * from config_variable where config_type_id='".addslashes($rec["id"])."' and school_id='".$_SESSION["current_school_id"]."' order by id ",$dblink);
                if(numrows($res1)>0){
                    while($rec1=dofetch($res1)){
                    $rec1["value"]=unslash($rec1["value"]);
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label form-label" for="title"><?php echo $rec1["title"]?></label>
                    <div class="col-sm-10">
                    <?php
                        switch($rec1["type"]){
                            case "text":
                            case "submit":
                            case "file":
                            case "textarea":
                            case "button":
                                getInputBox($rec1["type"],$rec1["value"],$rec1["id"],"","");
                            break;
                            case "radio":
                            case "checkbox":
                            case "combobox":
                                getInputBox($rec1["type"],"",$rec1["id"],"",$rec1["default_values"]);
                            break;
                            case "editor":
                                getInputBox($rec1["type"],$rec1["value"],$rec1["id"],"mceEditor","");
                            break;
                        }
                    ?>
                    <br />
                    <small><?php echo unslash($rec1["notes"]);?></small>
                    </div>
                </div>
                <?php
                $sn++;
                }
                ?>
                <div class="form-group">
                    <label for="company" class="col-sm-2 control-label form-label"></label>
                    <div class="col-sm-10">
                    	<button type="submit" class="btn btn-info" name="config_edit" title="Update Record">
                        	<i class="ace-icon fa fa-check bigger-110"></i>
                            UPDATE
                        </button>
                    </div>
                </div>
                <?php
                }
                else{
                ?>
                <div class="err">No Variables  Found</div>
                <?php
                }
            }
        }
        ?>
        </div>
	</form>