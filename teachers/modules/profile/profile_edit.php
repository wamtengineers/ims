<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Edit Profile
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Profile
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="index.php" class="btn btn-sm btn-primary">Back to List</a> <a href="profile.php?tab=idcard&id=<?php echo $_SESSION["logged_in_teachers"]["id"];?>" class="btn btn-sm btn-primary">ID CARD</a> </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="profile.php?tab=profile_edit" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $_SESSION["logged_in_teachers"]["id"];?>">
                <div class="form-group">
                	<div class="row bottom-10">
                    	<div class="col-sm-1" style="margin-top:-50px;">
                        <?php
                        $employee_image = get_employee_meta($_SESSION["logged_in_teachers"]["id"], "employee_image");
                        $employee_image = !empty($employee_image)?$file_upload_url."employee/".$employee_image."?v=".rand():"placeholder.jpg";
                        image_editor( 'employee_image', $employee_image, "profile.php", array( "tab" => "profile_image_upload", "id" => $_SESSION["logged_in_teachers"]["id"] ) );
                        ?>  	
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
                        <div class="col-md-3">
                            <label class="" for="surname">Surname</label>
                            <div class="">
                                <input type="text" title="Enter Surname" value="<?php echo $surname; ?>" name="surname" id="surname" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="" for="birth_place">Birth Place</label>
                            <div class="">
                                <input type="text" title="Enter Birth Place" value="<?php echo $birth_place; ?>" name="birth_place" id="birth_place" class="col-xs-10" />
                            </div>
                        </div>
                    </div>
                    <div class="row bottom-10">
                        <div class="col-md-3">
                            <label class="" for="gender">Gender</label>
                            <div class="">
                                <input type="text" title="Enter Gender" value="<?php echo $gender; ?>" name="gender" id="gender" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="date_of_birth">Birth Date</label>
                            <div class="">
                                <input type="text" title="Enter Birth Date" value="<?php echo $date_of_birth; ?>" name="date_of_birth" id="date_of_birth" class="col-xs-10 datepicker" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="subject">Subject</label>
                            <div class="">
                                <input type="text" title="Enter Subject" value="<?php echo $subject; ?>" name="subject" id="subject" class="col-xs-10" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="" for="address">Address</label>
                            <div class="">
                                <textarea rows="2" title="Enter Address" name="address" id="address" class="col-xs-12"><?php echo $address; ?></textarea>
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
                        <label class="" for="username">Password</label>
                        <div class="">
                            <input type="password" title="Enter Password" value="" name="password" id="password" class="form-control" />
                            <span id="helpBlock" class="help-block clr">Leave empty for no change.</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-9">
                            <button class="btn btn-info" type="submit" name="profile_edit" title="Update Record">
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