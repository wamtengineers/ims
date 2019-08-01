<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Employee
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Employee
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="employee_manage.php" class="btn btn-sm btn-primary">Back to List</a> <a href="employee_manage.php?tab=idcard&id=<?php echo $id;?>" class="btn btn-sm btn-primary">ID CARD</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="employee_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group">
                	<div class="row bottom-10">
                    	<div class="col-sm-2" style="margin-top:-50px;">
                        <?php
                        $employee_image = get_employee_meta($id, "employee_image");
                        $employee_image = !empty($employee_image)?$file_upload_url."employee/".$employee_image."?v=".rand():"images/placeholder.jpg";
                        image_editor( 'employee_image', $employee_image, "employee_manage.php", array( "tab" => "profile_image_upload", "id" => $id ) );
                        ?>  	
                        </div>
                    	<div class="col-md-2">
                            <label class="" for="department_id">Department <span class="red">*</span> </label>
                            <div class="">
                                <select name="department_id" title="Choose Option">
                                    <option value="0">Select Department id</option>
                                    <?php
                                    $res=doquery("Select * from department where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
                                    if(numrows($res)>0){
                                        while($rec=dofetch($res)){
                                        ?>
                                        <option value="<?php echo $rec["id"]?>"<?php echo($department_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                                     	<?php			
                                        }			
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="" for="designation_id">Designation <span class="red">*</span> </label>
                            <div class="">
                                <select name="designation_id" title="Choose Option">
                                    <option value="0">Select Designation id</option>
                                    <?php
                                    $res=doquery("Select * from designation where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
                                    if(numrows($res)>0){
                                        while($rec=dofetch($res)){
                                        ?>
                                        <option value="<?php echo $rec["id"]?>"<?php echo($designation_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                                     	<?php			
                                        }			
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="name">Name <span class="red">*</span></label>
                            <div class="">
                                <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="father_name">Father Name</label>
                            <div class="">
                                <input type="text" title="Enter Father Name" value="<?php echo $father_name; ?>" name="father_name" id="father_name" class="col-xs-10" />
                            </div>
                        </div>
                    </div>
                    <div class="row bottom-10">
                        <div class="col-md-3">
                            <label class="" for="employee_code">Employee Code <span class="red">*</span></label>
                            <div class="">
                                <input type="text" title="Enter Employee Code" value="<?php echo $employee_code; ?>" name="employee_code" id="employee_code" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="surname">Surname</label>
                            <div class="">
                                <input type="text" title="Enter Surname" value="<?php echo $surname; ?>" name="surname" id="surname" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="birth_place">Birth Place</label>
                            <div class="">
                                <input type="text" title="Enter Birth Place" value="<?php echo $birth_place; ?>" name="birth_place" id="birth_place" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="gender">Gender</label>
                            <div class="">
                                <input type="text" title="Enter Gender" value="<?php echo $gender; ?>" name="gender" id="gender" class="col-xs-10" />
                            </div>
                        </div>
                    </div>
                    <div class="row bottom-10">
                        <div class="col-md-3">
                            <label class="" for="date_of_birth">Birth Date</label>
                            <div class="">
                                <input type="text" title="Enter Birth Date" value="<?php echo $date_of_birth; ?>" name="date_of_birth" id="date_of_birth" class="col-xs-10 datepicker" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="shift">Shift</label>
                            <div class="">
                                <input type="text" title="Enter Shift" value="<?php echo $shift; ?>" name="shift" id="shift" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="team_work_type">Work Type</label>
                            <div class="">
                                <input type="text" title="Enter Work Type" value="<?php echo $team_work_type; ?>" name="team_work_type" id="team_work_type" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="employee_type">Employee Type</label>
                            <div class="">
                                <input type="text" title="Enter Employee Type" value="<?php echo $employee_type; ?>" name="employee_type" id="employee_type" class="col-xs-10" />
                            </div>
                        </div>
                    </div>
                    <div class="row bottom-10">
                    	<div class="col-md-3">
                            <label class="" for="date_of_app">Date of Appointment</label>
                            <div class="">
                                <input type="text" title="Enter Date" value="<?php echo $date_of_app; ?>" name="date_of_app" id="date_of_app" class="col-xs-10 datepicker" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="subject">Subject</label>
                            <div class="">
                                <input type="text" title="Enter Subject" value="<?php echo $subject; ?>" name="subject" id="subject" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="level">Level</label>
                            <div class="">
                                <input type="text" title="Enter Level" value="<?php echo $level; ?>" name="level" id="level" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="address">Address</label>
                            <div class="">
                                <textarea title="Enter Address" name="address" id="address" class="col-xs-10"><?php echo $address; ?></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="row bottom-10">
                        <div class="col-md-3">
                            <label class="" for="mobile_number">Mobile Number</label>
                            <div class="">
                                <input type="text" title="Enter Number" value="<?php echo $mobile_number; ?>" name="mobile_number" id="mobile_number" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="telephone_number">Telephone Number</label>
                            <div class="">
                                <input type="text" title="Enter Number" value="<?php echo $telephone_number; ?>" name="telephone_number" id="telephone_number" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="religion">Religion</label>
                            <div class="">
                                <input type="text" title="Enter Religion" value="<?php echo $religion; ?>" name="religion" id="religion" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="nationality">Nationality</label>
                            <div class="">
                                <input type="text" title="Enter Nationality" value="<?php echo $nationality; ?>" name="nationality" id="nationality" class="col-xs-10" />
                            </div>
                        </div>
                    </div>
                    <div class="row bottom-10">
                        <div class="col-md-3">
                            <label class="" for="cnic_number">CNIC Number</label>
                            <div class="">
                                <input type="text" title="Enter CNIC" value="<?php echo $cnic_number; ?>" name="cnic_number" id="cnic_number" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="cnic_expiry_date">CNIC Expiry Date</label>
                            <div class="">
                                <input type="text" title="Enter Date" value="<?php echo $cnic_expiry_date; ?>" name="cnic_expiry_date" id="cnic_expiry_date" class="col-xs-10 datepicker" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="qualification">Qualification</label>
                            <div class="">
                                <input type="text" title="Enter Qualification" value="<?php echo $qualification; ?>" name="qualification" id="qualification" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="work_experiance">Work Experiance</label>
                            <div class="">
                                <input type="text" title="Enter Work" value="<?php echo $work_experiance; ?>" name="work_experiance" id="work_experiance" class="col-xs-10" />
                            </div>
                        </div>
                    </div>
                    <div class="row bottom-10">
                    	<div class="col-md-3">
                            <label class="" for="blood_group">Blood Group</label>
                            <div class="">
                                <input type="text" title="Enter Blood Group" value="<?php echo $blood_group; ?>" name="blood_group" id="blood_group" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="present_leave">Present/Leave</label>
                            <div class="">
                                <input type="text" title="Enter Present" value="<?php echo $present_leave; ?>" name="present_leave" id="present_leave" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="leave_date">Leave Date</label>
                            <div class="">
                                <input type="text" title="Enter Leave Date" value="<?php echo $leave_date; ?>" name="leave_date" id="leave_date" class="col-xs-10 datepicker" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="basic_salary">Basic Salary</label>
                            <div class="">
                                <input type="text" title="Enter Salary" value="<?php echo $basic_salary; ?>" name="basic_salary" id="basic_salary" class="col-xs-10" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row bottom-10">
                	<div class="col-md-3">
                        <label class="" for="timing_from">Timing From</label>
                        <div class="">
                            <input type="text" title="Enter Timing From" value="<?php echo $timing_from; ?>" name="timing_from" id="timing_from" class="form-control timing" data-inputmask="'mask': '99:99'" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="" for="timing_to">Timing To</label>
                        <div class="">
                            <input type="text" title="Enter Timing To" value="<?php echo $timing_to; ?>" name="timing_to" id="timing_to" class="form-control timing" data-inputmask="'mask': '99:99'" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="" for="username">Username <span class="red">*</span></label>
                        <div class="">
                            <input type="text" title="Enter Uername" value="<?php echo $username; ?>" name="username" id="username" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="" for="username">Password</label>
                        <div class="">
                            <input type="password" title="Enter Password" value="" name="password" id="password" class="form-control" />
                            <span id="helpBlock" class="help-block clr">Leave empty for no change.</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-9">
                            <button class="btn btn-info" type="submit" name="employee_edit" title="Update Record">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>