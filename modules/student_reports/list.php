<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
if( isset($_GET["start_date"]) ){
	$_SESSION["student"]["reports"]["start_date"] = $_GET["start_date"];
}
if(isset($_SESSION["student"]["reports"]["start_date"]) && !empty($_SESSION["student"]["reports"]["start_date"])){
	$start_date = $_SESSION["student"]["reports"]["start_date"];
}
else{
	$start_date = date("d/m/Y");
}
if( !empty($start_date) ){
	$extra.=" and checked_in>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($start_date)))."'";
}
if( isset($_GET["end_date"]) ){
	$_SESSION["student"]["reports"]["end_date"] = $_GET["end_date"];
}
if(isset($_SESSION["student"]["reports"]["end_date"]) && !empty($_SESSION["student"]["reports"]["end_date"])){
	$end_date = $_SESSION["student"]["reports"]["end_date"];
}
else{
	$end_date = date("d/m/Y");
}
if( !empty($end_date) ){
	$extra.=" and checked_out<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($end_date))+3600*24)."'";
}
if(isset($_GET["student_id"])){
	$student_id=slash($_GET["student_id"]);
	$_SESSION["student_reports_list_student_id"]=$student_id;
}
if(isset($_SESSION["student_reports_list_student_id"])){
	$student_id=$_SESSION["student_reports_list_student_id"];
}
else
	$student_id="";
if(!empty($student_id))
	$extra.=" and student_id like '%".$student_id."%'";
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Student Reports
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All Students Reports 
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
                                    <div class="dataTables_length col-md-2 col-sm-4 col-xs-12" id="dynamic-table_length">
                                        <div class=""><h4 class="blue">Student Reports</h4></div>
                                    </div>
                                    <div class="col-md-10 col-sm-8 col-xs-12 align-right search">
                                        <form action="" method="get">
                                        	<div class="date-from form-group">
                                                <input type="text" title="Enter Date" value="<?php echo $start_date;?>" name="start_date" id="start_date" class="datepicker" />
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <div class="date-from form-group">
                                                <input type="text" title="Enter Date" value="<?php echo $end_date;?>" name="end_date" id="end_date" class="datepicker" />
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <select name="student_id" id="student_id" class="custom_select">
                                                <option value=""<?php echo ($student_id=="")? " selected":"";?>>All Students</option>
                                                <?php
                                                    $res=doquery("select * from student where school_id = '".$_SESSION["current_school_id"]."' order by student_name",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                		?>
                                                		<option value="<?php echo $rec["id"]?>" <?php echo($student_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["student_name"])?></option>
                                                	<?php
                                                    	}
                                                    }	
                                                ?>
                                            </select>
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
                                        <th width="30%">Student Name</th>
                                        <th width="25%">Checked In</th>
                                        <th width="25%">Checked Out</th>
                                        <th width="15%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql="select * from student_attendance where 1 $extra order by checked_in";
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
                                                <td><?php if($r["student_id"]==0) echo "Default"; else echo get_field($r["student_id"], "student","student_name");?></td>
                                                <td><?php echo unslash($r["checked_in"]); ?></td>
                                                <td><?php echo unslash($r["checked_out"]); ?></td>
                                                <td class="center">
                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="student_reports_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
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
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="" class="tooltip-error" data-rel="tooltip">
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
                                                </select>
                                                <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
                                            </td>
                                            <td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "student_attendance", $sql, $pageNum)?></td>
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