<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["admin_manage"]["add"])){
	extract($_SESSION["admin_manage"]["add"]);	
}
else{
	$school_id="";
	$admin_type_id="";
	$name="";
	$username="";
	$email="";
	$password="";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Add New Admin
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Admin
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="admin_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="admin_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
            	<?php
				$i=0;
				if( $_SESSION[ "logged_in_admin" ][ "school_id" ] == 0 ) {
					?>
					<div class="form-group">
						<div class="row">
							<label class="col-sm-3 control-label no-padding-right" for="school_id">School</label>
							<div class="col-sm-9">
                            	<select name="school_id" id="school_id" class="custom_select">
                                    <option value="0">All School</option>
                                    <?php
                                    $res=doquery("Select * from school order by sortorder",$dblink);
                                    if(numrows($res)>0){
                                        while($rec=dofetch($res)){
                                        ?>
                                        <option value="<?php echo $rec["id"]?>"<?php echo($school_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                                        <?php			
                                        }			
                                    }
                                    ?>
                                </select>
							</div>
						</div>
					</div>
                    <?php
				}
				?>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="admin_type_id">Admin Type <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <select name="admin_type_id" title="Choose Option">
                                <option value="0">Select Admin Type</option>
                                <?php
                                $res=doquery("Select * from admin_type order by title",$dblink);
                                if(numrows($res)>0){
                                    while($rec=dofetch($res)){
                                    ?>
                                    <option value="<?php echo $rec["id"]?>"<?php echo($admin_type_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="name">Name <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="username">Username <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter User Name" value="<?php echo $username; ?>" name="username" id="username" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="email">Email <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Email" value="<?php echo $email; ?>" name="email" id="email" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="password">Password <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <input type="password" title="Enter Password" value="" placeholder="Password" name="password" id="password" class="col-xs-10" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="admin_add" title="Submit Record">
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