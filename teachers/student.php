<?php 
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
if( isset( $_GET[ "id" ] ) && isset( $_GET[ "subject_id" ] ) ) {
	$res=doquery("select a.subject_id, b.title as subject, a.class_section_id, c.title as section, d.class_name as class from subject_teachers a left join subject b on a.subject_id = b.id left join class_section c on a.class_section_id = c.id left join class d on c.class_id = d.id where employee_id = '".$_SESSION["logged_in_teachers"][ "id" ]."' and a.id = '".slash( $_GET[ "subject_id" ] )."'",$dblink);
	if(numrows($res)>0){
		$details = dofetch($res);	
	}
	else{
		header( "Location: index.php" );
		die;
	}
	$current_academic_year = current_academic_year();
	$current_academic_year_id = $current_academic_year[ "id" ];
	$res = doquery( "select * from examination where id = '".slash( $_GET[ "id" ] )."' and academic_year_id = '".$current_academic_year_id."'", $dblink );
	if(numrows($res)>0){
		$exam = dofetch($res);
		$marks = get_max_marks( $exam[ "id" ], $details[ "subject_id" ] );
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
$academic_year = current_academic_year();
$year_id=$academic_year["id"];
$sql = "select b.*, c.marks, c.id as result_id, c.status as result_status, c.marks as marks, d.name as reviewer from student_2_class a left join student b on a.student_id = b.id left join examination_marks_students c on a.student_id = c.student_id and subject_id = '".$details[ "subject_id" ]."' and exam_id = '".$exam[ "id" ]."' left join admin d on c.reviewed_by = d.id where class_section_id='".$details[ "class_section_id" ]."' and academic_year_id='".$year_id."' and b.status = 1 order by b.student_name, b.father_name";
$rs=doquery( $sql, $dblink );
if( isset( $_POST[ "add_exam_marks" ] ) || isset( $_POST[ "submit_exam_marks" ] ) ) {
	if( isset( $_POST[ "add_exam_marks" ] ) ) {
		$status = 0;
	}
	else{
		$status = 2;
	}
	extract( $_POST );
	$rs2 = doquery( $sql, $dblink );
	if( numrows( $rs2 ) > 0 ) {
		while( $r= dofetch( $rs2 ) ) {
			if( isset( $student_marks[ $r[ "id" ] ] ) && $student_marks[ $r[ "id" ] ] <= $marks[ "max" ] && $student_marks[ $r[ "id" ] ] != "" ) {
				if( empty( $r[ "result_id" ] ) ) {
					doquery( "insert into examination_marks_students(exam_id, student_id, subject_id, marks, checked_by, status) values( '".$exam[ "id" ]."', '".$r[ "id" ]."', '".$details[ "subject_id" ]."', '".slash( $student_marks[ $r[ "id" ] ] )."', '".$_SESSION[ "logged_in_teachers" ][ "id" ]."', '".$status."' )", $dblink );
				}
				else if( $r[ "result_status" ] == 0 || $r[ "result_status" ] == 2 ){
					doquery( "update examination_marks_students set marks = '".slash( $student_marks[ $r[ "id" ] ] )."', status = '".$status."' where id = '".$r[ "result_id" ]."'", $dblink );	
				}
			}
		}
	}
	header( "Location: student.php?id=".slash( $_GET[ "id" ] )."&subject_id=".slash( $_GET[ "subject_id" ] )."&msg=".url_encode( 'Successfully Saved' ) );
	die();
}
?>
<?php include("header.php");?>
<div class="page-content">
    <div class="page-header">
    	<div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h1>
                    <?php echo get_field($exam["examination_type_id"], "examination_type", "title")?> Result of <?php echo unslash( $details[ "subject" ] )?>
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        Class: <?php echo unslash( $details[ "class" ] )." - ".unslash( $details[ "section" ] )?>
                    </small>
                </h1>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 align-right">
                <a href="results.php?id=<?php echo slash( $_GET[ "subject_id"])?>" class="btn btn-sm btn-primary">Back to list</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                             <form class="form-horizontal" role="form" action="<?php echo "student.php?id=".slash( $_GET[ "id" ] )."&subject_id=".slash( $_GET[ "subject_id" ] );?>" method="post" enctype="multipart/form-data" name="frmAdd">
                                <table id="dynamic-table" class="table list table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">S.No</th>
                                            <th width="35%">Student</th>
                                            <th width="20%">Max.Marks</th>
                                            <th width="20%">Min.Marks</th>
                                            <th width="20%">Obt.Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
										if(numrows($rs)>0){
                                            $sn=1;
                                            while($r=dofetch($rs)){           
                                                ?>
                                                <tr class="<?php echo !empty( $r[ "result_id" ] ) && $r[ "result_status" ]==0?"info":""; echo !empty( $r[ "result_id" ] ) && $r[ "result_status" ]==1?"success":""; echo !empty( $r[ "result_id" ] ) && $r[ "result_status" ]==2?"warning":""; echo !empty( $r[ "result_id" ] ) && $r[ "result_status" ]==3?"danger":""?>">
                                                    <td><?php echo $sn;?></td>
                                                    <td><?php echo unslash( $r[ "student_name" ] )." / ".unslash( $r[ "father_name" ] )?></td>
                                                    <td><?php echo $marks[ "max" ]?></td>
                                                    <td><?php echo $marks[ "min" ]?></td>
                                                    <td><input style="width: 100%;" type="text" name="student_marks[<?php echo $r[ "id" ]?>]" value="<?php echo $r[ "marks" ]?>"<?php echo 0 && !empty( $r[ "result_id" ] ) && ( $r[ "result_status" ]==1 || $r[ "result_status" ]==2 )?' disabled':''?>/><?php echo !empty( $r[ "reviewer" ] )?"<br>Reviewed By: ".unslash( $r[ "reviewer" ] ):""?></td>
                                                </tr>  
                                                <?php 
                                                $sn++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="clearfix form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button class="btn btn-info" type="submit" name="add_exam_marks" title="Update Record">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Update
                                        </button>
                                        <button class="btn btn-success" type="submit" name="submit_exam_marks" title="Submit Record">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Submit for review
                                        </button>
                                    </div>
                                </div>
                            </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>
<?php include("footer.php");