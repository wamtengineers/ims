<?php 
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
if( isset( $_GET[ "id" ] ) ) {
	$r = dofetch(doquery( "select * from student where id='".slash($_GET["id"])."'", $dblink ));
}
else{
	header( "Location: index.php" );
	die;
}
?>
<?php include("header.php");?>
<style>
table th,table td{
	font-size:12px;
}
.editable{
	position:relative;
}
td.editable:hover {
    background: url("../images/edit.png") no-repeat scroll right bottom #9f9;
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
                            <table id="dynamic-table" class="table list table-bordered table-hover" style="background:#f5f5f5">
                            	<thead>
                            		<tr>
                                    	<th colspan="10">Student Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="10%">Student Name</th>
                                        <td width="10%"><?php echo unslash($r["student_name"]);?></td>
                                        <th width="10%">Father Name</th>
                                        <td width="10%"><?php echo unslash($r["father_name"]); ?></td>
                                        <th width="10%">Class</th>
                                        <td width="10%" class="editable">
											<?php echo get_student_class($r["id"]); ?>
                                            <div id="editable_popup">
                                                <div class="editable_popup_content">
                                                	<form id="editable_form" method="post">
                                                        <input type="text" value="" name="" />
                                                        <div class="popup_btn">
                                                        	<input type="submit" value="Save" name="submit" class="btn btn-sm btn-primary" />
                                                            <input type="button" value="Cancel" name="cancel" id="cancel" class="btn btn-sm btn-danger" />
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <th width="10%">Surname</th>
                                        <td width="10%"><?php echo unslash($r["surname"]); ?></td>
                                        <th width="10%">Nationality</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "nationality"); ?></td>
                                    </tr> 
                                    <tr>
                                        <th width="10%">Birth Date</th>
                                        <td width="10%"><?php echo date_convert($r["birth_date"]);?></td>
                                        <th width="10%">Birth Place</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "birth_place"); ?></td>
                                        <th width="10%">Gender</th>
                                        <td width="10%"><?php echo $r[ "gender" ]=='male'?'Male':'Female'?></td>
                                        <th width="10%">CNIC#</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "cnic_no"); ?></td>
                                        <th width="10%">Religion</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "religion"); ?></td>
                                    </tr>
                                    <tr>
                                    	<th width="10%" colspan="2">Home Address</th>
                                        <td width="10%" colspan="3"><?php echo unslash($r["address"]);?></td>
                                        <th width="10%" colspan="2">Corresponding Address</th>
                                        <td width="10%" colspan="3"><?php echo get_student_meta($r["id"], "corresponding_address"); ?></td>
                                    </tr>
                                    <tr>
                                        <th width="10%">Phone</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "phone"); ?></td>
                                        <th width="10%">Corr Tel#</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "corresponding_phone"); ?></td>
                                        <th width="10%">Father Occupation:</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "father_occupation"); ?></td>
                                        <th width="10%">Father Email</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "father_email");?></td>
                                        <th width="10%">Father Tel</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "father_number"); ?></td>
                                    </tr>
                                    <tr>
                                        <th width="10%">Guardian</th>
                                        <td width="10%"><?php echo $r[ "gender" ]=='father'?'Father':'Mother'?></td>
                                        <th width="10%">Mother Name</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "mother_name"); ?></td>
                                        <th width="10%">Mother Occupation:</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "mother_occupation"); ?></td>
                                        <th width="10%">Mother Email</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "mother_email"); ?></td>
                                        <th width="10%">Mother Tel</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "mother_office_phone"); ?></td>
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
                                        <td width="10%"><?php echo get_student_meta($r["id"], "gr_no"); ?></td>
                                        <th width="10%">Addmission Date</th>
                                        <td width="10%"><?php echo date_convert($r["addmission_date"]);?></td>
                                        <th width="10%">Domicile / PRC</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "domicile_district"); ?></td>
                                        <th width="10%">Addmission Note</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "notes"); ?></td>
                                        
                                    </tr>
                                    <tr>
                                    	<th width="10%" colspan="2">Last Sch.Att.To</th>
                                        <td width="10%"><?php echo date_convert(get_student_meta($r["id"], "last_school_attended_to_date")); ?></td>
                                        <th width="10%" colspan="2">Last Sch.Att.From</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "last_school_attended_from_date"); ?></td>
                                        <th width="10%">Last Sch.Att</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "last_school_attended"); ?></td>
                                    </tr>
                                 </tbody>
                            </table>
                            <table id="dynamic-table" class="table list table-bordered table-hover" style="background:#f5f5f5">
                            	<thead>
                            		<tr>
                                    	<th colspan="10">Fee Details</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr>
                                        <th width="10%">Addmission Fee</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "gr_no"); ?></td>
                                        <th width="10%">Addmission Fee Discount</th>
                                        <td width="10%"><?php echo date_convert($r["addmission_date"]);?></td>
                                        <th width="10%">Approved Addmission Fee</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "domicile_district"); ?></td>
                                        <th width="10%">Security Deposit</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "notes"); ?></td>
                                        <th width="10%">Security Deposit Discount</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "notes"); ?></td>
                                        <th width="10%">Approved Security Deposit</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "notes"); ?></td>
                                    </tr>
                                    <tr>
                                    	<th width="10%" colspan="2">Tuition Fee</th>
                                        <td width="10%"><?php echo date_convert(get_student_meta($r["id"], "last_school_attended_to_date")); ?></td>
                                        <th width="10%" colspan="2">Tuition Fee Discount</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "last_school_attended_from_date"); ?></td>
                                        <th width="10%">Approved Tuition Fee</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "last_school_attended"); ?></td>
                                    </tr>
                                    <tr>
                                        <th width="10%">Taekwondo Fees</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "gr_no"); ?></td>
                                        <th width="10%">Annual Activity</th>
                                        <td width="10%"><?php echo date_convert($r["addmission_date"]);?></td>
                                        <th width="10%">Stationary Fee</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "domicile_district"); ?></td>
                                        <th width="10%">Student ID Card Fee</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "notes"); ?></td>
                                        <th width="10%">Music Class Fee</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "notes"); ?></td>
                                        <th width="10%">Examination Fee</th>
                                        <td width="10%"><?php echo get_student_meta($r["id"], "notes"); ?></td>
                                    </tr>
                                 </tbody>
                            </table>
                            <?php 
							$student_2_class = doquery( "select a.*, b.title as section, c.class_name as class, d.title as level, e.title as `group`, f.title as board, g.title as academic_year from student_2_class a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id left join class_level d on c.class_level_id = d.id left join `group` e on a.group_id = e.id left join board f on a.board_id = f.id left join academic_year g on a.academic_year_id = g.id where student_id='".$r["id"]."' order by g.start_date desc", $dblink );    
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
                                                        $rs = doquery( "select a.id as chalan_id, a.*, b.* from fees_chalan a left join fees_chalan_receiving b on a.id = b.fees_chalan_id where student_2_class_id	 = '".$student_class["id"]."' order by month desc", $dblink );
														if( numrows( $rs ) > 0 ) {
															$sn=1;
															while( $r = dofetch( $rs ) ) {
																?>
																<tr>
                                                                    <td><?php echo $r[ "chalan_id" ];?></td>
                                                                    <td><?php echo date( "M Y", strtotime( $r[ "month" ]."01" ) )?></td>
                                                                    <td><?php echo date_convert( $r["issue_date"] );?></td>
                                                                    <td>
                                                                    <?php
																		$amount = 0;
																		$chalan_details = get_chalan_details( $r["chalan_id"] );
																		foreach( $chalan_details[ "details" ] as $chalan_detail ) {
																			echo $chalan_detail[ "title" ].": ".curr_format( $chalan_detail[ "amount" ] )."<br />";
																			$amount += $chalan_detail[ "amount" ];
																		}
																		?>
                                                                    </td>
                                                                    <td><?php echo curr_format( $amount );?></td>
                                                                    <td><?php echo $r[ "amount" ]!=""?curr_format( $r[ "amount" ] ).' ('.date_convert( $r[ "payment_date" ] ).') <br> -':''?></td>
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
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>
<?php include("footer.php");