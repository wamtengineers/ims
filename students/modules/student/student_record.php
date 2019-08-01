<?php 
if(isset($_SESSION["logged_in_students"]["id"]) && !empty($_SESSION["logged_in_students"]["id"])){
	$record = dofetch(doquery( "select * from student where id='".slash($_SESSION["logged_in_students"]["id"])."'", $dblink ));
}
else{
	header( "Location: index.php" );
	die;
}
function get_student_form_field( $field, $filter = "" ) {
	global $r;	
	if( isset( $r[ $field ] ) ) {
		$value = unslash( $r[ $field ] );
	}
	else {
		$value = get_student_meta($r[ "id" ], $field);
	}
	return $value;	
}

?>
<style>
table th,table td{
	font-size:12px;
}
.editable{
	position:relative;
}
.editable a{
	display:block;
}
td.editable:hover {
    background: url("../images/edit.png") no-repeat right 10px center #9f9;
    cursor: text;
}
#editable_popup{
	background: none repeat scroll 0 0 #ffffff;
	border: 1px solid #cccccc;
	border-radius: 5px;
	box-shadow: 0 0 10px #999999;
	font-weight: normal;
	margin-top: 10px;
	padding: 10px;
	position: absolute;
	width: 250px;
	z-index: 99999;
	display:none;
	left: 0;
    top: 20px;
}
#editable_popup.editable_show{ display:block;}
#editable_popup input[type="text"]{
	margin:0;
	width:100%;
}
.popup_btn{
	margin-top:10px;
}
</style>
<div class="page-content">
    <div class="page-header">
    	<div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h1>
                    Student Details
                </h1>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 align-right">
                <a href="index.php" class="btn btn-sm btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        	<form method="post">
                            	<table id="dynamic-table" class="table list table-bordered table-hover" style="background:#f5f5f5">
                                    <thead>
                                        <tr>
                                            <th colspan="10">Student Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="10%">Student Name</th>
                                            <td width="10%"><?php echo unslash($record["student_name"]);?></td>
                                            <th width="10%">Father Name</th>
                                            <td width="10%"><?php echo unslash($record["father_name"]); ?></td>
                                            <th width="10%">Class</th>
                                            <td width="10%">
                                                <?php echo get_student_class($record["id"]); ?>
                                            </td>
                                            <th width="10%">Surname</th>
                                            <td width="10%">
                                                <?php echo unslash($record["surname"]); ?>
                                                
                                            </td>
                                            <th width="10%">Nationality</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "nationality"); ?></td>
                                        </tr> 
                                        <tr>
                                            <th width="10%">Birth Date</th>
                                            <td width="10%"><?php echo date_convert($record["birth_date"]);?></td>
                                            <th width="10%">Birth Place</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "birth_place"); ?></td>
                                            <th width="10%">Gender</th>
                                            <td width="10%"><?php echo $record[ "gender" ]=='male'?'Male':'Female'?></td>
                                            <th width="10%">CNIC#</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "cnic_no"); ?></td>
                                            <th width="10%">Religion</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "religion"); ?></td>
                                        </tr>
                                        <tr>
                                            <th width="10%" colspan="2">Home Address</th>
                                            <td width="10%" colspan="2"><?php echo unslash($record["address"]);?></td>
                                            <th width="10%" colspan="2">Corresponding Address</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "corresponding_address"); ?></td>
                                            <td width="10%"><input type="password" class="form-control" name="password" placeholder="Enter Password" value="<?php echo get_student_meta($record["id"], "password"); ?>" />
                                            <td width="10%"><input type="password" class="form-control" name="cpassword" placeholder="Enter Confirm Password" value="" /></td>
                                            <td width="10%">
                                            	<button type="submit" name="update_password" class="btn btn-sm btn-danger">
                                                    <span class="bigger-110">Submit</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="10%">Phone</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "phone"); ?></td>
                                            <th width="10%">Corr Tel#</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "corresponding_phone"); ?></td>
                                            <th width="10%">Father Occupation:</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "father_occupation"); ?></td>
                                            <th width="10%">Father Email</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "father_email");?></td>
                                            <th width="10%">Father Tel</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "father_number"); ?></td>
                                        </tr>
                                        <tr>
                                            <th width="10%">Guardian</th>
                                            <td width="10%"><?php echo $record[ "gender" ]=='father'?'Father':'Mother'?></td>
                                            <th width="10%">Mother Name</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "mother_name"); ?></td>
                                            <th width="10%">Mother Occupation:</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "mother_occupation"); ?></td>
                                            <th width="10%">Mother Email</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "mother_email"); ?></td>
                                            <th width="10%">Mother Tel</th>
                                            <td width="10%"><?php echo get_student_meta($record["id"], "mother_office_phone"); ?></td>
                                        </tr>
                                    </tbody>
                            	</table>                                
                            	<table id="dynamic-table" class="table list table-bordered table-hover" style="background:#f5f5f5">
                            	<thead>
                            		<tr>
                                    	<th colspan="10">Addmission Details</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr>
                                        <th width="10%">G.R.No</th>
                                        <td width="10%"><?php echo get_student_meta($record["id"], "gr_no"); ?></td>
                                        <th width="10%">Addmission Date</th>
                                        <td width="10%"><?php echo date_convert($record["addmission_date"]);?></td>
                                        <th width="10%">Domicile / PRC</th>
                                        <td width="10%"><?php echo get_student_meta($record["id"], "domicile_district"); ?></td>
                                        <th width="10%">Addmission Note</th>
                                        <td width="10%"><?php echo get_student_meta($record["id"], "notes"); ?></td>
                                        
                                    </tr>
                                    <tr>
                                    	<th width="10%" colspan="2">Last Sch.Att.To</th>
                                        <td width="10%"><?php echo date_convert(get_student_meta($record["id"], "last_school_attended_to_date")); ?></td>
                                        <th width="10%" colspan="2">Last Sch.Att.From</th>
                                        <td width="10%"><?php echo get_student_meta($record["id"], "last_school_attended_from_date"); ?></td>
                                        <th width="10%">Last Sch.Att</th>
                                        <td width="10%"><?php echo get_student_meta($record["id"], "last_school_attended"); ?></td>
                                    </tr>
                                 </tbody>
                            	</table>
                            	<table id="dynamic-table" class="table list table-bordered table-hover" style="background:#f5f5f5">
                            	<thead>
                            		<tr>
                                    	<th colspan="18">Fee Details</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                $fees = doquery( "select * from fees where status=1 order by sortorder", $dblink );
								if( numrows( $fees ) > 0 ) {
									?>
                                    <tr>
                                    <?php
                                	while( $fee = dofetch( $fees ) ) {
										?>
                                        <th width="7%"><?php echo unslash( $fee[ "title" ] )?></th>
                                       
                                        <?php 
									}
									?>   
                                    </tr>
                                    <?php 
								}
                                $fees1 = doquery( "select * from fees where status=1 order by sortorder", $dblink );
								if( numrows( $fees1 ) > 0 ) {
									?>
                                    <tr>
                                    <?php
                                	while( $fee1 = dofetch( $fees1 ) ) {
										?>
                                        <td width="3%"><?php echo get_student_form_field( "fees_".$fee1[ "id" ] );?></td>
                                        <?php 
									}
									?>   
                                    </tr>
                                    <?php 
								}
								?>
                                 </tbody>
                            </table>
								<?php 
                                $student_2_class = doquery( "select a.*, b.title as section, c.class_name as class, d.title as level, e.title as `group`, f.title as board, g.title as academic_year from student_2_class a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id left join class_level d on c.class_level_id = d.id left join `group` e on a.group_id = e.id left join board f on a.board_id = f.id left join academic_year g on a.academic_year_id = g.id where student_id='".$record["id"]."' order by g.start_date desc", $dblink );    
                                $student_classes = array();
                                if( numrows( $student_2_class ) > 0 ){
                                    while( $student_class = dofetch( $student_2_class ) ){
                                        $student_classes[] = $student_class;
                                    }
                                }
                                ?>
 								<div class="row clearfix bottom-10">
                                <div class="col-sm-12">
                                    <div class="tabs tabs-section dataTables_wrapper" id="tabs">
                                        <?php
                                        if( count( $student_classes ) > 0 ) {
											?>
                                            <ul class="main-tabs">
                                                <?php 
                                                $i = 1;
												foreach( $student_classes as $student_class ){
													?>
                                                    <li><a href="#tab<?php echo $i?>">Class <?php echo $student_class["class"]."-".$student_class["section"]." (".$student_class["academic_year"].")";?></a></li>
                                                    <?php
													$i++;
                                                }
                                                ?>
                                            </ul>
                                        	<?php
											$i = 1;
											foreach( $student_classes as $student_class ){
												?>
                                                <div id="tab<?php echo $i?>">
                                                    <table id="dynamic-table" class="table list table-bordered table-hover" style="background:#f5f5f5">
                                                        <tr>
                                                            <th>Academic Year</th>
                                                            <th>Class</th>
                                                            <th>Board</th>
                                                            <th>Group:</th>
                                                            <th>Adm-Level:</th>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo unslash( $student_class["academic_year"] );?></td>
                                                            <td><?php echo unslash( $student_class[ "class" ] )."-".unslash( $student_class[ "section" ] )?></td>
                                                            <td><?php echo unslash( $student_class["board"] );?></td>
                                                            <td><?php echo unslash( $student_class["group"] );?></td>
                                                            <td><?php echo unslash( $student_class["level"] );?></td>                                                        </tr>
                                                    </table>
                                                    <table id="dynamic-table" class="table list table-bordered table-hover" style="background:#f5f5f5">
                                                    	<thead>
                                                            <tr>
                                                                <th colspan="6">Fee Chalan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <th width="2%">Chalan#</th>
                                                            <th>Month</th>
                                                            <th>Issue Date</th>
                                                            <th>Details</th>
                                                            <th>Total Amount</th>
                                                            <th>Receiving</th>
                                                        </tr>
                                                        <?php
                                                        $rs1 = doquery( "select a.id as chalan_id, a.*, b.* from fees_chalan a left join fees_chalan_receiving b on a.id = b.fees_chalan_id where student_2_class_id	 = '".$student_class["id"]."' order by month desc", $dblink );
														if( numrows( $rs1 ) > 0 ) {
															$sn=1;
															while( $r1 = dofetch( $rs1 ) ) {
																?>
																<tr>
                                                                    <td><?php echo $r1[ "chalan_id" ];?></td>
                                                                    <td><?php echo date( "M Y", strtotime( $r1[ "month" ]."01" ) )?></td>
                                                                    <td><?php echo date_convert( $r1["issue_date"] );?></td>
                                                                    <td>
                                                                    <?php
																		$amount = 0;
																		$chalan_details = get_chalan_details( $r1["chalan_id"] );
																		foreach( $chalan_details[ "details" ] as $chalan_detail ) {
																			echo $chalan_detail[ "title" ].": ".curr_format( $chalan_detail[ "amount" ] )."<br />";
																			$amount += $chalan_detail[ "amount" ];
																		}
																		?>
                                                                    </td>
                                                                    <td><?php echo curr_format( $amount );?></td>
                                                                    <td><?php echo $r1[ "amount" ]!=""?curr_format( $r1[ "amount" ] ).' ('.date_convert( $r1[ "payment_date" ] ).') <br> -':''?></td>
                                                              	</tr>
																<?php
																$sn++;
															}
														}
														else {
															?>
															<tr>
                                                            	<td colspan="6" class="alert alert-danger">No Chalan Found.</td>
                                                            </tr>
															<?php
														}
														?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                       			<?php
												$i++;
                                            }
                                        }
                                       	?>
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