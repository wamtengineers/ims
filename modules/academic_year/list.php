<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
if( $_SESSION[ "current_school_id" ] != 0 ) {
	$extra=" and school_id='".$_SESSION[ "current_school_id" ]."'";
}
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["academic_year_manage"]["q"]=$q;
}
if(isset($_SESSION["academic_year_manage"]["q"]))
	$q=$_SESSION["academic_year_manage"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and title like '%".$q."%'";
	$is_search=true;
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Academic Year
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
                                    <div class="dataTables_length col-md-6 col-sm-6 col-xs-12" id="dynamic-table_length">
                                        <div class=""><a href="academic_year_manage.php?tab=add" class="btn btn-sm btn-primary">Add New Record</a></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 align-right search">
                                    	<form action="" method="get">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-check"></i>
                                                </span>
                                                <input class="form-control search-query" value="<?php echo $q;?>" name="q" id="search" type="text">
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
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="3%">S.No</th>
                                        <th width="2%" class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" id="select_all" value="0" title="Select All Records" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th width="10%">Title</th>
                                        <th width="10%">Start Date</th>
                                        <th width="10%">End Date</th>
                                        <th>Promotion</th>
                                        <th>Default</th>
                                        <th width="5%">Class</th>
                                        <th width="2%" class="center">View</th>
                                        <th width="3%" class="center">Status</th>
                                        <th width="10%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$sql="select * from academic_year where 1 $extra";
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
                                                <td><?php echo unslash($r["title"]); ?></td>
                                                <td><?php echo date_convert($r["start_date"]);?></td>
                                                <td><?php echo date_convert($r["end_date"]); ?></td>
                                                <td class="center">
                                                	<a href="academic_year_manage.php?tab=promote&id=<?php echo $r['id'];?>">Promote Students</a> - <a href="academic_year_manage.php?tab=import&id=<?php echo $r['id'];?>">Import Students</a>
                                                </td>
                                                <td class="center">
                                                	<a href="academic_year_manage.php?id=<?php echo $r['id'];?>&tab=current_year&y=<?php echo ($r["is_current_year"]==0)?1:0;?>">
                                                    	<?php
														if($r["is_current_year"]==0){
															?>
															<img src="images/offdefault.png" alt="Off">
															<?php
														}
														else{
															?>
															<img src="images/ondefault.png" alt="On">
															<?php
														}
														?>
                                                    </a>
                                                </td>
                                                <td><a href="academic_year_class_manage.php?parent_id=<?php echo $r['id'];?>" class="btn btn-sm btn-primary fancybox iframe">Class</a></td>
                                                <td class="center">
                                                	<a href="academic_year_manage.php?tab=view&id=<?php echo $r['id'];?>"><img src="images/view_image.png" alt="Balance" title="View Balances" style="width:30px" /></a>
                                                </td>
                                                <td class="center">
                                                	<a href="academic_year_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                                                        <a class="green" title="Edit Record" href="academic_year_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
            
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="academic_year_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
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
                                                                    <a title="Edit Record" href="academic_year_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="academic_year_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
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
                                            <td colspan="6" class="actions">
                                                <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                                                    <option value="null">Bulk Action</option>
                                                    <option value="delete">Delete</option>
                                                    <option value="statuson">Set Status On</option>
                                                    <option value="statusof">Set Status Off</option>
                                                </select>
                                                <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
                                            </td>
                    						<td colspan="5" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "academic_year", $sql, $pageNum)?></td>
                						</tr>
                						<?php	
            						}
            						else{	
                						?>
                                        <tr>
                                            <td colspan="11"  class="no-record">No Result Found</td>
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