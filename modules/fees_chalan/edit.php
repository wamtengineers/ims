<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Fees Chalan
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Fees Chalan
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="fees_chalan_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="fees_chalan_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="student_2_class_id">Student Class <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <?php
							$res=doquery("Select * from student_2_class a left join student b on a.student_id = b.id left join class_section c on a.class_section_id = c.id left join class d on c.class_id = d.id where a.id = '".$student_2_class_id."'",$dblink);
							if(numrows($res)>0){
								$rec=dofetch($res);
								echo unslash( $rec[ "student_name" ] )." (Class: ".unslash( $rec[ "class_name" ] )."-".unslash( $rec[ "title" ] ).")";
							}
							?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="academic_year_id">Academic Year <span class="red">*</span></label>
                        <div class="col-sm-9">
                            <?php echo get_field( $academic_year_id, "academic_year" );?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="month">Month (YYYYMM) </label>
                        <div class="col-sm-9">
                            <?php echo date( "M Y", strtotime( $month."01" ));?>
                            <input type="text" title="Enter Month" value="<?php echo $month; ?>" name="fee_month" id="fee_month" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label">
                            <label class="form-label" for="issue_date">Date</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Date" value="<?php echo $issue_date; ?>" name="issue_date" id="issue_date" class="form-control datepicker" >
                        </div>
                    </div>
                </div>
                <?php
				$fees_ids = array();
                $fees = doquery( "SELECT a.*, b.title, b.id as fees_id FROM `fees_chalan_details` a left join fees b on a.fees_id = b.id where fees_chalan_id = '".$id."' order by sortorder", $dblink );
				if( numrows( $fees ) > 0 ) {
					while( $fee = dofetch( $fees ) ){
						$fees_ids[] = $fee[ "fees_id" ];
						?>
						<div class="form-group fee_container">
                            <div class="row">
                                <div class="col-sm-3 control-label">
                                    <label class="form-label" for="fees_<?php echo $id?>"><?php echo unslash( $fee[ "title" ] )?></label><br />
                                    <a href="#" class="delete_fees" data-id="<?php echo $id?>">Delete</a> <a href="#" class="undo_delete_fees" data-id="<?php echo $id?>" style="display:none">Undo</a>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" title="Enter Month" value="<?php echo unslash( $fee[ "fees_month" ] )?>" name="chalan_month[<?php echo unslash( $fee[ "id" ] )?>]" id="fees_<?php echo $id?>" class="form-control" >
                                    <input type="text" title="Enter Fees" value="<?php echo unslash( $fee[ "fees_amount" ] )?>" name="chalan_fees[<?php echo unslash( $fee[ "id" ] )?>]" id="fees_<?php echo $id?>" class="form-control" >
                                    <input type="hidden" value="0" name="chalan_fees_deleted[<?php echo unslash( $fee[ "id" ] )?>]" id="fees_deleted_<?php echo $id?>" class="form-control" >
                                </div>
                            </div>
                        </div>
						<?php
					}
				}
				?>
                <script>
                $(document).ready(function(){
					$( ".delete_fees" ).click(function(e){
						e.preventDefault();
						$(this).hide();
						$id = $(this).data( "id" );
						$container = $(this).parents( '.fee_container' );
						$container.addClass( 'fee_deleted' );
						$container.find( '.undo_delete_fees' ).show();
						$container.find( '#fees_deleted_'+$id ).val("1");
					});
					$( ".undo_delete_fees" ).click(function(e){
						e.preventDefault();
						$(this).hide();
						$id = $(this).data( "id" );
						$container = $(this).parents( '.fee_container' );
						$container.removeClass( 'fee_deleted' );
						$container.find( '.delete_fees' ).show();
						$container.find( '#fees_deleted_'+$id ).val("0");
					});
				});
                </script>
                <style>
                .fee_deleted{ opacity: 0.1;}
                </style>
                <?php
                $fees = doquery( "SELECT * FROM fees where status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by sortorder", $dblink );
				if( numrows( $fees ) > 0 ) {
					$extra_fees = array();
					while( $fee = dofetch( $fees ) ){
						$extra_fees[] = $fee;
						$fees_amount = str_replace( ",", "", get_student_meta($rec[ "student_id" ], "fees_".$fee[ "id" ]));
						?>
						<div class="form-group extra_fee_container" style="display:none" id="extra_fee_container_<?php echo $fee[ "id" ]?>">
                            <div class="row">
                                <div class="col-sm-3 control-label">
                                    <label class="form-label"><?php echo unslash( $fee[ "title" ] )?></label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" title="Enter Month" value="<?php echo $month?>" name="chalan_extra_fees_month[<?php echo unslash( $fee[ "id" ] )?>]" class="form-control" >
                                    <input type="text" title="Enter Fees" value="<?php echo $fees_amount?>" name="chalan_extra_fees[<?php echo unslash( $fee[ "id" ] )?>]" class="form-control" >
                                </div>
                            </div>
                        </div>
						<?php
					}
					?>
					<div class="form-group extra_fees_container">
                        <div class="row">
                            <div class="col-sm-3 control-label">
                                <label class="form-label">Extra Fees</label>
                            </div>
                            <div class="col-sm-9">
                            	<?php
								foreach( $extra_fees as $extra_fee ) {
									?>
	                                <label><input type="checkbox" value="<?php echo $extra_fee[ "id" ]?>" name="extra_fees[]" class="form-control" onchange="$('#extra_fee_container_<?php echo $extra_fee[ "id" ]?>').toggle()"> <?php echo unslash( $extra_fee[ "title" ] )?></label>
                                    <?php
								}
								?>
                            </div>
                        </div>
                    </div>
					<?php
				}
				?>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="fees_chalan_edit" title="Update Record">
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