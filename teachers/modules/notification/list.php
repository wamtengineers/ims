<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
if( $_SESSION[ "current_school_id" ] != 0 ) {
	$extra=" and a.school_id='".$_SESSION[ "current_school_id" ]."'";
}
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["notification_list"]["q"]=$q;
}
if(isset($_SESSION["notification_list"]["q"]))
	$q=$_SESSION["notification_list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and a.title like '%".$q."%'";
	$is_search=true;
}
?>
<div class="page-content">
    <div class="page-header">
    	<div class="row clearfix">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h1>
                    Manage Notification
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        All the administrators who can use the admin panel
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
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="dataTables_length col-md-6 col-sm-6 col-xs-12" id="dynamic-table_length">
                                        <div class=""><a href="notification_manage.php?tab=add" class="btn btn-sm btn-primary">Add New Record</a></div>
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
                                        <th width="15%">Target</th>
                                        <th width="30%">Class Section </th>
                                        <th width="15%">Students </th>
                                        <th width="15%">Title</th>
                                        <th width="20%">Date</th>
                                        <th width="10%">Send SMS</th>
                                        <th width="5%" class="center">Status</th>
                                        <th width="5%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$sql="select a.* from notification a inner join notification_2_class_section b on a.id=b.notification_id inner join class_section c on b.class_section_id=c.id inner join subject_teachers d on c.id=d.class_section_id where 1 ".$extra." and d.employee_id='".$_SESSION[ "logged_in_teachers" ][ "id" ]."' and is_class_teacher=1 order by date desc";
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
                                                <td><?php echo $r[ "target" ]=='0'?'Student':'Employee'?></td>
                                                <td>
													<?php
														if($r[ "target" ]==0){
															$class_section = array();
															$rs2 = doquery("select title, c.class_name from notification_2_class_section a inner join class_section b on a.class_section_id=b.id left join class c on b.class_id = c.id where notification_id='".$r["id"]."'", $dblink);
															$cls = '';
															if( numrows( $rs2 ) > 0 ) {
																while( $r2 = dofetch( $rs2 ) ) {
																	$cls .= $r2[ "class_name" ];
																	$cls .= '-';
																	$cls .= $r2[ "title" ];
																	$cls .= ' ,';
																}
															}
															echo trim( $cls, ',');
														}
													?>
                                                </td>
                                                <td>
                                                	<?php 
													if($r[ "target" ]==0){
														?>
                                                		<a href="notification_manage.php?tab=student&id=<?php echo $r['id'];?>" class="btn btn-sm btn-primary">Selected Student</a>
                                                    	<?php 
													}
													?>
                                                </td>
                                                <td><?php echo unslash($r["title"]); ?></td>
                                                <td><?php echo date_convert($r["date"]); ?></td>
                                                <td><a href="notification_manage.php?tab=send_sms&id=<?php echo $r['id'];?>" class="btn btn-sm btn-primary">Send SMS</a></td>
                                                <td class="center">
                                                	<a href="notification_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
                                                    	<?php
														if($r["status"]==0){
															?>
															<img src="<?php echo $site_url?>/images/offstatus.png" alt="Off" title="Set Status On">
															<?php
														}
														else{
															?>
															<img src="<?php echo $site_url?>/images/onstatus.png" alt="On" title="Set Status Off">
															<?php
														}
														?>
                                                    </a>
                                                </td>
                                                <td class="center">
                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                        <a class="green" title="Edit Record" href="notification_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
            
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="notification_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
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
                                                                    <a title="Edit Record" href="notification_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="notification_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
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
                    						<td colspan="5" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "notification", $sql, $pageNum)?></td>
                						</tr>
                						<?php	
            						}
            						else{	
                						?>
                                        <tr>
                                            <td colspan="10" class="no-record">No Result Found</td>
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