<?php 
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
if( isset( $_GET[ "id" ] ) ) {
	$rs = doquery( "select a.class_section_id as class_sec_id, b.title as subject, c.title as section, d.class_name as class from subject_teachers a left join subject b on a.subject_id = b.id left join class_section c on a.class_section_id = c.id left join class d on c.class_id = d.id where employee_id = '".$_SESSION["logged_in_teachers"][ "id" ]."' and a.id = '".slash( $_GET[ "id" ] )."'", $dblink );
	if( numrows( $rs ) > 0 ) {
		$details = dofetch( $rs );
	}
	else{
		header( "Location: index.php" );
		die;
	}
}
else{
	header( "Location: index.php" );
	die;
}
$current_academic_year = current_academic_year();
$current_academic_year_id = $current_academic_year[ "id" ];
?>
<?php include("header.php");?>
<div class="page-content">
    <div class="page-header">
    	<div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h1>
                    Examination Result of <?php echo unslash( $details[ "subject" ] )?>
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        Class: <?php echo unslash( $details[ "class" ] )." - ".unslash( $details[ "section" ] )?>
                    </small>
                </h1>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 align-right">
                <a href="subject.php?id=<?php echo $details[ "class_sec_id"]?>" class="btn btn-sm btn-primary">Back to list</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%" class="center">S.No</th>
                                        <th width="10%" class="center">ID</th>
                                        <th width="20%">Title</th>
                                        <th width="20%">Start Date</th>
                                        <th width="20%">Result Date</th>
                                        <th width="10%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$rs=doquery("select * from `examination` where status = 1 and academic_year_id = '".$current_academic_year_id."' order by start_date", $dblink);

									if(numrows($rs)>0){
										$sn=1;
										while($r=dofetch($rs)){             
											?>
                                            <tr>
                                                <td class="center"><?php echo $sn;?></td>
                                                <td class="center"><?php echo $r["id"];?></td>
                                                <td><?php echo get_field($r["examination_type_id"], "examination_type", "title")?></td>
                                                <td><?php echo date_convert($r["start_date"]); ?></td>
                                                <td><?php echo date_convert($r["result_date"]); ?></td>
                                                <td class="center">
                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                        <a class="green" title="Edit Record" href="student.php?id=<?php echo $r[ "id" ]?>&subject_id=<?php echo $_GET[ "id" ]?>">
                                                            <i class="ace-icon fa fa-users bigger-130"></i>
                                                        </a>
                                                    </div>
                                                    <div class="hidden-md hidden-lg">
                                                        <div class="inline pos-rel">
                                                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                                <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                <li>
                                                                    <a title="Edit Record" href="student.php?id=<?php echo $r[ "id" ]?>&subject_id=<?php echo $_GET[ "id" ]?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-users bigger-120"></i>
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
<?php include("footer.php");