<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Subject Teachers
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All Subject Teachers
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
                                    <div class="col-md-6 col-sm-6 col-xs-12 search">
                                    	<form action="" method="get">
                                        	
                                          
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%" class="center">S.No</th>
                                        <th width="10%" class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" id="select_all" value="0" title="Select All Records" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th width="20%">Class Section</th>
                                        <th width="50%">Subject Teachers</th>
                                        <th width="10%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$sql="select a.id, a.title as section, b.id as class_id, b.class_name, c.title as class_level from class_section a inner join class b on a.class_id = b.id inner join class_level c on b.class_level_id=c.id where a.school_id = '".$_SESSION["current_school_id"]."' order by c.sortorder, b.class_name, a.title";
									$rs=show_page($rows, $pageNum, $sql);
									if(numrows($rs)>0){
										$sn=1;
										while($r=dofetch($rs)){             
											?>
                                            <tr>
                                            
                                                <td class="center"><?php echo $sn;?></td>
                                                <td class="center">
                                                    <label class="pos-rel">
                                                        <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td><?php echo unslash( $r[ "class_name" ] )." - ".unslash( $r["section"] ); ?></td>
                                                <td><?php
                                                	$rs2 = doquery( "select a.*, c.name from subject a left join subject_teachers b on a.id = b.subject_id and class_section_id = '".$r[ "id" ]."' left join employee c on b.employee_id = c.id where class_id = '".$r[ "class_id" ]."' order by sortorder", $dblink );
													if( numrows( $rs2 ) > 0 ) {
														while( $r2 = dofetch( $rs2 ) ) {
															echo unslash( $r2[ "title" ] ).( !empty( $r2[ "name" ] )?" (".unslash( $r2[ "name" ] ).")":" " )."<br>";
														}
													}
												?></td>
                                                <td class="center">
                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                        <a class="green" title="Edit Record" href="subject_teachers_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
                                                       
                                                    </div>
                                                    <div class="hidden-md hidden-lg">
                                                        <div class="inline pos-rel">
                                                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                                <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                <li>
                                                                    <a title="Edit Record" href="subject_teachers_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
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
                                            
                    						<td colspan="7" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "subject_teachers", $sql, $pageNum)?></td>
                						</tr>
                						<?php	
            						}
            						else{	
                						?>
                                        <tr>
                                            <td colspan="7"  class="no-record">No Result Found</td>
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