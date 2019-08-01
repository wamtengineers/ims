<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?php echo $title?> Assement of <?php echo $class_teacher[ "class" ]."-".$class_teacher[ "section" ]?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Class Assement
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="examination_result_students_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal result-form" role="form" action="examination_result_students_manage.php?tab=edit&page=<?php echo $pageNum?>" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <?php
				$rs = show_page(10, $pageNum, $sql);
				if( numrows( $rs ) > 0 ) {
					while( $r = dofetch( $rs ) ) {
						?>
						<div class="form-group">
                            <div class="row">
                                <div class="col-md-12"><h3><?php echo unslash( $r[ "student_name" ] )." / ".unslash( $r[ "father_name" ] )?></h3></div>
                                <?php
								foreach( $parameters as $parameter ){
									?>
									<div class="col-md-3">
                                        <div class="col-md-5"><label><?php echo ucfirst( str_replace("_"," ",$parameter ));?> <span class="red">*</span> </label></div>
                                        <div class="col-md-7">
                                            <select name="<?php echo $parameter?>[<?php echo $r[ "studentid" ]?>]" title="Choose Option">
                                                <option value=""<?php echo empty($r[$parameter])? " selected":"";?>>Select</option>
                                                <option value="1"<?php echo ($r[$parameter]=="1")? " selected":"";?>>E</option>
                                                <option value="2"<?php echo ($r[$parameter]=="2")? " selected":"";?>>D</option>
                                                <option value="3"<?php echo ($r[$parameter]=="3")? " selected":"";?>>S</option>
                                                <option value="4"<?php echo ($r[$parameter]=="4")? " selected":"";?>>A</option>
                                                <option value="5"<?php echo ($r[$parameter]=="5")? " selected":"";?>>Na</option>
                                            </select>
                                        </div>
                                    </div>	
									<?php
								}
								?>
                                <div class="col-md-12">
                                    <div class="col-md-3"><label for="remarks">Remarks</label></div>
                                    <div class="col-md-9">
                                        <textarea title="Enter Remarks" name="remarks[<?php echo $r[ "studentid" ]?>]" id="remarks" class="col-xs-10"><?php echo unslash( $r["remarks"] ); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
					}
				}
				?>
                
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-9">
                            <button class="btn btn-info" type="submit" name="examination_result_students_edit" title="Update Record">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update
                            </button>
                            <?php echo pages_list(10, "", $sql, $pageNum);?>
                        </div>
                    </div>
                </div>
            </form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>