<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Fees Chalan Receiving
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
            <form class="form-horizontal" role="form" action="fees_chalan_manage.php?tab=edit_receiving" method="post" enctype="multipart/form-data" name="frmAdd">
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
                        <label class="col-sm-3 control-label no-padding-right" for="month">Month </label>
                        <div class="col-sm-9">
                            <?php echo date( "M Y", strtotime( $month."01" ));?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="month">Fees Total </label>
                        <div class="col-sm-9">
                            <?php echo curr_format( $total_amount );?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 control-label">
                            <label class="form-label" for="issue_date">Payment Date</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Date" value="<?php echo $payment_date; ?>" name="payment_date" id="payment_date" class="form-control datepicker" >
                        </div>
                    </div>
                </div>
                <div class="form-group fee_container">
                    <div class="row">
                        <div class="col-sm-3 control-label">
                            <label class="form-label" for="amount">Amount</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" title="Enter Fees" value="<?php echo $amount?>" name="amount" id="amount" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="fees_chalan_receiving_edit" title="Update Record">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update
                            </button>
                            <button class="btn btn-danger" type="button" onclick="window.location.href='fees_chalan_manage.php?tab=edit_receiving&id=<?php echo $id?>&delete'">
                                <i class="ace-icon fa fa-close bigger-110"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>