<?php
if(!defined("APP_START")) die("No Direct Access");

$extra='';
$order="asc";
$group_id="";
$is_opt="";
if(isset($_GET["academic_year_id"])){
	$academic_year_id=slash($_GET["academic_year_id"]);
	$_SESSION["examination_manage_result"]["list"]["academic_year_id"]=$academic_year_id;
}
if(isset($_SESSION["examination_manage_result"]["list"]["academic_year_id"])){
	$academic_year_id=$_SESSION["examination_manage_result"]["list"]["academic_year_id"];
}
else{
	$academic_year = current_academic_year();
	$academic_year_id = $academic_year[ "id" ];
}

if(isset($_GET["examination_type_id"])){
	$examination_type_id=slash($_GET["examination_type_id"]);
	$_SESSION["examination_manage_result"]["list"]["examination_type_id"]=$examination_type_id;
}
if(isset($_SESSION["examination_manage_result"]["list"]["examination_type_id"])){
	$examination_type_id=$_SESSION["examination_manage_result"]["list"]["examination_type_id"];
}
else{
	$examination_type_id = "";
}

if(isset($_GET["class_section_id"])){
	$class_section_id=slash($_GET["class_section_id"]);
	$_SESSION["examination_manage_result"]["list"]["class_section_id"]=$class_section_id;
	$subject_id="";
}
else{
	$class_section_id = "";
	$subject_id = "";
}
if(isset($_SESSION["examination_manage_result"]["list"]["class_section_id"])){
	$class_section_id=$_SESSION["examination_manage_result"]["list"]["class_section_id"];
}
else{
	$class_section_id = "";
}

if(isset($_GET["subject_id"])){
    $subject_id=slash($_GET["subject_id"]);
    $_SESSION["examination_manage_result"]["result"]["subject_id"]=$subject_id;
}
if(isset($_SESSION["examination_manage_result"]["result"]["subject_id"])){
    $subject_id=$_SESSION["examination_manage_result"]["result"]["subject_id"];
}
else{
    $subject_id = "";
}
if(!empty($subject_id)){
	$extra.=" and a.subject_id=".slash( $subject_id );
	$is_optionals = doquery("select group_concat(student_2_class_id) as class_id from student_2_class_2_subject a where subject_id='".slash($subject_id)."'", $dblink);
	
	if( numrows($is_optionals)>0 ){
		$is_optional = dofetch($is_optionals);
		$is_opt = (!empty($is_optional["class_id"])?" and student_id IN (".$is_optional["class_id"].")":"");
	}
	else{
		$subject = doquery("select a.*, group_concat(group_id) as groups from subject a left join subject_2_group b on a.id = b.subject_id where id ='".slash( $subject_id )."'",$dblink);
		$subjects = dofetch($subject);
		
		$group_id = (!empty($subjects["groups"])?" and (group_id in (".$subjects["groups"].")":" ")." || group_id=0 )";
	}
	
}
else{
	$extra="";
}

if(isset($_GET["order"])){
	$_SESSION["examination_manage_result"]["list"]["order"]=slash($_GET["order"]);
}
if(isset($_SESSION["examination_manage_result"]["list"]["order"])){
	$order=$_SESSION["examination_manage_result"]["list"]["order"];
}
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Examination Result
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All Examination
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
                                        
                                    <div class="col-md-12 col-sm-12 col-xs-12 align-right search">
                                    	<?php
//                                            $sql=doquery("select * from examination_marks_students where subject_id='".$subject_id."'",$dblink);
//                                            if(numrows($sql)>0){
                                        if( !empty($academic_year_id) && !empty($examination_type_id) && !empty($class_section_id) && !empty($subject_id) ) {?>
                                    	<div>
                                    	<div class=" col-md-1 col-sm-2 col-xs-12" style="margin-right: 18px;">
                                      	<a href="examination_result_manage.php?tab=delete&examination_type_id=<?php echo $examination_type_id; ?>&academic_year_id=<?php echo $academic_year_id; ?>&class_section_id=<?php echo $class_section_id; ?>&subject_id=<?php echo
										 $subject_id; ?>" class="btn btn-sm btn-primary">Delete</a>
                                    	</div>
                                      	
                                       	<div class=" col-md-1 col-sm-2 col-xs-12" style="margin-right: 18px;">
                                       		<a href="examination_result_manage.php?tab=status&examination_type_id=<?php echo $examination_type_id; ?>&academic_year_id=<?php echo $academic_year_id; ?>&class_section_id=<?php echo $class_section_id; ?>&subject_id=<?php echo
										 $subject_id; ?>&status=1" class="btn btn-sm btn-success">All Approved</a>
                                       	</div>
                                       	
                                       	<div class=" col-md-1 col-sm-2 col-xs-12">
                                       		<a href="examination_result_manage.php?tab=status&examination_type_id=<?php echo $examination_type_id; ?>&academic_year_id=<?php echo $academic_year_id; ?>&class_section_id=<?php echo $class_section_id; ?>&subject_id=<?php echo
										 $subject_id; ?>&status=0" class="btn btn-sm btn-danger">All Cancel</a>
                                       	</div>
                                       	
                                       	<div class=" col-md-1 col-sm-2 col-xs-12">
                                       		<a href="examination_result_manage.php?tab=status&examination_type_id=<?php echo $examination_type_id; ?>&academic_year_id=<?php echo $academic_year_id; ?>&class_section_id=<?php echo $class_section_id; ?>&subject_id=<?php echo
										 $subject_id; ?>&status=2" class="btn btn-sm btn-primary">All Review</a>
                                       	</div>
                                       	</div>
                                       	<?php } ?>
                                       
                                      	   	
                                       	   	<form action="" method="get">     
                                            	<div class="input-examination">
                                                   <select name="academic_year_id" id="academic_year_id" class="custom_select">
                                                    <option value=""<?php echo ($academic_year_id=="")? " selected":"";?>>Academic Year</option>
                                                    <?php
                                                        $res=doquery("select * from academic_year where status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by start_date",$dblink);
                                                        if(numrows($res)>=0){
                                                            while($rec=dofetch($res)){
                                                            ?>
                                                            <option value="<?php echo $rec["id"]?>" <?php echo($academic_year_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                            <?php
                                                            }
                                                        }	
                                                    ?>
                                                </select>
                                                
                                                <select name="examination_type_id" id="examination_type_id" class="custom_select">
                                                    <option value=""<?php echo ($examination_type_id=="")? " selected":""; ?>>Examination Type</option>
                                                    <?php
                                                        $res=doquery("select * from examination_type where school_id = '".$_SESSION["current_school_id"]."' and status = 1",$dblink);
                                                        if(numrows($res)>=0){
                                                            while($rec=dofetch($res)){
                                                            ?>
                                                            <option value="<?php echo $rec["id"]?>" <?php echo($examination_type_id==$rec["id"])?" selected":""; ?>><?php echo unslash($rec["title"])?></option>
                                                            <?php
                                                            }
                                                        }	
                                                    ?>
                                                </select>
                                                
                                                <select name="class_section_id" id="class_section_id" class="custom_select">
                                                    <option value=""<?php echo ($class_section_id=="")? " selected":"";?>>Select Class</option>
                                                    <?php
                                                        $res=doquery("select b.id, a.class_name, b.title as section from class a left join class_section b on a.id = b.class_id where a.status=1 and b.status = 1 and b.school_id = '".$_SESSION["current_school_id"]."' order by a.sortorder, b.title",$dblink);
                                                        if(numrows($res)>=0){
                                                            while($rec=dofetch($res)){
                                                            ?>
                                                            <option value="<?php echo $rec["id"]?>" <?php echo($class_section_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["class_name"]." - ".$rec["section"])?></option>
                                                            <?php
                                                            }
                                                        }	
                                                    ?>
                                                </select>
                                                
                                                <?php
													if(isset($class_section_id)){
														$class_section = dofetch( doquery( "select * from class_section where id = '".$class_section_id."'", $dblink ) );
													?>
													<select name="subject_id" id="subject_id" class="custom_select">
                                                        <option value=""<?php echo ($subject_id=="")? " selected":"";?>>All Subjects</option>
                                                        <?php
                                                            $res=doquery( "select * from subject where class_id = '".$class_section[ "class_id" ]."' order by sortorder", $dblink );
                                                            if(numrows($res)>=0){
                                                                while($rec=dofetch($res)){
                                                                ?>
                                                                <option value="<?php echo $rec["id"]?>" <?php echo($subject_id==$rec["id"])?" selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                                <?php
                                                                }
                                                            }	
														}
                                                        ?>
                                                    </select>
											
                                                <span class="input-examination-btn">
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
                            <form action="examination_result_manage.php?tab=edit&examination_type_id=<?php echo $examination_type_id; ?>&academic_year_id=<?php echo $academic_year_id; ?>&class_section_id=<?php echo $class_section_id; ?>&subject_id=<?php echo
										 $subject_id; ?>" method="post">
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%" class="center">S.No</th>
                                        <th width="20%" class="center"><a href="examination_result_manage.php?tab=list&order=<?php echo $order=="asc"?"desc":"asc"; ?>" class="sorting">
                                        
                                        Student
                                       	
                                       	<span class="sort-icon">
                                       		<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                                       	</span>
                                        </a></th>
                                        <th width="10%">Total Marks</th>
                                        <th width="10%">Obtained Marks</th>
                                        <th width="10%">Percentage</th>
                                        <th width="10%">Grade</th>
                                        <th width="5%" class="center">Status</th>
                                        <th width="5%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                <?php if(empty($academic_year_id)){ ?>
                        	        <tr>
										<td colspan="8" class="no-record">Please Select Academic Year</td>
									</tr>
                                <?php } elseif( empty($examination_type_id ) ){ ?>
									<tr>
										<td colspan="8" class="no-record">Please Select Examination Type</td>
									</tr>
								<?php } elseif( empty($class_section_id) ){ ?>
									<tr>
										<td colspan="8" class="no-record">Please Select Class Section</td>
									</tr>
								<?php } elseif( empty($subject_id) ){ ?>
									<tr>
										<td colspan="8" class="no-record">Please Select Class Subject</td>
									</tr>
								<?php }else{
									
									$exam = doquery( "select * from examination where examination_type_id='".$examination_type_id."'", $dblink );
									if( numrows( $exam )>0 ) {
										$exam = dofetch($exam);
									}
	
									$students = doquery("select a.* from student a left join student_2_class b on a.id = b.student_id and academic_year_id = '".slash( $academic_year_id )."' and class_section_id = '".slash( $class_section_id )."' $is_opt $group_id  where a.status = 1 and b.status = 1 order by student_name $order, father_name", $dblink);

									if(numrows($students)>0){
										$sn=1;
										while($r=dofetch($students)){
//																																															
											$result = doquery( "select * from examination_marks_students a left join examination_marks b on a.exam_id = b.exam_id where a.exam_id = ".$exam[ "id" ]." and a.student_id = ".$r[ "id" ]." $extra", $dblink );

                                            if( numrows( $result )>0 ){
											
												$result = dofetch($result);
												$percent_grade = get_percent_grade( $result[ "marks" ], $result["max"] );
											    
                                            }
											else{ ?>
											
											<tr>
												<td colspan="8" class="no-record">Please Enter Marks</td>
											</tr>
											<?php
												die;								
											}
											?>
                                            <tr>
                                                <td class="center"><?php echo $sn;?></td>
                                                <td class="center"><?php echo unslash( $r["student_name"] )." ".($r[ "gender" ]=='male'?'S':'D')."/o ".unslash( $r["father_name"] ); ?></td>
                                            	<td><?php echo ($result["max"]>0)?(int)$result["max"]:'//'; ?></td>
                                                <td>
                                                <input style="width: 100%;" name="marks[<?php echo $r["id"]; ?>]" value="<?php echo ($result[ "marks" ]>0)?$result[ "marks" ]:''; ?>"/>
<!--                                              <td>  <?php //echo ($result[ "marks" ]>0)?(int)$result[ "marks" ]:'//'; ?></td>-->
                                                <td><?php echo (isset($percent_grade[ "percent" ]) && ($percent_grade[ "percent" ])>0)?$percent_grade[ "percent" ]:'-'; ?></td>
                                                <td><?php echo (isset($percent_grade[ "percent" ]) && ($percent_grade[ "percent" ])>0)?$percent_grade[ "grade" ]:'-'; ?></td>
                                                <td><?php echo $result["status"]; ?></td> 
                                                <td>
                                                <?php
												if( $result["status"]!='//'){
													
												if($result["status"]==0) { ?>
                                                	<a href="examination_result_manage.php?tab=status&id=<?php echo $result['id'];?>&s=<?php echo 1; ?>"><i class="fa fa-check" style="color: #438EB9; font-size: 17px"></i></a>
                                                	<a href="examination_result_manage.php?tab=status&id=<?php echo $result['id'];?>&s=<?php echo 2; ?>"><i class="fa fa-search" style="color: #438EB9; font-size: 17px"></i></a>
                                                <?php } elseif($result["status"]==1) { ?>
                                                	<a href="examination_result_manage.php?tab=status&id=<?php echo $result['id'];?>&s=<?php echo 2; ?>"><i class="fa fa-search " style="color: #438EB9; font-size: 17px"></i></a>
                                                	<a href="examination_result_manage.php?tab=status&id=<?php echo $result['id'];?>&s=<?php echo 0; ?>"><i class="fa fa-close" style="color: #F3060A; font-size: 17px"></i></a>
                                                <?php } elseif($result["status"]==2) { ?>
                                                	<a href="examination_result_manage.php?tab=status&id=<?php echo $result['id'];?>&s=<?php echo 0; ?>"><i class="fa fa-close" style="color: #F3060A; font-size: 17px"></i></a>
                                                	<a href="examination_result_manage.php?tab=status&id=<?php echo $result['id'];?>&s=<?php echo 1; ?>"><i class="fa fa-check" style="color: #438EB9; font-size: 17px"></i></a>
                                                <?php }} ?>
                                                </td>
                                                <?php 
											$sn++;
											$percent_grade = "";
										}
										}
									}
									?>
                                </tbody>
                            </table>
                                <?php
//                                if(!empty($academic_year_id) && !empty($examination_type_id) && !empty($class_section_id) && !empty($subject_id)){
                                   ?>
                                    <input type="submit" name="save_marks" value="Save Marks" class="btn btn-sm btn-primary"/>
<!--                                --><?php //} ?>

                            </form>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>