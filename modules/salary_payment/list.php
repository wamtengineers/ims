<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Salary Payment
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All the administrators who can use the admin panel
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
                                        <div class=""><a href="salary_payment_manage.php?tab=add" class="btn btn-sm btn-primary">Add New Record</a></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-12 align-right search">
                                    	<form action="" method="get">
                                        	<select name="employee_id" id="employee_id" class="custom_select">
                                                <option value=""<?php echo ($employee_id=="")? " selected":"";?>>All Employee</option>
                                                <?php
                                                    $res=doquery("select * from employee where status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by name",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                		?>
                                                		<option value="<?php echo $rec["id"]?>" <?php echo($employee_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"])?></option>
                                                		<?php
                                                    	}
                                                    }	
                                                ?>
                                            </select>
                                            <div class="input-group">
                                            	<input class="form-control search-query date-timepicker" value="<?php echo $date_from;?>" name="date_from" id="date_from" type="text" placeholder="Date From">
                                            </div>
                                            <div class="input-group">
                                            	<input class="form-control search-query date-timepicker" value="<?php echo $date_to;?>" name="date_to" id="date_to" type="text" placeholder="Date To">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                        Search
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm reset_search" alt="Reset Record" title="Reset Record">
                                                        Reset
                                                    </button>
                                                </span>
                                            </div>
                                            <a class="btn btn-primary btn-sm" href="salary_payment_manage.php?tab=print"><i class="fa fa-print" aria-hidden="true"></i></a>
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
                                        <th width="25%">Employee</th>
                                        <th width="15%">Datetime</th>
                                        <th width="15%">Amount</th>
                                        <th width="15%">Paid By</th>
                                        <th width="10%" class="center">Status</th>
                                        <th width="10%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									$rs=show_page($rows, $pageNum, $sql);
									if(numrows($rs)>0){
										$sn=1;
										while($r=dofetch($rs)){           
											?>
                                            <tr>
                                                <td><?php echo $sn;?></td>
                                                <td class="center">
                                                    <label class="pos-rel">
                                                        <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td><?php if($r["employee_id"]==0) echo "Default"; else echo get_field($r["employee_id"], "employee","name");?></td>
                                                <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
                                                <td><?php echo curr_format(unslash($r["amount"])); ?></td>
                                                <td><?php if($r["account_id"]==0) echo "Default"; else echo get_field($r["account_id"], "account","title");?></td>
                                                <td class="center">
                                                	<a href="salary_payment_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
                                                    	<?php
														if($r["status"]==0){
															?>
															<img src="images/offstatus.png" alt="Off" title="Set Status On">
															<?php
														}
														else{
															?>
															<img src="images/onstatus.png" alt="On" title="Set Status Off">
															<?php
														}
														?>
                                                    </a>
                                                </td>
                                                <td class="center">
                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                        <a class="green" title="Edit Record" href="salary_payment_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
            
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="salary_payment_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
                                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                        </a>
                                                    </div>
                                                    <div class="hidden-md hidden-lg">
                                                        <div class="inline pos-rel">
                                                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                                <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                <li>
                                                                    <a title="Edit Record" href="salary_payment_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="salary_payment_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
                                                                        <span class="red">
                                                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>  
                                    		<?php 
                    						$sn++;
                						}
                						?>
                                        <tr class="modal-footer no-margin-top">
                                            <td colspan="5" class="actions">
                                                <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                                                    <option value="null">Bulk Action</option>
                                                    <option value="delete">Delete</option>
                                                    <option value="statuson">Set Status On</option>
                                                    <option value="statusof">Set Status Off</option>
                                                </select>
                                                <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
                                            </td>
                    						<td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "salary_payment", $sql, $pageNum)?></td>
                						</tr>
                						<?php	
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
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>