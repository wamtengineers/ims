<?php
if(!defined("APP_START")) die("No Direct Access");
if( $_SESSION[ "current_school_id" ] != 0 ) {
	$extra=" and school_id='".$_SESSION[ "current_school_id" ]."'";
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Feedback
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All Feedback
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
                                    <div class="dataTables_length col-md-10 col-sm-10 col-xs-12" id="dynamic-table_length">
                                        <div class=""><a href="feedback_manage.php?tab=add" class="btn btn-sm btn-primary">New Feedback</a></div>
                                    </div>
                                    <div class="dataTables_length col-md-2 col-sm-2 col-xs-12" id="dynamic-table_length">
                                        <div class=""><a href="index.php" class="btn btn-sm btn-primary">Back to Dashboard</a></div>
                                    </div>
                                </div>
                            </div>
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">S.No</th>
                                        <th width="20%">Date</th>
                                        <th width="35%">Feedback</th>
                                        <th width="35%">Reply</th>
                                        <th width="5%" class="center">Status</th>
                                        <th width="5%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$sql="select * from feedback where status = 1 $extra order by date desc";
									$rs=show_page($rows, $pageNum, $sql);
									if(numrows($rs)>0){
										$sn=1;
										while($r=dofetch($rs)){             
											?>
                                            <tr>
                                                <td><?php echo $sn;?></td>
                                                <td><?php echo date_convert($r["date"]); ?></td>
                                                <td><?php echo unslash($r["message"]); ?></td>
                                                <td><?php echo unslash($r["reply"]); ?></td>
                                                <td class="center">
                                                	<a href="feedback_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                                                        <a class="green" title="Edit Record" href="feedback_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
            
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="feedback_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
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
                                                                    <a title="Edit Record" href="feedback_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="feedback_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
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
                    						<td colspan="6" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "feedback", $sql, $pageNum)?></td>
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