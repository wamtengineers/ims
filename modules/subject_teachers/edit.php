<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?php echo get_field( $class_id, "class", "class_name" )." - ".$title;?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Subject Teachers
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
                                    <div class="dataTables_length col-md-6 col-sm-6 col-xs-12" id="dynamic-table_length">
                                        <div class=""><a href="subject_teachers_manage.php" class="btn btn-sm btn-primary">Back to list</a></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <?php
                            $exams = array();
							$rs = doquery( "select * from examination where status = 1 and academic_year_id = '".$academic_year[ "id" ]."' and school_id = '".$_SESSION["current_school_id"]."' order by start_date", $dblink );
							if( numrows( $rs ) > 0 ) {
								while( $r = dofetch( $rs ) ) {
									$exams[] = $r;
								}
							}
							?>
                             <form class="form-horizontal" role="form" action="subject_teachers_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            				 <input type="hidden" name="id" value="<?php echo $id;?>">
                                <table id="dynamic-table" class="table list table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%" rowspan="2">S.No</th>
                                            <th width="15%" rowspan="2">Subject</th>
                                            <th width="10%" rowspan="2">Teacher</th>
                                            <th width="10%" rowspan="2">Class Teacher</th>
                                            <?php
                                            foreach( $exams as $exam ){
												?>
												<th width="10%" colspan="2"><?php echo get_field($exam["examination_type_id"], "examination_type", "title")?></th>
												<?php
											}
											?>
                                        </tr>
                                        <tr>
                                        	<?php
                                            foreach( $exams as $exam ){
												?>
												<th width="5%">Max</th>
                                                <th width="5%">Min</th>
												<?php
											}
											?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $rs=doquery( "select a.*, b.employee_id, b.is_class_teacher, b.subject_id, c.name from subject a left join subject_teachers b on a.id = b.subject_id and class_section_id = '".$id."' left join employee c on b.employee_id = c.id where class_id = '".$class_id."' order by sortorder", $dblink );
                                        if(numrows($rs)>0){
                                            $sn=1;
                                            while($r=dofetch($rs)){             
                                                ?>
                                                <tr>
                                                    <td><?php echo $sn;?></td>
                                                    <td><?php echo unslash( $r[ "title" ] )?></td>
                                                    <td>
                                                        <select name="employee_id[<?php echo $r[ "id" ]?>]" title="Choose Option">
                                                            <option value="0">Select Teachers</option>
                                                            <?php
                                                            $res=doquery("Select * from employee where department_id = '".get_config("department_id")."' and status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by id",$dblink);
                                                            if(numrows($res)>0){
                                                                while($rec=dofetch($res)){
                                                                ?>
                                                                <option value="<?php echo $rec["id"]?>"<?php echo($r[ "employee_id" ]==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"]); ?></option>
                                                                <?php			
                                                                }			
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td align="center"><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="class_teacher" value="<?php echo $r[ "id" ]?>"<?php echo $r[ "is_class_teacher" ]==1?' checked':''?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
                                                    <?php
													foreach( $exams as $exam ){
														$marks = get_max_marks( $exam[ "id" ], $r[ "subject_id" ] );
														?>
														<td width="5%"><input style="width: 100%;" type="text" value="<?php echo $marks[ "max" ] ?>" name="marks[<?php echo $exam[ "id" ]?>][<?php echo $r[ "subject_id" ]?>][max]" /></td>
                                                        <td width="5%"><input style="width: 100%;" type="text" value="<?php echo $marks[ "min" ] ?>" name="marks[<?php echo $exam[ "id" ]?>][<?php echo $r[ "id" ]?>][min]"/></td>
														<?php
													}
													?>
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
                                        <button class="btn btn-info" type="submit" name="subject_teachers_edit" title="Update Record">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Update
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