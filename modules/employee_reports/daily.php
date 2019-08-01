<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
if( isset($_GET["date"]) ){
	$_SESSION["employee"]["reports"]["daily"]["date"] = $_GET["date"];
}
if(isset($_SESSION["employee"]["reports"]["daily"]["date"]) && !empty($_SESSION["employee"]["reports"]["daily"]["date"])){
	$date = $_SESSION["employee"]["reports"]["daily"]["date"];
}
else{
	$date = date("d/m/Y");
}
if( !empty($date) ){
	$extra.=" and (checked_in>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date)))."' and checked_in<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($date))+3600*24)."' or checked_out>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date)))."' and checked_out<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($date))+3600*24)."')";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Daily Attendance
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All Employees Reports 
            </small>
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="dataTables_length col-md-3 col-sm-3 col-xs-12" id="dynamic-table_length">
                                        <div class=""><h4 class="blue">Employee Reports</h4></div>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-12 align-right search">
                                        <form action="" method="get">
                                            <input type="hidden" name="tab" value="daily"  />
                                            <div class="date-from form-group">
                                                <input type="text" title="Enter Date" value="<?php echo $date;?>" name="date" id="date" class="datepicker" />
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                        Search
                                                    </button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">S.No</th>
                                        <th width="5%" class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" id="select_all" value="0" title="Select All Records" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th width="30%">Employee Name</th>
                                        <th width="25%">Checked In</th>
                                        <th width="25%">Checked Out</th>
                                        <th width="15%" class="center">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql="select * from employee where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by name";
                                    $rs=doquery($sql,$dblink);
                                    if(numrows($rs)>0){
                                        $sn=1;
                                         while($r=dofetch($rs)){ 
										 	$sql2="select * from employee_attendance where employee_id='".$r["id"]."' $extra";
											$attendance = doquery( $sql2, $dblink );            
                                            if( numrows( $attendance ) > 0 ) {
												$attendance = dofetch( $attendance );
												$attendance = array(
													"in" => !is_null($attendance[ "checked_in" ])?datetime_convert( $attendance[ "checked_in" ] ):'--',
													"out" => !is_null($attendance[ "checked_out" ])?datetime_convert( $attendance[ "checked_out" ] ):'--',
													"time" => calc_time( $attendance[ "checked_in" ], $attendance[ "checked_out" ] )
												);
											}
											else {
												$attendance = array(
													"in" => '--',
													"out" => '--',
													"time" => 'absent'
												);
											}
											?>
                                            <tr class="<?php if($attendance[ "time" ]=="absent") echo "danger";?>">
                                                <td><?php echo $sn;?></td>
                                                <td class="center">
                                                    <label class="pos-rel">
                                                        <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td><?php echo unslash( $r[ "name" ] )?></td>
                                                <td><?php echo $attendance[ "in" ]; ?></td>
                                                <td><?php echo $attendance[ "out" ]; ?></td>
                                                <td><?php echo is_array($attendance["time"])?(($attendance["time"][0]>0?$attendance["time"][0]." hrs ":"").($attendance["time"][1]>0?$attendance["time"][1]." mins ":"")):$attendance[ "time" ]; ?></td>
                                            </tr> 
                                         <?php 
                                            $sn++;
                                         }
                                         ?> 
                                        <tr class="modal-footer no-margin-top">
                                            <td colspan="6" class="actions">
                                                <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                                                    <option value="null">Bulk Action</option>
                                                    <option value="delete">Delete</option>
                                                </select>
                                                <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
                                            </td>
                                            
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