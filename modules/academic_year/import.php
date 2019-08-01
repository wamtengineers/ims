<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Import Students from Other Campus
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Academic Year
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="academic_year_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <?php
			$id = slash( $_REQUEST[ "id" ] );
			$current_year = dofetch( doquery( "select * from academic_year where id  = '".$id."' and school_id = '".$_SESSION["current_school_id"]."'", $dblink ) );
			$last_year = doquery( "select * from academic_year where start_date < '".$current_year[ "start_date" ]."' order by start_date desc limit 0,1", $dblink );
			?>
            <form class="form-horizontal" role="form" action="academic_year_manage.php?tab=import" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row">
                        <label class="col-sm-3 control-label no-padding-right" for="title">Title <span class="red">*</span> </label>
                        <div class="col-sm-9">
                            <select name="import_school_id">
                            	<option value="">Select School</option>
                                <?php
								$school=doquery("Select * from school where status = 1 order by sortorder",$dblink);
								if( numrows( $school ) > 0 ){
									while( $sch = dofetch( $school ) ){
										if( $_SESSION[ "current_school_id" ] != $sch["id"] ){
										?>
										<option value="<?php echo $sch[ "id" ]?>"<?php echo  $sch[ "id" ]==$import_school_id?' selected':''?>><?php echo unslash( $sch[ "title" ] );?></option>
										<?php
										}
									}
								}
								?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php
                if( isset( $student_lists ) ) {
					$first_class = dofetch(doquery( "select * from class where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by sortorder", $dblink ));
					$rs = doquery( "select * from class_section where status = 1 and class_id = '".$first_class[ "id" ]."'", $dblink );
					if( numrows( $rs ) > 0 ) {
						while( $r = dofetch( $rs ) ) {
							$sections[ unslash( $r[ "title" ] ) ] = $r[ "id" ];
						}
					}
					foreach( $student_lists as $student ) {
						echo $student->class."-".$student->section." "."SID: ".$student->id." - ".$student->student_name." ".($student->gender =="male"?"S/o":"D/o")." ".$student->father_name;
						if( isset( $_POST[ "confirm_import" ] ) ) {
							//Inset Query Here
							if( isset( $sections[ $student->section ] ) ) {
								$class_section_id = $sections[ $student->section ];
							}
							else{
								$class_section_id = $sections[0] ;
							}
							$check = doquery( "select a.student_id from student_meta a left join student_meta b on a.student_id = b.id and b.meta_key = 'external_school_id' and b.meta_value = '".$import_school_id."' where a.meta_key = 'external_sid' and a.meta_value='".$student->id."'", $dblink );
							if( numrows( $check ) == 0 ) {
								//Student Insert Query here
								doquery("insert into student (school_id, student_name, father_name, surname, birth_date, address, addmission_date, gender, created_at) values ('".$_SESSION["current_school_id"]."', '".slash($student->student_name)."', '".slash($student->father_name)."', '".slash($student->surname)."', '".date_dbconvert($student->birth_date)."', '".slash($student->address)."', '".date_dbconvert($student->addmission_date)."', '".slash($student->gender)."', NOW())", $dblink);
								$student_id = inserted_id();
								doquery( "insert into student_2_class(student_id, academic_year_id, class_section_id, status) values('".$student_id."', '".$id."', '".$class_section_id."', '1' )", $dblink );
								echo " has been imported.";
								foreach( $student_meta_fields as $field ){
									if( isset( $student->$field ) ){
										set_student_meta($student_id, $field, $student->$field);
									}
								}
								set_student_meta( $student_id, 'external_sid', $student->id );
								set_student_meta( $student_id, 'external_school_id', $import_school_id );
							}
							else{
								echo ' already exists.';
							}
						}
						echo "<br />";
					}
					?>
					<div class="clearfix form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <label><input type="checkbox" name="confirm_import" /> Confirm?</label>
                            </div>
                        </div>
                    </div>
					<?php
				}
				?>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="academic_year_import_do" title="Update Record">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Import
                            </button>
                        </div>
                    </div>
                </div>
           	</form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>