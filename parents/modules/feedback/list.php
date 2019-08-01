<?php
if(!defined("APP_START")) die("No Direct Access");
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
                                        <th width="70%">Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$sql="select * from feedback where status = 1 order by date desc";
									$rs=show_page($rows, $pageNum, $sql);
									if(numrows($rs)>0){
										$sn=1;
										while($r=dofetch($rs)){             
											?>
                                            <tr>
                                                <td><?php echo $sn;?></td>
                                                <td><?php echo date_convert($r["date"]); ?></td>
                                                <td><?php echo unslash($r["message"]); ?></td>
                                            </tr>  
                                    		<?php 
                    						$sn++;
                						}
                						?>
                                        <tr class="modal-footer no-margin-top">
                    						<td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "feedback", $sql, $pageNum)?></td>
                						</tr>
                						<?php	
            						}
            						else{	
                						?>
                                        <tr>
                                            <td colspan="3"  class="no-record">No Result Found</td>
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