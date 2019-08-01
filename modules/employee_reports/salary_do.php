<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["save_salary"])){
	$start_date = strtotime(date_dbconvert($_POST[ "start_date" ]));
	$salary_date = date("Y-m-t",$start_date);
	//echo $salary_date;die;
	$year = date( "Y", $start_date );
	$month = date( "n", $start_date )-1;
	$sql="select * from employee where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by name";
    $rs=doquery($sql,$dblink);
	if( numrows( $rs ) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			if( isset( $_POST[ "monthly_salary" ][ $r[ "id" ] ] ) ) {
				set_employee_meta($r["id"], "monthly_salary", $_POST[ "monthly_salary" ][ $r[ "id" ] ]);
			}
			if( isset( $_POST[ "salary" ][ $r[ "id" ] ] ) ) {
				$salary = doquery( "select id, amount from salary where employee_id='".$r[ "id" ]."' and month='".$month."' and year='".$year."'", $dblink );
				if( numrows( $salary ) > 0 ) {
					$salary = dofetch( $salary );
					doquery( "update salary set amount='".slash($_POST[ "salary" ][ $r[ "id" ] ])."' where id='".$salary[ "id" ]."'", $dblink );
					$salary_id = $salary[ "id" ];
				}
				else {
					doquery( "insert into salary(school_id, employee_id, month, year, datetime_added, amount) values('".$_SESSION["current_school_id"]."', '".$r[ "id" ]."', '".$month."', '".$year."',  '".$salary_date."', '".slash($_POST[ "salary" ][ $r[ "id" ] ])."' )", $dblink );
					$salary_id = inserted_id();
				}
				if( isset( $_POST[ "payment" ][ $r[ "id" ] ] ) && !empty( $_POST[ "payment" ][ $r[ "id" ] ] ) ) {
					$salary_payment = doquery( "select id from salary_payment where salary_id='".$salary_id."'", $dblink );
					if( numrows( $salary_payment ) > 0 ) {
						$salary_payment = dofetch( $salary_payment );
						doquery( "update salary_payment set amount='".slash($_POST[ "payment" ][ $r[ "id" ] ])."', account_id='".slash($_POST[ "account_id" ][ $r[ "id" ] ])."', details='".slash($_POST[ "details" ][ $r[ "id" ] ])."' where id='".$salary_payment[ "id" ]."'", $dblink );
					}
					else {
						doquery( "insert into salary_payment(school_id, employee_id, salary_id, datetime_added, amount, account_id, details) values('".$_SESSION["current_school_id"]."', '".$r[ "id" ]."', '".$salary_id."', NOW(), '".slash($_POST[ "payment" ][ $r[ "id" ] ])."', '".slash($_POST[ "account_id" ][ $r[ "id" ] ])."', '".slash($_POST[ "details" ][ $r[ "id" ] ])."' )", $dblink );
					}
				}
			}
		}
	}
}
$extra='';
if( isset($_GET["salary_month"]) ){
	$_SESSION["employee"]["reports"]["salary"]["salary_month"] = $_GET["salary_month"];
}
if(isset($_SESSION["employee"]["reports"]["salary"]["salary_month"]) && !empty($_SESSION["employee"]["reports"]["salary"]["salary_month"])){
	$salary_month = $_SESSION["employee"]["reports"]["salary"]["salary_month"];
}
else{
	$salary_month = date("Ym");
}
$s = strtotime($salary_month."01");
$start_date = date( "d/m/Y", $s );
$end_date = date( "d/m/Y", strtotime( "last day of this month", $s ) );
$year = date( "Y", $s );
$month = date( "n", $s )-1;
$accounts = array();
$rs = doquery( "select * from account where school_id = '".$_SESSION["current_school_id"]."' order by title", $dblink );
if( numrows( $rs ) > 0 ) {
	while( $r = dofetch( $rs ) ) {
		$accounts[] = array(
			"id" => $r[ "id" ],
			"title" => unslash( $r[ "title" ] )
		);
	}
}
$employees = array();
$sql="SELECT a.*, c.title as department, d.title as designation FROM `employee` a left join employee_meta b on a.id = b.employee_id and b.meta_key='date_of_app' left join `department` c on a.department_id = c.id left join designation d on a.designation_id = d.id where a.status=1 and a.school_id = '".$_SESSION["current_school_id"]."' order by c.sortorder, b.meta_value, a.name";
$rs=doquery($sql,$dblink);
if(numrows($rs)>0){
	$sn=1;
	 while($r=dofetch($rs)){
		
		$working_days = 0;
		$present = 0;
		$absent = 0;
		
		$on_time_in = 0;
		$late_in = 0;
		$very_late_in = 0;
		$no_in = 0;
		
		$on_time_out = 0;
		$early_out = 0;											
		$very_early_out = 0;
		$no_out = 0;
		$time_from = get_employee_meta($r["id"], "timing_from");
		if( empty( $time_from ) ) {
			$time_from = '08:00';
		}
		$time_to = get_employee_meta($r["id"], "timing_to");
		if( empty( $time_to ) ) {
			$time_to = '14:00';
		}
		$timing_from = explode( ":", $time_from);
		$timing_to = explode( ":", $time_to);
		for( $i = 0; $i < date( "j", strtotime(date_dbconvert($end_date)) ); $i++ ) {
			$day = strtotime(date_dbconvert($start_date))+24*3600*$i;
			if( !is_holiday( date("Y-m-d", $day) ) ) {
				$sql2="select * from employee_attendance where employee_id='".$r["id"]."' and (checked_in>='".date("Y/m/d H:i:s", $day)."' and checked_in<'".date("Y/m/d H:i:s", $day+24*3600)."' or checked_out>='".date("Y/m/d H:i:s", $day)."' and checked_out<'".date("Y/m/d H:i:s", $day+24*3600)."')";	
				$rs2 = doquery( $sql2, $dblink );
				if( numrows( $rs2 ) > 0 ) {
					$rs2 = dofetch( $rs2 );
					if( !is_null($rs2[ "checked_in" ]) ){
						$time_in_hr = date( "H", strtotime($rs2[ "checked_in" ]));
						$time_in_min = date( "i", strtotime($rs2[ "checked_in" ]));
						if( $timing_from[0] > $time_in_hr ) {
							$on_time_in++;
						}
						else {
							if( $timing_from[0] == $time_in_hr ) {
								if( $timing_from[1] <= $time_in_min ) {
									$on_time_in++;
								}
								else {
									$diff = $time_in_min - $timing_from[1];
									if( $diff <= 30 ) {
										$late_in++;
									}
									else {
										$very_late_in++;
									}
								}
							}
							else {
								$diff = $time_in_min + $timing_from[1];
								if( $diff <= 30 ) {
									$late_in++;
								}
								else {
									$very_late_in++;
								}
							}
						}
					}
					else {
						$no_in++;
					}
					if( !is_null($rs2[ "checked_in" ]) ){
						$time_out_hr = date( "H", strtotime($rs2[ "checked_out" ]));
						$time_out_min = date( "i", strtotime($rs2[ "checked_out" ]));
						if( $timing_to[0] < $time_out_hr ) {
							$on_time_out++;
						}
						else {
							if( $timing_to[0] == $time_out_hr ) {
								if( $timing_to[1] >= $time_out_min ) {
									$on_time_out++;
								}
								else {
									$diff = $timing_to[1] - $time_out_min;
									if( $diff <= 30 ) {
										$early_out++;
									}
									else {
										$very_early_out++;
									}
								}
							}
							else {
								$diff = $time_out_min + $timing_to[1];
								if( $diff <= 30 ) {
									$early_out++;
								}
								else {
									$very_early_out++;
								}
							}
						}
					}
					else {
						$no_out++;
					}
					$present++;
				}
				else {
					$absent++;
				}
				$working_days++;
			}
		}
		$salary = get_employee_meta($r["id"], "monthly_salary");
		$total_days = $working_days-$absent;
		$total_days -= 0.5*$no_in;
		$total_days -= 0.25*$late_in;
		$total_days -= 0.5*$very_late_in;
		$total_days -= 0.25*$early_out;
		$total_days -= 0.5*$very_early_out;
		$total_days -= 0.5*$no_out;
		$payment_amount = 0;
		$account_id = get_account_of_type( 2 );
		$details = '';
		$final_salary = 0;
		$balance = 0;
		$fs = doquery( "select id, amount from salary where employee_id='".$r[ "id" ]."' and month='".$month."' and year='".$year."'", $dblink );
		if( numrows( $fs ) > 0 ) {
			$fs = dofetch( $fs );
			$final_salary = round($fs[ "amount" ]);
			//$payment_amount = $final_salary;
			$payment = doquery( "select * from salary_payment where employee_id='".$r[ "id" ]."' and salary_id='".$fs[ "id" ]."'", $dblink );
			if( numrows( $payment ) > 0 ) {
				$payment = dofetch( $payment );
				$payment_amount = $payment[ "amount" ];
				$account_id = $payment[ "account_id" ];
				$details = unslash( $payment[ "details" ] );
			}														
		}
		$bl = dofetch( doquery( "SELECT sum(ifnull(b.amount,0))-sum(a.amount) as balance FROM `salary_payment` a left join salary b on a.salary_id = b.id where a.employee_id = '".$r[ "id" ]."' and a.datetime_added < '".date('Y-m-d 00:00:00', strtotime(date_dbconvert($start_date)))."'", $dblink ) );
		$balance = $bl[ "balance" ];
		$employees[] = array(
			"employee" => $r,
			"working_days" => $working_days,
			"present" => $present,
			"absent" => $absent,
			"on_time_in" => $on_time_in,
			"late_in" => $late_in,
			"very_late_in" => $very_late_in,
			"on_time_out" => $on_time_out,
			"early_out" => $early_out,
			"very_early_out" => $very_early_out,
			"no_out" => $no_out,
			"salary" => $salary,
			"total_days" => $total_days,
			"final_salary" => $final_salary,
			"balance" => $balance,
			"payment_amount" => $payment_amount,
			"account_id" => $account_id,
			"details" => $details,			
		);
	}
}
if( isset( $_GET[ "action" ] ) && $_GET[ "action" ] == 'print' ) {
	if( count( $employees ) > 0 ) {
		?>
		<!doctype html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Untitled Document</title>
        <style>@charset "utf-8";
/* CSS Document */
.wrapper{ width:1024px; margin:0 auto 30px; padding-bottom:30px; border-bottom:dashed 1px;}
.wrapper:nth-child(3n){ page-break-after:always;}
.clear:after { 
  content: "";
  clear: both;
  display: table;
}
.salary {
  font-size: 46px;
  font-family: arial;
}
.date span {
  font-size: 16px;
  font-family: arial;
  font-weight: bold;
}
.logo_area {
    float: left;
    width: 700px;
}

.address span {
  font-size: 24px;
  margin: 0px 0px 6px;
  padding: 0px;
  line-height: 20px;
  font-family: arial;
  font-weight: bold;
  display: block;
}
.address p {
  margin: 0px 0px 0px;
  font-size:16px;
  font-family:arial;
}
.address {
  margin-top: 20px;
}
.logo {
    width: 120px;
    float: left;
    position: relative;
    margin-left: -28px;
	margin-right:30px;
}
.logo_area img {
	width:100%;
  margin:0 auto;
  display:block;
}
.right_details{ float:right; font-family:arial;}
.employee_details{ margin:0 0 20px;}
.header_inn {
  padding: 24px 0px 30px;
}
.name span {
  display: block;
  margin-bottom: 4px;
  font-size: 16px;
  font-family: arial;
  font-weight: bold;
}
.name p {
  margin: 0px;
}
.address h1 {
    margin: 0;
    font-family: sans-serif;
    font-size: 22px;
}

h1 {}

.address h2 {
    margin-top: 0;
    font-weight: normal;
    font-family: sans-serif;
    margin: 5px 0;
    font-size: 16px;
}

.logo {height: 80px;width: auto;margin: 0 20px 0px 0px;}

.logo_area img {
    height: 100%;
    width: auto;
}

.address {
    margin: 0;
}

.address p {
    font-size: 12px;
}

.salary {
    text-transform: uppercase;
    font-size: 22px;
    font-weight: bold;
    text-align: right;
}

.name span {
    font-size: 12px;
    display: inline;
    margin-right: 10px;
}

.name p {
    display: inline;
}

.name {
    border-bottom: solid 1px;
    margin-bottom: 5px;
}
table {
    width: 100%;
    border-collapse: collapse;
}

td, th {
    border: solid 1px;
    padding: 5px;
    text-align: left;
}
td.no-border {
    border: 0;
}
td strong{ font-size:12px; font-family: arial; font-weight:bold;}
td{ width: 12.5%}
</style>
        </head>
        <body>
			<?php
			$fees_chalan_header = get_config( 'fees_chalan_header' );
            foreach( $employees as $employee ) {
				?>
				<div class="wrapper">
                    <div class="header">
                        <div class="header_inn clear">
                            <div class="logo_area">
                                <div class="logo"><img src="<?php echo $file_upload_url;?>config/<?php echo $school_logo?>" alt="image" /></div>
                                <div class="address">
                                    <?php echo $fees_chalan_header;?>
                                </div>
                            </div>
                            <div class="right_details">
                                    
                                <div class="date_area">
                                    <div class="salary">Pay Slip</div>
                                    <div class="date"><span>Date: </span><?PHP echo date( "d/m/Y" )?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="employee_details">
                            <div class="name"><span>Employee Name:</span><p><?php echo unslash( $employee[ "employee" ][ "name" ] )?></p></div>
                            <div class="name"><span>Designation:</span><p><?php echo unslash( $employee[ "employee" ][ "designation" ] )?></p></div>
                            <div class="name"><span>Department:</span><p><?php echo unslash( $employee[ "employee" ][ "department" ] )?></p></div>
                        </div>
                        <table>
                        	<tr>
                            	<th colspan="10">Details</th>
                            </tr>
                            <tr>
                            	<td><strong>Month</strong></td>
                                <td><strong>Working Days</strong></td>
                                <td><strong>Present</strong></td>
                                <td><strong>Absent</strong></td>
                                <td><strong>Late In/Out</strong></td>
                                <td><strong>Total Days</strong></td>
                                <td><strong>Salary</strong></td>
                                <td><strong>Calculated</strong></td>
                            </tr>
                            <tr>
                            	<td><?php echo date( "M Y", $s )?></td>
                                <td style="text-align: right"><?php echo $employee[ "working_days" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "present" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "absent" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "present" ]-$employee[ "total_days" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "total_days" ]?></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "salary" ] )?></td>
                                <td style="text-align: right"><?php echo curr_format( round( ($employee[ "salary" ]/$employee[ "working_days" ]) * $employee[ "total_days" ] ) ); ?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Approved</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "final_salary" ] )?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Previous Balance</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "balance" ] )?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Payment</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "payment_amount" ] )?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Remaning Balance</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "final_salary" ]+$employee[ "balance" ]-$employee[ "payment_amount" ] )?></td>
                            </tr>
                        </table>
                	</div>
                </div>
				<?php
			}
			?>
		</body>
		</html>
        <?php
	}
	die;
}