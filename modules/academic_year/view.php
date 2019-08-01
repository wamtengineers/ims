<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Student Bulk Update
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
                                        <div class=""><a href="academic_year_manage.php?tab=list" class="btn btn-sm btn-primary">Back to List</a>&nbsp;<!--<a href="academic_year_manage?tab=print" target="_blank" class="btn btn-sm btn-primary">Print</a>--></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 align-right search">
                                    	<form action="" method="get">
                                        	<input type="hidden" name="tab" value="view" />
                							<input type="hidden" name="id" value="<?php echo $academic_year_id?>" />
                                            <style>
                                            .chosen-container{ max-width: 200px !important; text-align:left;}
                                            </style>
                                            <select name="fields_array[]" multiple="multiple" class="custom_select select_multiple">
                                            	<?php
                                                foreach( $student_meta_fields as $field ) {
													?>
													<option<?php echo in_array( $field, $fields ) ? " selected" : ""?> value="<?php echo $field?>"><?php echo ucwords(str_replace( "_", " ", $field))?></option>
													<?php
												}
												$fees = doquery( "select * from fees where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by sortorder", $dblink );
												if( numrows( $fees ) > 0 ) {
													while( $fee = dofetch( $fees ) ) {
														?>
                                                        <option<?php echo in_array( "fees_".$fee[ "id" ], $fields ) ? " selected" : ""?> value="<?php echo "fees_".$fee[ "id" ]?>"><?php echo unslash( $fee[ "title" ] )?></option>
                                                        <?php
													}
												}
												?>
                                                <option value="optional_subjects">Optional Subjects</option>
                                                <option value="group">Group</option>
                                            </select>
                                            <select name="class_section_id" id="class_section_id" class="custom_select">
                                                <option value="">Select Class Section</option>
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
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-check"></i>
                                                </span>
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
                            <form method="post" enctype="multipart/form-data">
                                <table id="dynamic-table" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="3%" class="center">S.No</th>
                                            <th width="17%">Student Name</th>
                                            <?php
											$fees = array();
                                            foreach( $fields as $field ) {
												if( substr( $field, 0, 5 ) === 'fees_' ) {
													$fee_id = str_replace( "fees_", "", $field );
													$fees[ $field ] = dofetch( doquery( "select * from fees where id = '".$fee_id."'", $dblink ));
													$title = unslash( $fees[ $field ][ "title" ] );
												}
												else {
													$title = ucwords(str_replace( "_", " ", $field));
												}
												?>
												<th width="<?php echo 80/count($fields)?>%"><?php echo $title?></th>
												<?php
											}
											?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sn=1;
                                        ?>
                                        <?php
                                        $rs=doquery($sql, $dblink);
                                        if(numrows($rs)>0){
                                            while($r=dofetch($rs)){          
                                                ?>
                                                <tr>
                                                    <td align="center"><?php echo $sn;?></td>
                                                    <td><?php echo unslash($r["student_name"]); echo " - ".unslash($r["father_name"]); ?></td>
                                                    <?php
													foreach( $fields as $field ) {
														if( substr( $field, 0, 5 ) === 'fees_' ) {
															$field = "fees_".$fees[ $field ][ "id" ].($fees[ $field ][ "has_discount" ]?"_approved":"");
															$field_value = get_student_meta( $r[ "id" ], $field );
														}
														else if( $field == 'balance' && 0 ) {
															$balance = 0;
															$check = doquery( "select * from student_academic_year_balance where academic_year_id='".$academic_year_id."' and student_id='".$r[ "id" ]."'", $dblink );
															if( numrows( $check ) > 0 ) {
																$check = dofetch( $check );
																$balance = $check[ "balance" ];
															}
															$field_value = $balance;
														}
														else if( in_array( $field, $student_main_fields ) ) {
															$field_value = unslash( $r[ $field ] );
														}
														else if( $field == 'group' || $field == 'optional_subjects' ) {
															//$field_value = unslash( $r[ $field ] );
														}
														else {
															$field_value = get_student_meta( $r[ "id" ], $field );
														}
														?>
														<td>
                                                        	<?php 
															if( $field == 'group' || $field == 'optional_subjects' ) {
																if( $field == 'group' ){
																	?>
                                                                    <select name="field_value[<?php echo $field?>][<?php echo $r["id"]?>]">
                                                                        <option value="0">Select Group</option>
                                                                        <?php
                                                                        $res=doquery("select * from `group` where status = 1 order by title",$dblink);
                                                                        if(numrows($res)>0){
                                                                            while($rec=dofetch($res)){
                                                                            ?>
                                                                            <option value="<?php echo $rec["id"]?>"<?php echo($r["group_id"]==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                                            <?php			
                                                                            }	
                                                                        }
                                                                        ?>
                                                                    </select>
                                                            		<?php
																}
																else if($field == 'optional_subjects'){
																	?>
																	<select name="field_value[<?php echo $field?>][<?php echo $r["id"]?>][]" id="optional_subjects_<?php echo $r["id"]?>" multiple="multiple" class="select_multiple" title="Choose Option">
                                                                        <option value="0">Select Subject</option>
                                                                        <?php
                                                                        $res=doquery("select * from subject where is_optional = 1 and class_id = (select class_id from class_section where id = '".$r["class_section_id"]."') order by sortorder",$dblink);
																		$subject_ids = array();
																		$sql="select * from student_2_class_2_subject where student_2_class_id='".$r["student_2_class_id"]."'";
																		$rs1 = doquery( $sql, $dblink );
																		if( numrows( $rs1 ) > 0 ) {
																			while( $r1 = dofetch( $rs1 ) ) {
																				$subject_ids[] = $r1[ "subject_id" ];
																			}
																		}
                                                                        if(numrows($res)>0){
                                                                            while($rec=dofetch($res)){
                                                                            ?>
                                                                            <option value="<?php echo $rec["id"]?>"<?php echo(isset($subject_ids) && in_array($rec["id"], $subject_ids))?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                                            <?php			
                                                                            }			
                                                                        }
                                                                        ?>
                                                                    </select>
																	<?php
																}
															}
															else{
																?>
                                                            	<input style="width: 100%" type="text" name="field_value[<?php echo $field?>][<?php echo $r["id"]?>]" value="<?php echo strpos( $field,"date" )!== false?date_convert($field_value):$field_value?>" class="<?php echo strpos( $field,"date" ) !== false?"datepicker":""?>" />
                                                            	<?php
															}
															?>
                                                        </td>
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
                                <input type="submit" name="save_balance" value="Save Balance" class="btn btn-sm btn-primary" />
        						&nbsp;&nbsp;<a href="academic_year_manage.php?tab=view&id=<?php echo $academic_year_id?>" class="btn btn-sm btn-primary">Back to List</a>&nbsp;&nbsp;<a class="print-view btn btn-sm btn-primary">Print</a>
                            </form>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>
<style>
	#for-print table{margin-bottom:10px;}
	#for-print table th,#for-print table td{ padding:3px 5px; border:1px solid #333;line-height: 1.5em; color:#333;}
	.head th{ text-transform:uppercase;}
	.daily-sale{}
	#for-print{ display:none; padding: 0px 20px 10px;}
	#for-print h1{ font-size:20px; margin-top:10px;}
		#for-print h2{ font-size:16px;margin-top:10px;}
	#for-print .ui-datepicker{ display:none !important;}
	@media print{
		#navbar, .page-content, .footer{ display:none !important;}
		.main-content-inner > * { display:none;}
		.main-content-inner > #for-print{ display:block;}
		 #for-print{ display:block;}
		#for-print h3{ margin-top:0;}
		.content{ padding-top:0;padding-bottom:0;}
		.page-header{ display:none;}
		
		.page-content{ padding-bottom:0;}
		.main-container{ padding-top:0 !important;}
		.main-content{ margin-left:0;}
	}
</style>
<div id="for-print">
	<table width="100%" cellspacing="0" cellpadding="0">
        <tr class="head">
            <th colspan="9">
                <?php echo get_config( 'fees_chalan_header' )?>
                <h2>Student Lists</h2>
                <p>
                   <?php
                   	if( !empty( $class_section_id ) ){
						echo " Class: ".get_field( get_field( $class_section_id, "class_section", "class_id" ), "class", "class_name" ).'-'.get_field( $class_section_id, "class_section");
					}
				   ?>
                </p>
            </th>
        </tr>
        <tr>
            <th width="3%" class="center">S.No</th>
            <th width="17%">Student Name</th>
            <?php
            $fees = array();
            foreach( $fields as $field ) {
                if( substr( $field, 0, 5 ) === 'fees_' ) {
                    $fee_id = str_replace( "fees_", "", $field );
                    $fees[ $field ] = dofetch( doquery( "select * from fees where id = '".$fee_id."'", $dblink ));
                    $title = unslash( $fees[ $field ][ "title" ] );
                }
                else {
                    $title = ucwords(str_replace( "_", " ", $field));
                }
                ?>
                <th width="<?php echo 80/count($fields)?>%"><?php echo $title?></th>
                <?php
            }
            ?>
        </tr>
		<?php
        $sn=1;
        ?>
        <?php
        $rs=doquery($sql, $dblink);
        if(numrows($rs)>0){
			$total_balance = 0;
            while($r=dofetch($rs)){          
                ?>
                <tr>
                    <td align="center"><?php echo $sn;?></td>
                    <td><?php echo unslash($r["student_name"]); echo " - ".unslash($r["father_name"]); ?></td>
                    <?php
					foreach( $fields as $field ) {
                        if( substr( $field, 0, 5 ) === 'fees_' ) {
                            $field2 = "fees_".$fees[ $field ][ "id" ].($fees[ $field ][ "has_discount" ]?"_approved":"");
                            $field_value = get_student_meta( $r[ "id" ], $field2 );
							if( !isset( $total_fees[ $fees[ $field ][ "id" ] ] ) ) {
								$total_fees[ $fees[ $field ][ "id" ] ] = 0;
							}
							$total_fees[ $fees[ $field ][ "id" ] ] += $field_value;
                        }
                        else if( $field == 'balance' && 0 ) {
                            $balance = 0;
							die();
                            $check = doquery( "select * from student_academic_year_balance where academic_year_id='".$academic_year_id."' and student_id='".$r[ "id" ]."'", $dblink );
                            if( numrows( $check ) > 0 ) {
                                $check = dofetch( $check );
                                $balance = $check[ "balance" ];
                            }
                            $field_value = $balance;
                        }
                        else if( in_array( $field, $student_main_fields ) ) {
                            $field_value = unslash( $r[ $field ] );
                        }
                        else {
                            $field_value = get_student_meta( $r[ "id" ], $field );
                        }
						if( $field == 'balance' ){
							$total_balance += $field_value;
						}
                        ?>
                        <td>
                            <span style="width: 100%" class="<?php echo strpos( $field,"date" ) !== false?"datepicker":""?>"><?php echo strpos( $field,"date" )!== false?date_convert($field_value):$field_value?></span>
                        </td>
                        <?php
                    }
                    ?>
                </tr>  
                <?php 
                $sn++;
            }
			?>
			<tr>
                <td align="center"><?php echo $sn;?></td>
                <th>Total</th>
                <?php
                foreach( $fields as $field ) {
					?>
					<th><?php if( $field == 'balance' ){ echo $total_balance; } if( substr( $field, 0, 5 ) === 'fees_' && isset( $total_fees[ $fees[ $field ][ "id" ] ] ) ) { echo $total_fees[ $fees[ $field ][ "id" ] ];}?></th>
					<?php
				}
				?>
           </tr>
			<?php
        }
        ?>
	</table>
</div>