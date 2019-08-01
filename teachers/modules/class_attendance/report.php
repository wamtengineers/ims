<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<style>
.head{ display:none}
@media print{
	.no-print, .footer, .page-header{ display:none;}
	.head{ display:block; width:100%; text-align:center;}
}
</style>
<div class="page-content">
    <div class="page-header">
    	<div class="row clearfix">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h1>
                    Class Attendance
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        Student's Attendance
                    </small>
                </h1>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 align-right">
                <a href="index.php" class="btn btn-sm btn-primary">Back to list</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                            <div class="row no-print">
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-md-6 col-sm-6 col-xs-12 search">
                                    	<form action="" method="get">
                                        	<input type="hidden" name="tab" value="report" />
                                        	<div class="input-group">
                                            	<span class="input-group-addon">
                                                    <i class="ace-icon fa fa-calendar"></i>
                                                </span>
                                                <input class="form-control search-query datepicker" placeholder="Date From" value="<?php echo $from;?>" name="date_from" id="date_from" type="text">
                                            </div>
                                            <div class="input-group">
                                            	<span class="input-group-addon">
                                                    <i class="ace-icon fa fa-calendar"></i>
                                                </span>
                                                <input class="form-control search-query datepicker" placeholder="Date To" value="<?php echo $to;?>" name="date_to" id="date_to" type="text">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                        Search
                                                    </button>&nbsp;
                                                    <a class="btn btn-sm btn-primary" href="" onclick="window.print();return false;" style="margin-left:4px"><i class="fa fa-print" aria-hidden="true"></i></a>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="head">
									<?php echo get_config( 'fees_chalan_header' )?>
                                    <h2>Student Attendance List</h2>
                                    <p>
                                        <?php
                                        echo "List of";
                                        if( !empty( $from ) ){
                                            echo " from ".$from;
                                        }
                                        if( !empty( $to ) ){
                                            echo " to ".$to;
                                        }
                                        ?>
                                    </p>
                            </div>
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                	
                                    <tr>
                                        <th width="5%" class="center">S.No</th>
                                        <th width="40%">Student</th>
                                        <th width="20%">Total Days</th>
                                        <th width="20%">Present Days</th>
                                        <th width="15%">Absent Days</th>
                                        <!--<th width="15%" class="center">Action</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									//$rs=show_page($rows, $pageNum, $sql);
									$start_ts = strtotime( date_dbconvert( $from ) );
									$end_ts = strtotime( date_dbconvert( $to ) );
									$one_day = 24*3600;
									$total_days = 0;
									$total_present = 0;
									$sn=1;
                                   	for( $i = $start_ts; $i <= $end_ts; $i += $one_day ) {
										if( !is_holiday( date("Y-m-d", $i) ) ) {
											$total_days++;
										}
									}
									if(numrows($rs)>0){
										$sn=1;
										$total_present = 0;
										while($r=dofetch($rs)){             
											?>
                                            <tr>
                                                <td class="center"><?php echo $sn;?></td>
                                                <td><?php echo unslash($r["student_name"]); ?></td>
                                                <td class="text-right"><?php echo $total_days?></td>
                                                <td class="text-right">
                                                	<?php
                                                    $count = dofetch( doquery( "select count(1) from student_attendance where student_id = '".$r[ "id" ]."' and date >= '".date_dbconvert( $from )."' and date <= '".date_dbconvert( $to )."'", $dblink ) );
													echo $count[ "count(1)" ];
													?>
                                                </td>
                                                <td class="text-right">
													<?php
														echo $total_days-$count[ "count(1)" ];
													?>
                                                </td>
                                                <!--<td class="text-center">
                                                	<a href="class_attendance_manage.php?tab=student_attendance&id=<?php echo $r['id'];?>"><img src="../images/view_image.png" alt="Attendance" title="View Attendance" style="width:30px" /></a>
                                                </td>-->
                                            </tr>  
                                    		<?php 
                    						$sn++;
                						}
                						?>
                                        <tr class="modal-footer no-margin-top no-print">
                    						<td colspan="6" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "student", $sql, $pageNum)?></td>
                						</tr>
                						<?php	
            						}
            						else{	
                						?>
                                        <tr>
                                            <td colspan="6"  class="no-record">No Result Found</td>
                                        </tr>
                						<?php
            						}
            						?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>