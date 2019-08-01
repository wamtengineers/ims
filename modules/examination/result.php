<?php
if(!defined("APP_START")) die("No Direct Access");
$academic_year = current_academic_year();
$exam = dofetch( doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.id = '".slash( $_GET[ "id" ] )."'", $dblink ) );
if(isset($_GET["class_section_id"])){
	$class_section_id=slash($_GET["class_section_id"]);
	$_SESSION["examination_manage"]["result"]["class_section_id"]=$class_section_id;
}
if(isset($_SESSION["examination_manage"]["result"]["class_section_id"])){
	$class_section_id=$_SESSION["examination_manage"]["result"]["class_section_id"];
}
else{
	$class_section_id = "";
}
if(isset($_GET["subject_id"])){
	$subject_id=slash($_GET["subject_id"]);
	$_SESSION["examination_manage"]["result"]["subject_id"]=$subject_id;
}
if(isset($_SESSION["examination_manage"]["result"]["subject_id"])){
	$subject_id=$_SESSION["examination_manage"]["result"]["subject_id"];
}
else{
	$subject_id = "";
}
if(isset($_GET["group_id"])){
	$group_id=slash($_GET["group_id"]);
	$_SESSION["examination_manage"]["result"]["group_id"]=$group_id;
}
if(isset($_SESSION["examination_manage"]["result"]["group_id"])){
	$group_id=$_SESSION["examination_manage"]["result"]["group_id"];
}
else{
	$group_id = "";
}
if( !empty( $class_section_id ) ) {
	$class_section = dofetch( doquery("select * from class_section where id = '".$class_section_id."'",$dblink));
	$rs = get_result( $exam, $class_section, 0, $subject_id );
	$groups = array();
	$empty = array(
		"id" => "",
		"title" => "No Group"
	);
	foreach( $rs as $k => $r ){
		if( !isset( $groups[$r[ "group" ]] ) ){
			if( !empty( $r[ "group" ] ) ){
				$g = doquery( "select title from `group` where id = '".$r[ "group" ]."'", $dblink );
				if( numrows( $g ) > 0 ){
					$g = dofetch($g);
					$groups[] = array(
						"id" => $r[ "group" ],
						"title" => unslash( $g[ "title" ] )
					);
				}
				else{
					$groups[$r[ "group" ]] = $empty;
				}
			}
			else{
				$groups[$r[ "group" ]] = $empty;
			}
		}
		$r[ "group" ] = empty($r[ "group" ])?"":$r[ "group" ];
		if( $r[ "group" ] != $group_id ){
			unset( $rs[ $k ] );
		}
	}
	ksort( $groups );
}

?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Result Sheets of <?php echo $exam[ "title" ]?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Examination
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
                                        <div class=""><a href="examination_manage.php?tab=print_result&id=<?php echo slash( $_GET[ "id" ] )?>" class="btn btn-sm btn-primary">Print</a></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 align-right search">
                                    	<form action="examination_manage.php" method="get">
                                        	<input type="hidden" name="id" value="<?php echo $exam[ "id" ]?>">
                                            <input type="hidden" name="tab" value="result" />
                                        	
                                            <div class="input-examination">
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
                                                if( !empty( $class_section_id ) ) {
													$class_section = dofetch( doquery( "select * from class_section where id = '".$class_section_id."'", $dblink ) );
													if( isset( $groups ) ) {
														?>
														<select name="group_id">
															<?php
                                                            foreach( $groups as $group ) {
                                                                ?>
                                                               	<option value="<?php echo $group[ "id" ]?>"<?php echo $group["id"]==$group_id?" selected":""?>><?php echo $group[ "title" ]?></option> 
                                                                <?php
                                                            }
															?>
														</select>
														<?php
													}
													?>
													<select name="subject_id" id="subject_id" class="custom_select">
                                                        <option value=""<?php echo ($subject_id=="")? " selected":"";?>>All Subjects</option>
                                                        <?php
                                                            $subjects = array();
															$res=doquery( "select * from subject where class_id = '".$class_section[ "class_id" ]."' order by sortorder", $dblink );
                                                            if(numrows($res)>=0){
                                                                while($rec=dofetch($res)){
																	$subjects[] = $rec;
                                                                ?>
                                                                <option value="<?php echo $rec["id"]?>" <?php echo($subject_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                                <?php
                                                                }
                                                            }	
                                                        ?>
                                                    </select>
													<?php
												}
												?>
                                                
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
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%" class="center">S.No</th>
                                        <th width="20%" class="center">Student</th>
                                        <?php
                                        if( isset( $subjects ) ) {
											foreach( $subjects as $s ){
												?>
												<th width="15%" class="text-right"><?php echo $s["title"]?></th>
												<?php
											}
										}
										?>
                                        <th width="15%" class="text-right">Total Marks</th>
                                        <th width="15%" class="text-right">Obtained Marks</th>
                                        <th width="15%" class="text-right">Percentage</th>
                                        <th width="15%">Grade</th>
                                        <th width="10%" class="center">Position</th>
                                        <th width="10%">Marksheet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									if( !empty( $class_section_id ) ) {
										if(count($rs)>0){
											$sn=1;
											foreach( $rs as $r ){
												$student = dofetch( doquery( "select * from student where id = '".$r[ "id" ]."'", $dblink ) );
												$percent_grade = get_percent_grade( $r[ "obtained" ], $r[ "max" ]);
												?>
												<tr>
													<td class="center"><?php echo $sn;?></td>
													<td><?php echo unslash( $student[ "student_name" ] )?></td>
													 <?php
													 if( isset( $subjects ) ) {
														foreach( $subjects as $s ){
															if( isset( $r[ "subject" ][$s[ "id" ]] ) ) {
																?>
																<td width="15%" class="text-right"><?php echo $r[ "subject" ][$s[ "id" ]][ "total" ][ "obtained" ]?></td>
                                                                <?php
															}
															else{
																?>
																<td>&nbsp;</td>
																<?php
															}
															?>
															<?php
														}
													}
													?>
                                                    <td class="text-right"><?php echo $r[ "max" ]; ?></td>
													<td class="text-right"><?php echo $r[ "obtained" ]; ?></td>
                                                    <td class="text-right"><?php echo $percent_grade[ "percent" ]; ?></td>
                                                    <td><?php echo $percent_grade[ "grade" ]; ?></td>
                                                    <td><?php echo $percent_grade[ "grade" ]!='F'?($r[ "rank" ]<=3?ordinal( $r[ "rank" ] ):'Pass'):'Fail'; ?></td>
                                                    <td class="text-center"><a href="student_manage.php?tab=marksheet&id=<?php echo $student["id"]?>&academic_year_id=<?php echo $academic_year["id"]?>&examination_type_id=<?php echo slash($_GET[ "id" ])?>"><i class="fa fa-print" aria-hidden="true"></i></a></td>
												</tr>  
												<?php 
												$sn++;
											}
										}
										else{	
											?>
											<tr>
												<td colspan="9"  class="no-record">No Result Found</td>
											</tr>
											<?php
										}
									}else{	
										?>
										<tr>
											<td colspan="9"  class="no-record">Select Class from dropdown</td>
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