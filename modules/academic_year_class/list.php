<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra="";
if(isset($_GET["class_section_id"])){
	$class_section_id=slash($_GET["class_section_id"]);
	$_SESSION["academic_year_class_manage"]["list"]["class_section_id"]=$class_section_id;
}
if(isset($_SESSION["academic_year_class_manage"]["list"]["class_section_id"])){
	$class_section_id=$_SESSION["academic_year_class_manage"]["list"]["class_section_id"];
}
else{
	$class_section_id="";
}
if($class_section_id!=""){
	$extra.=" and class_section_id='".$class_section_id."'";
}
$extra.=" and academic_year_id = '".$parent_academic_year_id."'";
$is_search=false;

?>
<div class="page-content">
    <div class="page-header">
    
        <h1>
            <?php echo $academic_year;?>'s Class(s)
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage All the classes <?php echo $academic_year;?> has attended
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
                                        <div class=""><a href="academic_year_class_manage.php?tab=add" class="btn btn-sm btn-primary">Add New Record</a></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-12 align-right search">
                                    	<form action="" method="get">
                                            <div class="input-group">
                                                <select name="class_section_id" id="class_section_id" class="form-control">
                                                    <option value=""<?php echo ($class_section_id=="")? " selected":"";?>>All Classes</option>
                                                    <?php
                                                        $res=doquery("select a.*, b.class_name from class_section a inner join class b on a.class_id=b.id where a.school_id = '".$_SESSION["current_school_id"]."' order by sortorder",$dblink);
                                                        if(numrows($res)>=0){
                                                            while($rec=dofetch($res)){
                                                            ?>
                                                            <option value="<?php echo $rec["id"]?>" <?php echo($class_section_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["class_name"])." - ".unslash($rec["title"])?></option>
                                                            <?php
                                                            }
                                                        }	
                                                    ?>
                                                </select>
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
                                        <th width="20%">Class</th>
                                        <th width="20%">Start Date</th>
                                        <th width="20%">End Date</th>
                                        <th width="15%" class="center">Status</th>
                                        <th width="15%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$sql = "select * from academic_year_class where 1".$extra." order by id";
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
                                                    <td><?php if($r["class_section_id"]==0) echo "Default"; else echo get_field($r["class_section_id"], "class","class_name"); echo "-".get_field($r["class_section_id"], "class_section","title");?></td>
                                                    <td><?php echo date_convert($r["start_date"]);?></td>
                                                    <td><?php echo date_convert($r["end_date"]); ?></td>
                                                    <td class="center">
                                                        <a href="academic_year_class_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                                                            <a class="green" title="Edit Record" href="academic_year_class_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                            </a>
                
                                                            <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="academic_year_class_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
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
                                                                        <a title="Edit Record" href="academic_year_class_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                            <span class="green">
                                                                                <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a onclick="return confirm('Are you sure you want to delete')" href="academic_year_class_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
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
                                                <td colspan="4" class="actions">
                                                    <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                                                        <option value="null">Bulk Action</option>
                                                        <option value="delete">Delete</option>
                                                        <option value="statuson">Set Status On</option>
                                                        <option value="statusof">Set Status Off</option>
                                                    </select>
                                                    <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
                                                </td>
                                                <td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "academic_year_class", $sql, $pageNum)?></td>
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