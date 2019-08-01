<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["index"]["attendance"]["date_from"]=$date_from;
}
if(isset($_SESSION["index"]["attendance"]["date_from"]))
	$date_from=$_SESSION["index"]["attendance"]["date_from"];
else{
	$date_from=date("01/m/Y");
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["index"]["attendance"]["date_to"]=$date_to;
}
if(isset($_SESSION["index"]["attendance"]["date_to"]))
	$date_to=$_SESSION["index"]["attendance"]["date_to"];
else{
	$date_to=date("d/m/Y");
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Attendance Records
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Student: <?php echo $_SESSION["logged_in_students"]["student_name"]?>
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
                                    <div class="dataTables_length col-md-2 col-sm-2 col-xs-12" id="dynamic-table_length">
                                        <div class=""><h4 class="blue">Student Attendance</h4></div>
                                    </div>
                                    <div class="col-md-8 col-sm-8 col-xs-12 search">
                                        <form action="" method="get">
                                            <input type="hidden" name="tab" value="attendance"  />
                                            <span class="col-sm-1 align-right margin-top">From</span>
                                            <div class="col-sm-2 no-padding">
                                                <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control datepicker"  value="<?php echo $date_from?>" >
                                            </div>
                                            <span class="col-sm-1 align-right margin-top">To</span>
                                            <div class="col-sm-2 no-padding">
                                                <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control datepicker"  value="<?php echo $date_to?>" >
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                        Filter
                                                    </button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2">
                                    	<a href="index.php" class="btn btn-sm btn-primary button">Back to Dashboard</a>
                                    </div>
                                </div>
                            </div>
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">S.No</th>
                                        <th width="30%">Date</th>
                                        <th width="25%">Present</th>
                                        <th width="25%">Absent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									$attendance = array();
									$rs = doquery( "select * from student_attendance where date >= '".date_dbconvert( $date_from )."' and date <= '".date_dbconvert( $date_to )."' and student_id = '".$_SESSION["logged_in_students"]["id"]."' order by date", $dblink );
									if( numrows( $rs ) > 0 ) {
										while( $r = dofetch( $rs ) ) {
											$attendance[ strtotime( $r[ "date" ] ) ] = $r[ "date" ];
										}
									}
									$start_ts = strtotime( date_dbconvert( $date_from ) );
									$end_ts = strtotime( date_dbconvert( $date_to ) );
									$one_day = 24*3600;
									$total_days = 0;
									$total_present = 0;
									$sn=1;
                                   	for( $i = $start_ts; $i <= $end_ts; $i += $one_day ) {
										if( !is_holiday( date("Y-m-d", $i) ) ) {
											$total_days++;
											$present = isset( $attendance[ $i ] );
											$total_present += $present?1:0;
											?>
											<tr class="<?php echo !$present?"danger":"";?>">
												<td><?php echo $sn;?></td>
												<td><?php echo date("d/m/Y", $i) ?></td>
												<td><?php echo $present?"yes":""; ?></td>
												<td><?php echo !$present?"yes":""; ?></td>
											</tr>
											<?php
											$sn++;
										}
									}
									if( $total_days > 0 ) {
										?>
										<tr>
											<th>&nbsp;</th>
                                            <th>Total: <?php echo $total_days?></th>
                                            <th>Present: <?php echo $total_present?></th>
                                            <th>Absent: <?php echo $total_days-$total_present?></th>
										</tr>
                                        <?php
									}
									else{
										?>
										<tr>
											<td colspan="4"  class="no-record">No Result Found</td>
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