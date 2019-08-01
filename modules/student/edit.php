<?php
if(!defined("APP_START")) die("No Direct Access");
function get_student_form_field( $field, $filter = "" ) {
	global $student;
	if( !isset( $student ) ) {
		if( isset( $_SESSION[ "student_manage" ][ "add" ][ $field ] ) ) {
			return $_SESSION[ "student_manage" ][ "add" ][ $field ];
		}
		else {
			return "";
		}
	}
	else {
		if( isset( $_SESSION[ "student_manage" ][ "edit" ][ $student[ "id" ] ][ $field ] ) ) {
			return $_SESSION[ "student_manage" ][ "edit" ][ $student[ "id" ] ][ $field ];
		}
		else {
			if( isset( $student[ $field ] ) ) {
				$value = unslash( $student[ $field ] );
			}
			else {
				$value = get_student_meta($student[ "id" ], $field);
			}
			if( !empty( $filter ) ) {
				switch( $filter ) {
					case "date":
						$value = date_convert( $value );
					break;
				}
			}
			return $value;
		}
	}
}
?>
<div class="page-content">
    <div class="page-header">
    	<div class="row">
        	<div class="chak-bar">   	
            	<div class="col-sm-4 col-xs-8">
                	<div class="chak-bar-1">
                        <h1>
                            <?php echo isset($_GET["add"])?'Add New':'Edit'?> Student
                            <small>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                Manage Students
                            </small>
                        </h1>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-4 filter-btn"><i class="fa fa-filter" aria-hidden="true"></i></div>
                <div class="open-filter students_filter">
                    <div class="col-sm-5">
                    	<form method="get" action="student_manage.php">
                        	<input type="hidden" name="tab" value="edit" />
                            <select name="year_id" id="year_id" class="custom_select">
                                <?php
                                    $res=doquery("select * from academic_year where school_id = '".$_SESSION["current_school_id"]."' order by title desc",$dblink);
                                    if(numrows($res)>=0){
                                        while($rec=dofetch($res)){
                                        ?>
                                         <option value="<?php echo $rec["id"]?>" <?php echo($year_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                        <?php
                                        }
                                    }
                                ?>
                            </select>
                            <select name="class_section_id" id="class_section_id" class="custom_select">
                                <option value=""<?php echo ($class_section_id=="")? " selected":"";?>>All Classes</option>
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
                            <select name="student_status" id="student_status" class="custom_select">
                                <option value="1"<?php echo ($student_status=="1")? " selected":"";?>>Present</option>
                                <option value="0"<?php echo ($student_status=="0")? " selected":"";?>>Left</option>
                            </select>
                            <div class="input-group" style="width:350px; margin-top:10px;">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-check"></i>
                                </span>
                                <input class="form-control search-query" value="<?php echo $q;?>" name="q" id="search" type="text">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                        Search
                                    </button>
                                </span>
                            </div>
                       	</form>
                    </div>
                    <div class="col-sm-3">
                        <?php if( isset( $prev ) ) { ?><a href="student_manage.php?tab=edit&id=<?php echo $prev[ "id" ]?>" class="btn btn-sm btn-primary button">Prev</a><?php } ?>
                        <?php if( isset( $next ) ) { ?><a href="student_manage.php?tab=edit&id=<?php echo $next[ "id" ]?>" class="btn btn-sm btn-primary button">Next</a><?php } ?>
                        <a href="student_manage.php" class="btn btn-sm btn-primary button">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.page-header -->
    <style>
    .student_record input[type=text], .student_record input[type=email], .student_record select, .student_record textarea{ width:100%;}
	.set-no input{ width:auto !important;}
    </style>
    <div class="row student_record">
        <div class="col-xs-12">
        	<div class="new-form">
                <div class="new-form-1 clearfix">
                	<div class="row">
                        <div class="col-sm-4 col-xs-8">
                            <div class="set-no clearfix">
                                <h1>SID:</h1>
                                <input type="text" value="<?php echo isset( $student[ "id" ] )? $student[ "id" ]:'--'?>" id="sid" />
                            </div>
                            <?php if( isset( $student ) ) {?><div style="text-align:right">Record Created at: <span><?php echo datetime_convert( $student[ "created_at" ] )?></span><br />Last Modified: <?php echo datetime_convert( $student[ "ts" ] )?></div><?php } ?>
                        </div>
                        <script>
                        $(document).ready(function(){
							$("#sid").keypress(function(e){
								if( e.which == 13 ) {
									window.location.href='student_manage.php?tab=edit&id='+$(this).val();
								}
							});
						});
                        </script>
                        <div class="links-btn col-xs-4">
                        	<i class="fa fa-line-chart" aria-hidden="true"></i>
                        </div>
                        <?php if( isset( $student ) ) {?>
                            <div class="links-open">
                                <div class="col-sm-4">
                                    <div class="student-link clearfix">
                                        <ul>
                                            <li><a href="#">Admission Certificate</a></li>
                                            <li><a href="#">Date Of Birth Certificate</a></li>
                                            <li><a href="#">Primary Pass Certificate</a></li>
                                            <li><a href="student_manage.php?tab=idcard&id=<?php echo $id;?>" target="_blank">ID Card</a></li>
                                            <li><a href="student_manage.php?tab=fees_chalan&id=<?php echo $id;?>" target="_blank" class="fees_chalan_generate">Fees Chalan</a></li>
                                            <li><a href="student_manage.php?tab=marksheet&id=<?php echo $id;?>" class="marksheet_generate" target="_blank">Marksheet</a></li>
                                            <li><a href="student_manage.php?tab=month_star&id=<?php echo $id;?>" class="marksheet_generate" target="_blank">Star of Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="student-link clearfix">
                                        <ul>
                                            <li><a href="student_manage.php?tab=character_certificate&id=<?php echo $id;?>" target="_blank">Character Certificate(X)</a></li>
                                            <li><a href="student_manage.php?tab=leaving_certificate&id=<?php echo $id;?>" target="_blank">Leaving Certificate</a></li>
                                            <li><a href="#">Ceststeading Fee Notice</a></li>
                                            <li><a href="#">Absence Notice</a></li>
                                            <li><a href="#">Cert His Del</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                       	<?php } ?>
                    </div>
                </div>
                <div class="new-form-2 clearfix">
                    <form class="form-horizontal" role="form" action="student_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
                    	<div class="row bottom-10">
                        	<div class="col-md-12">
                            	<h2>Student's Details</h2>
                            </div>
                        </div>
                        <div class="row bottom-10 pos clearfix">
                            <div class="col-sm-2">
                            	<?php if(isset($student)) { ?>
                                	<input type="hidden" name="id" value="<?php echo $student[ "id" ];?>">
								<?php } ?>
								<?php
                                $student_image = get_student_form_field( "student_image" );
                                $student_image = !empty($student_image)?$file_upload_url."student/".$student_image."?v=".rand():"images/placeholder.jpg";
                                image_editor( 'student_image', $student_image, "student_manage.php", array( "tab" => "profile_image_upload", "id" => $student[ "id" ] ) );
                                ?>
                            </div>
                            <div class="col-sm-4">
                            	<div class="row clearfix bottom-10">
                                    <label class="col-sm-5">Name*:</label>
                                    <div class="col-sm-7"><input type="text" value="<?php echo get_student_form_field( "student_name" ); ?>" name="student_name" id="student_name" /></div>
                                </div>
                                <div class="row clearfix bottom-10">
                                    <label class="col-sm-5" for="birth_date">Date Of Birth:</label>
                                    <div class="col-sm-7"><input type="text" class="datepicker" value="<?php echo get_student_form_field( "birth_date", 'date' ); ?>" name="birth_date" id="birth_date" /></div>
                                </div>
                                <div class="row clearfix">
                                	<label class="col-sm-5" for="nationality">Nationality:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="col-xs-12" name="nationality" id="nationality" value="<?php echo get_student_form_field( "nationality");?>" placeholder="" />
                                    </div>
                                </div>                      
                            </div>
                            <div class="col-sm-4">
                            	<div class="row clearfix bottom-10">
                                    <label class="col-sm-5">Father Name*:</label>
                                    <div class="col-sm-7"><input type="text" value="<?php echo get_student_form_field( "father_name"); ?>" name="father_name" id="father_name" /></div>
                                </div>
                                <div class="row clearfix bottom-10">
                                    <label class="col-sm-5">Birth Place:</label>
                                    <div class="col-sm-7">
                                       <input type="text" value="<?php echo get_student_form_field( "birth_place" );?>" name="birth_place" id="birth_place" />
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <label class="col-sm-5">CNIC#:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="col-sm-4 col-xs-4" placeholder="#####-#######-#" name="cnic_no" id="cnic_no" value="<?php echo get_student_form_field( "cnic_no");?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                            	<div class="row clearfix bottom-10">
                                    <label class="col-sm-5">Surname:</label>
                                    <div class="col-sm-7"><input type="text" value="<?php echo get_student_form_field( "surname"); ?>" name="surname" id="surname" /></div>
                                </div>
                                <div class="row clearfix bottom-10">
                                    <label class="col-sm-5" for="gender">Gender:</label>
                                    <?php
                                    $gender = get_student_form_field( "gender" );
									?>
                                    <div class="col-sm-7">
                                        <select name="gender" id="gender">
                                            <option value="male"<?php echo $gender=="male"?' selected="selected"':''?>>Male</option>
                                            <option value="female"<?php echo $gender=="female"?' selected="selected"':''?>>Female</option>
                                    	</select>
                                    </div>
                                </div>
                                <div class="row clearfix bottom-10">
                                    <label class="col-sm-5" for="religion">Religion:</label>
                                    <?php
                                    $religion = get_student_form_field( "religion" );
									?>
                                    <div class="col-sm-7">
                                        <select name="religion" id="religion">
                                            <option value="muslim"<?php echo $religion=="muslim"?' selected="selected"':''?>>Muslim</option>
                                            <option value="nonmuslim"<?php echo $religion=="nonmuslim"?' selected="selected"':''?>>Non Muslim</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row clearfix bottom-10">
                        	<div class="col-sm-8">
                            	<div class="row">
                                    <label class="col-sm-3">Home Address:</label>
                                    <div class="col-sm-9"><textarea name="address" id="address"><?php echo get_student_form_field( "address"); ?></textarea></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                    <label class="col-sm-5">Telephone:</label>
                                    <div class="col-sm-7"><input type="text" name="phone" id="phone" value="<?php echo get_student_form_field( "phone");?>" placeholder="###########" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix bottom-10">
                        	<div class="col-sm-8">
                            	<div class="row clearfix">
                                    <label class="col-sm-3">Corresponding Address:</label>
                                    <div class="col-sm-9"><textarea name="corresponding_address" id="corresponding_address"><?php echo get_student_form_field( "corresponding_address");?></textarea></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                    <label class="col-sm-5">Corr Tel#:</label>
                                    <div class="col-sm-7"><input type="text" name="corresponding_phone" id="corresponding_phone" value="<?php echo get_student_form_field( "corresponding_phone");?>" placeholder="" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix bottom-10">
                        	<div class="col-sm-3">
                            	<div class="row clearfix">
                                    <label class="col-sm-6" for="father_occupation">Father Occupation:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="col-xs-12" name="father_occupation" id="father_occupation" value="<?php echo get_student_form_field( "father_occupation");?>" placeholder="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row clearfix">
                                    <label class="col-sm-6" for="email">Father Email:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12" name="father_email" id="father_email" value="<?php echo get_student_form_field( "father_email");?>" placeholder="" /></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row clearfix">
                                	<label class="col-sm-6" for="father_number">Father Tel:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12" name="father_number" id="father_number" value="<?php echo get_student_form_field( "father_number");?>" /></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                            	<label class="col-sm-6" for="guardian">Guardian:</label>
                                <div class="col-sm-6">
                                	<?php
                                    $guardian = get_student_form_field( "guardian" );
									?>
                                    <select name="guardian" id="guardian">
                                        <option value="father"<?php echo $guardian=="father"?' selected="selected"':''?>>Father</option>
                                        <option value="mother"<?php echo $guardian=="mother"?' selected="selected"':''?>>Mother</option>
                                        <option value="other"<?php echo $guardian=="other"?' selected="selected"':''?>>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix bottom-10">
                        	<div class="col-sm-3">
                                <div class="row clearfix">
                                   <label class="col-sm-6">Mother Name:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12" name="mother_name" id="mother_name" value="<?php echo get_student_form_field( "mother_name" );?>" placeholder="" /></div>
                                </div>
                            </div>
                        	<div class="col-sm-3">
                                <div class="row clearfix">
                                    <label class="col-sm-6">Mother Occupation:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12" name="mother_occupation" id="mother_occupation" value="<?php echo get_student_form_field( "mother_occupation" );?>" placeholder="" /></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row clearfix">
                                    <label class="col-sm-6" for="mother_email">Mother Email:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12" name="mother_email" id="mother_email" value="<?php echo get_student_form_field( "mother_email" );?>" placeholder="" /></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row clearfix">
                                    <label class="col-sm-6" for="mother_office_phone">Mother Tel:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12" name="mother_office_phone" id="mother_office_phone" value="<?php echo get_student_form_field( "mother_office_phone" );?>" placeholder="" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="row bottom-10">
                        	<div class="col-md-12 bottom-10">
                            	<h2>Addmission Details</h2>
                            </div>
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                    <label class="col-sm-6">G.R.No:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12" value="<?php echo get_student_form_field( "gr_no" );?>" name="gr_no" id="gr_no" placeholder="" /></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                    <label class="col-sm-6">Addmission Date:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="datepicker" value="<?php echo get_student_form_field( "addmission_date", "date" );?>" name="addmission_date" id="addmission_date" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                    <label class="col-sm-6">Domicile / PRC:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="col-xs-12" name="domicile_district" id="domicile_district" value="<?php echo get_student_form_field( "domicile_district" );?>" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row bottom-10">
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                    <label class="col-sm-6" for="notes">Addmission Note:</label>
                                    <div class="col-sm-6"><textarea name="notes" id="notes" class="col-xs-12"><?php echo get_student_form_field( "notes" );?></textarea></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                	<label class="col-sm-6">Last Sch.Att.To:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12 datepicker" name="last_school_attended_to_date" id="last_school_attended_to_date" value="<?php echo get_student_form_field( "last_school_attended_to_date" );?>" placeholder="" /></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row clearfix">
                                	<label class="col-sm-6">Last Sch.Att.From:</label>
                                    <div class="col-sm-6"><input type="text" class="col-xs-12 datepicker" name="last_school_attended_from_date" id="last_school_attended_from_date" value="<?php echo get_student_form_field( "last_school_attended_from_date" );?>" placeholder="" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix bottom-10">
                        	<div class="col-sm-4">
                            	<div class="row clearfix">
                                    <label class="col-sm-5" for="houses_id">Houses:</label>
                                    <?php $houses_id = get_student_form_field( "houses_id" );?>
                                    <div class="col-sm-7">
                                        <select name="houses_id" title="Choose Option">
                                            <option value="0">Select Houses</option>
                                            <?php
                                            $res=doquery("Select * from houses order by title",$dblink);
                                            if(numrows($res)>0){
                                                while($rec=dofetch($res)){
                                                ?>
                                                <option value="<?php echo $rec["id"]?>"<?php echo($houses_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                                                <?php			
                                                }			
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row bottom-10">
                        	<div class="col-md-12 bottom-10">
                            	<h2>Fees</h2>
                            </div>
							<?php
                            $fees = doquery( "select * from fees where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by sortorder", $dblink );
                            if( numrows( $fees ) > 0 ) {
                                while( $fee = dofetch( $fees ) ) {
									$checkbox = get_student_form_field( "fees_".$fee[ "id" ] );
                                    ?>
                                    <div class="col-sm-4 bottom-10">
                                    	<div class="row clearfix">
                                            <label class="col-sm-6" for="fees_<?php echo $fee[ "id" ]?>"><?php echo unslash( $fee[ "title" ] )?>: <?php if( $fee[ "selected_students" ] ) { ?><input type="checkbox" name="fees_<?php echo $fee[ "id" ]?>_required" value="1"<?php if(($checkbox)>0) echo ' checked="checked"';?> /><?php }?></label>
                                            <div class="col-sm-6"><input class="col-xs-12" type="text" name="fees_<?php echo $fee[ "id" ]?>" id="fees_<?php echo $fee[ "id" ]?>" value="<?php echo get_student_form_field( "fees_".$fee[ "id" ] );?>" /></div>
                                        </div>
                                    </div>
                                    <?php
									if( $fee[ "has_discount" ] ) {
										?>
										<div class="col-sm-4 bottom-10">
                                            <div class="row clearfix">
                                                <label class="col-sm-6" for="fees_<?php echo $fee[ "id" ]?>_discount"><?php echo unslash( $fee[ "title" ] )?> Discount:</label>
                                                <div class="col-sm-6"><input class="col-xs-12" type="text" name="fees_<?php echo $fee[ "id" ]?>_discount" id="fees_<?php echo $fee[ "id" ]?>_discount" value="<?php echo get_student_form_field( "fees_".$fee[ "id" ]."_discount" );?>" /></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 bottom-10">
                                            <div class="row clearfix">
                                                <label class="col-sm-6" for="fees_<?php echo $fee[ "id" ]?>_approved">Approved <?php echo unslash( $fee[ "title" ] )?>:</label>
                                                <div class="col-sm-6"><input class="col-xs-12" type="text" name="fees_<?php echo $fee[ "id" ]?>_approved" id="fees_<?php echo $fee[ "id" ]?>_approved" value="<?php echo get_student_form_field( "fees_".$fee[ "id" ]."_approved" );?>" /></div>
                                            </div>
                                        </div>
										<?php
									}
                                }
                            }
                            ?>
                       	</div>
                        <div class="row">
                        	<label class="col-sm-3" for="balance">Opening Balance</label>
                            <div class="col-sm-6">
                            	<input type="text" name="balance" id="balance" value="<?php echo get_student_form_field( 'balance' );?>" />
                           	</div>
                      	</div>
                        <?php 
						if(!empty($student["id"])){
							$student_2_class = doquery( "select a.*, b.title as section, c.class_name as class, d.title as level, e.title as `group`, f.title as board, g.title as academic_year from student_2_class a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id left join class_level d on c.class_level_id = d.id left join `group` e on a.group_id = e.id left join board f on a.board_id = f.id left join academic_year g on a.academic_year_id = g.id where student_id='".$student["id"]."' order by g.start_date desc", $dblink );    
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
                                        <div class="btn-top-right">
                                            <a href="student_2_class_manage.php?tab=add&parent_id=<?php echo $student["id"]?>" class="btn btn-info btn-new fancybox iframe">Add New Class</a>
                                        </div>
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
                                                    <div class="btn-right">
                                                        <a href="student_2_class_manage.php?tab=edit&parent_id=<?php echo $student["id"]?>&id=<?php echo $student_class[ "id" ]?>" class="btn btn-info btn-new fancybox iframe">Edit Class</a>
                                                        <a href="student_2_class_manage.php?tab=delete&parent_id=<?php echo $student["id"]?>&id=<?php echo $student_class[ "id" ]?>" class="btn btn-info btn-new fancybox iframe" onClick="return confirm('Are you sure you want to delete')">Delete Class</a>
                                                        <?php
                                                        if( empty( $student_class[ "status" ] ) ) {
															?>
															<a href="student_2_class_manage.php?tab=status&parent_id=<?php echo $student["id"]?>&id=<?php echo $student_class[ "id" ]?>" class="btn btn-info btn-new fancybox iframe" onClick="return confirm('Are you sure you want to make current')">Mark as current Class</a>
															<?php
														}
														?>
                                                    </div>
                                                    <table class="table list table-bordered table-hover">
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
                                                    <h3>Fees Chalan</h3>
                                                    <div class="row"><div class="col-sm-3"><div class="btn-right">
                                                        <a href="fees_chalan_manage.php?tab=add&parent_id=<?php echo $student_class["id"]?>" class="btn btn-info btn-new fancybox iframe">Generate Chalan</a>
                                                    </div></div>
                                                    </div>
                                                    
                                                    <table class="table list table-bordered table-hover">
                                                        <tr>
                                                            <th width="2%">Chalan#</th>
                                                            <th>Month</th>
                                                            <th>Issue Date</th>
                                                            <th>Details</th>
                                                            <th>Total Amount</th>
                                                            <th>Receiving</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        <?php
                                                        $rs = doquery( "select a.id as chalan_id, a.*, b.* from fees_chalan a left join fees_chalan_receiving b on a.id = b.fees_chalan_id where student_2_class_id	 = '".$student_class["id"]."' and a.school_id = '".$_SESSION["current_school_id"]."' order by month desc", $dblink );
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
                                                                    <td><?php echo $r[ "amount" ]!=""?curr_format( $r[ "amount" ] ).' ('.date_convert( $r[ "payment_date" ] ).') <br><a href="fees_chalan_manage.php?tab=edit_receiving&id='.$r[ "chalan_id" ].'" class="fancybox iframe">Edit</a> - <a href="fees_chalan_manage.php?tab=edit_receiving&id='.$r[ "chalan_id" ].'&delete" class="fancybox iframe">Delete</a>':'<a href="fees_chalan_manage.php?tab=edit_receiving&id='.$r[ "chalan_id" ].'" class="fancybox iframe">Add</a>'?></td>
                                                                    <td><a href="fees_chalan_manage.php?tab=edit&parent_id=<?php echo $student_class["id"]?>&id=<?php echo $r[ "chalan_id" ]?>" class="btn btn-info btn-new fancybox iframe"><i class="fa fa-edit"></i></a> - <a href="fees_chalan_manage.php?tab=delete&parent_id=<?php echo $student_class["id"]?>&id=<?php echo $r[ "chalan_id" ]?>" class="btn btn-info btn-new fancybox iframe"><i class="fa fa-trash-o"></i></a></td>
                                                              	</tr>
																<?php
																$sn++;
															}
														}
														else {
															?>
															<tr>
                                                            	<td colspan="7" class="alert alert-danger">No Chalan Found.</td>
                                                            </tr>
															<?php
														}
														?>
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
                            <?php
						}
						?>
                        <div class="row">
                            <div class="col-sm-6">
                                <button class="btn btn-info btn-new" type="submit" name="student_add" title="Add Record">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>