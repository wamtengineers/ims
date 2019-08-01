<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if( isset($_GET["date"]) ){
	$_SESSION["holidays"]["list"]["date"] = $_GET["date"];
}
if(isset($_SESSION["holidays"]["list"]["date"]) && !empty($_SESSION["holidays"]["list"]["date"])){
	$date = $_SESSION["holidays"]["list"]["date"];
}
else{
	$date = "";
}
if( !empty($date) ){
	$extra=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date)))."' and date<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($date))+3600*24)."'";
	$is_search=true;
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Holidays
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
                                    <div class="dataTables_length col-xs-2" id="dynamic-table_length">
                                        <div class=""><a href="holidays_manage.php?tab=add" class="btn btn-sm btn-primary">Add New Record</a></div>
                                    </div>
                                    <div class="col-xs-10 align-right search">
                                    	<form action="" method="get">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-check"></i>
                                                </span>
                                                <input class="form-control search-query datepicker" value="<?php echo $date;?>" name="date" id="search" type="text">
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
                                        <th width="30%">Date</th>
                                        <th width="30%">Is Working Day</th>
                                        <th width="15%" class="center">Status</th>
                                        <th width="15%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$sql="select * from holidays where 1 $extra";
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
                                                <td><?php echo date_convert($r["date"]); ?></td>
                                                <td><?php echo $working_days[$r["is_working_day"]]; ?></td>
                                                <td class="center">
                                                	<a href="holidays_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                                                        <a class="green" title="Edit Record" href="holidays_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
            
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="holidays_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
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
                                                                    <a title="Edit Record" href="holidays_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="holidays_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
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
                                            <td colspan="3" class="actions">
                                                <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                                                    <option value="null">Bulk Action</option>
                                                    <option value="delete">Delete</option>
                                                    <option value="statuson">Set Status On</option>
                                                    <option value="statusof">Set Status Off</option>
                                                </select>
                                                <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
                                            </td>
                    						<td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "holidays", $sql, $pageNum)?></td>
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