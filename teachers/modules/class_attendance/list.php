<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["date"])){
	$date=slash($_GET["date"]);
	$_SESSION["class_attendance_manage"]["date"]=$date;
}
if(isset($_SESSION["class_attendance_manage"]["date"]))
	$date=$_SESSION["class_attendance_manage"]["date"];
else
	$date=date("d/m/Y");
if(!empty($date)){
	$extra.=" and class_name like '%".$q."%'";
	$is_search=true;
}
?>
<div class="page-content">
    <div class="page-header">
    	<div class="row clearfix">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h1>
                    Class Attendance
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        Student's Attendance
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
                                    <div class="col-md-6 col-sm-6 col-xs-12 search">
                                    	<form action="" method="get">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-calendar"></i>
                                                </span>
                                                <input class="form-control search-query datepicker" value="<?php echo $date;?>" name="date" id="date" type="text">
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
                                        <th width="5%" class="center">S.No</th>
                                        <th width="5%" class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" id="select_all" value="0" title="Select All Records" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th width="15%">Level</th>
                                        <th width="15%">Class</th>
                                        <th width="10%">Section</th>
                                        <th width="10%" class="center">Total Students</th>
                                        <th width="10%" class="center">Present Students</th>
                                        <th width="20%" class="center">Status</th>
                                        <th width="10%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									$current_academic_year = current_academic_year();
									$sql="select a.id, a.title as section, b.class_name, c.title as class_level from class_section a inner join class b on a.class_id = b.id inner join class_level c on b.class_level_id=c.id inner join subject_teachers d on d.class_section_id=a.id where d.employee_id='".$_SESSION[ "logged_in_teachers" ][ "id" ]."' and is_class_teacher=1 and a.school_id = '".$_SESSION[ "current_school_id" ]."' order by c.sortorder, b.class_name, a.title";
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
                                                <td><?php echo unslash($r["class_level"]); ?></td>
                                                <td><?php echo unslash($r["class_name"]); ?></td>
                                                <td><?php echo unslash($r["section"]); ?></td>
                                                <td class="center">
                                                	<?php
                                                    $count = dofetch( doquery( "select count(1) from student_2_class where class_section_id = '".$r[ "id" ]."' and academic_year_id='".$current_academic_year[ "id" ]."' and status=1", $dblink ) );
													echo $count[ "count(1)" ];
													?>
                                                </td>
                                                <td class="center">
                                                	<?php
                                                    $count = dofetch( doquery( "select count(1) from student_attendance a inner join student_2_class b on a.student_id = b.student_id where class_section_id = '".$r[ "id" ]."' and academic_year_id='".$current_academic_year[ "id" ]."' and status=1 and date='".date_dbconvert( $date )."'", $dblink ) );
													echo $count[ "count(1)" ];
													?>
                                                </td>
                                                <td class="center">
                                                	<?php
                                                    $class_attendance = doquery( "select a.*, b.name from student_daily_attendance a inner join employee b on a.taken_by = b.id where class_section_id = '".$r[ "id" ]."' and date='".date_dbconvert( $date )."'", $dblink );
													if( numrows( $class_attendance ) > 0 ) {
														$class_attendance = dofetch( $class_attendance );
														if( $class_attendance[ "status" ] > 0 ) {
															echo '<i class="fa fa-check" style="color: #0f0"></i> Attendance Taken by '.$class_attendance[ "name" ];
														}
														if( $class_attendance[ "status" ] == 2 ) {
															echo '<br /><i class="fa fa-check" style="color: #0f0"></i> SMS Sent';
														}
														else if( $class_attendance[ "status" ] == 1 ) {
															echo '<br /><i class="fa fa-close" style="color: #f00"></i> SMS not Sent';
														}
													}
													else {
														echo '<i class="fa fa-close" style="color: #f00"></i> Attendance Not Taken';
													}
													?>
                                                </td>
                                                <td class="center">
                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                    	<a class="green" title="Edit Record" href="class_attendance_manage.php?tab=report&id=<?php echo $r['id'];?>&date=<?php echo $date?>">
                                                            <i class="ace-icon fa fa-print bigger-130"></i>
                                                        </a>
                                                        <a class="green" title="Edit Record" href="class_attendance_manage.php?tab=edit&id=<?php echo $r['id'];?>&date=<?php echo $date?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
            
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="class_attendance_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete&date=<?php echo $date?>">
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
                                                                    <a title="Edit Record" href="class_attendance_manage.php?tab=print&id=<?php echo $r['id'];?>&date=<?php echo $date?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-print-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="Edit Record" href="class_attendance_manage.php?tab=edit&id=<?php echo $r['id'];?>&date=<?php echo $date?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="class_attendance_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete&date=<?php echo $date?>" class="tooltip-error" data-rel="tooltip">
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
                    						<td colspan="4" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "class", $sql, $pageNum)?></td>
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