<?php
if(!defined("APP_START")) die("No Direct Access");
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
                                            <input type="hidden" name="tab" value="salary"  />
                                            <div class="date-from form-group">
                                            	<select name="salary_month">
                                                	<option value="">Select Month</option>
                                                    <?php
                                                    foreach( get_months() as $k => $m ) {
														?>
														<option value="<?php echo $k?>"<?php echo $k==$salary_month?' selected':''?>><?php echo $m?></option>
														<?php
													}
													?>
                                                </select>
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
                            <form method="post">
                                <input type="hidden" value="<?php echo $start_date;?>" name="start_date" />
                                <input type="hidden" value="<?php echo $end_date;?>" name="end_date" />
                                <table id="dynamic-table" class="table list table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="2%">S.No</th>
                                            <th width="20%">Name</th>
                                            <th width="15%">Details</th>
                                            <th width="10%">Salary</th>
                                            <th width="10%">Calculated</th>
                                            <th width="10%">Approved</th>
                                            <th width="10%">Balance</th>
                                            <th width="10%">Paid</th>
                                            <th class="center">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php
                                        if( count( $employees > 0 ) ) {
											$sn=1;
											$department_id = 0;
											foreach( $employees as $employee ){
												if( $employee[ "employee" ][ "department_id" ] != $department_id ) {
													$department_id = $employee[ "employee" ][ "department_id" ];
													?>
													<tr>
                                                    	<th colspan="9"><?php echo unslash( $employee[ "employee" ][ "department" ] )?></th>
                                                    </tr>
													<?php
												}
												?>
                                                <tr class="<?php if($employee[ "final_salary" ]>0 && $employee[ "payment_amount" ] == 0 ) echo " danger";if($employee[ "final_salary" ] > 0 && $employee[ "payment_amount" ]>0) echo " success";?>">
                                                    <td><?php echo $sn;?></td>
                                                    <td><?php echo unslash( $employee[ "employee" ][ "name" ] )?></td>
                                                    <td>
                                                        Working days: <?php echo $employee[ "working_days" ]; ?><br  />
                                                        Present: <?php echo $employee[ "present" ]; ?><br  />
                                                        Absent: <?php echo $employee[ "absent" ]; ?><br  />
                                                        On time in: <?php echo $employee[ "on_time_in" ]; ?><br  />
                                                        Late in: <?php echo $employee[ "late_in" ]; ?><br  />
                                                        Very late in: <?php echo $employee[ "very_late_in" ]; ?><br  />
                                                        On time out: <?php echo $employee[ "on_time_out" ]; ?><br  />
                                                        Early out: <?php echo $employee[ "early_out" ]; ?><br  />
                                                        Very early out: <?php echo $employee[ "very_early_out" ]; ?><br  />
                                                        No out: <?php echo $employee[ "no_out" ]; ?><br  />
                                                    </td>
                                                    <td><input type="text" class="monthly_salary" name="monthly_salary[<?php echo $employee[ "employee" ][ "id" ]?>]" value="<?php echo $employee[ "salary" ]?>" id="monthly_salary_<?php echo $employee[ "employee" ][ "id" ]?>" style="width:80px;"  /></td>
                                                    <td><span data-workingdays="<?php echo $employee[ "working_days" ]?>" data-totaldays="<?php echo $employee[ "total_days" ]?>" class="expected_salary" id="expected_salary_<?php echo $employee[ "employee" ][ "id" ]?>"><?php echo curr_format( round( ($employee[ "salary" ]/$employee[ "working_days" ]) * $employee[ "total_days" ] ) ); ?></span></td>
                                                    <td><input type="text" name="salary[<?php echo $employee[ "employee" ][ "id" ]?>]" value="<?php echo $employee[ "final_salary" ]?>" style="width:80px;"  /></td>
                                                    <td><?php echo curr_format( $employee[ "balance" ] )?></td>
                                                    <td><input type="text" name="payment[<?php echo $employee[ "employee" ][ "id" ]?>]" value="<?php echo $employee[ "payment_amount" ]?>" style="width:80px;"  /></td>
                                                    
                                                    <td>
                                                        <div class="col-md-12">Account:</div>
                                                        <div class="col-md-12"><select name="account_id[<?php echo $employee[ "employee" ][ "id" ]?>]">
                                                            <option value="">Select Account</option>
                                                            <?php
                                                            foreach( $accounts as $account ) {
                                                                ?>
                                                                <option value="<?php echo $account[ "id" ]?>"<?php echo $account[ "id" ]==$employee[ "account_id" ]?' selected':''?>><?php echo $account[ "title" ]?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select></div>
                                                        <div class="col-md-12">Details:</div>
                                                        <div class="col-md-12"><textarea style="width:100%;" name="details[<?php echo $employee[ "employee" ][ "id" ]?>]" ><?php echo $employee[ "details" ]?></textarea></div>
                                                    </td>
                                                </tr> 
                                             <?php 
                                                $sn++;
                                             }
                                        }
                                        else{	
                                            ?>
                                            <tr>
                                                <td colspan="8"  class="no-record">No Result Found</td>
                                            </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <input type="submit" name="save_salary" value="Save Values" /> <a href="employee_reports_manage.php?tab=salary&action=print" target="_blank">Print Salary Sheets</a>
                           	</form>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
		</div>
<script>
$(document).ready(function(){
	$( ".monthly_salary" ).change(function(){
		$salary = parseFloat( $(this).val() );
		$expected_salary = $(this).siblings('.expected_salary');
		$workingdays = parseFloat($expected_salary.data( "workingdays" ));
		$totaldays = parseFloat($expected_salary.data( "totaldays" ));
		$expected_salary.html( Math.round( ($salary/$workingdays)*$totaldays ) );
	});
});
</script>