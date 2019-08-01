<?php 
include("../inc/db.php");
include("../inc/utility.php");
if( isset( $_GET[ "step" ] ) ) {
	unset($_SESSION["register_students"]);
}
if( isset( $_GET[ "resend" ] ) ) {
	sendsms( $_SESSION["register_students"]["sms_number"], $_SESSION["register_students"]["sms_text"] );
	$_SESSION["register_students"]["msg"]='Verification code has been resent.'.$_SESSION["register_students"]["sms_text"];
	header('Location: register.php');
	die;
}
if(isset($_POST["register_submit"])){
	$msg="";
	unset($_SESSION["register_students"]["err"]);
	unset($_SESSION["register_students"]["msg"]);
	if( !isset( $_SESSION["register_students"][ "student_id" ] ) ) {
		$student_id=slash($_POST["student_id"]);
		$mobile_number=slash($_POST["phone"]);
		$_SESSION["register_students"]["phone"]=$mobile_number;
		if(!empty($student_id) && strlen($mobile_number)>0){
			$qr="select a.id, gender,student_name,father_name from student a inner join student_meta b on a.id = b.student_id where meta_value='".$mobile_number."' and meta_key='phone' and a.id = '".$student_id."'";
			$res=doquery($qr, $dblink);
			if(numrows($res)>0){
				//$text = 'Dear Parent\n';
				$text = 'You have register with us.\n';
				//$student_ids = array();
				$row=dofetch($res);
				$student_id=$row["id"];
				$text .= unslash( $row[ "student_name" ] ).' '.($row["gender"]=='male'?'S':'D').'/o '.unslash( $row[ "father_name" ] )."\n";
				
				$_SESSION["register_students"]["code"] = rand(10000, 99999);
				$text.='Your verification code is '.$_SESSION["register_students"]["code"]."\n";
				$text.=$site_title;
				$_SESSION["register_students"]["sms_number"] = $mobile_number;
				$_SESSION["register_students"]["sms_text"] = $text;
				sendsms( $_SESSION["register_students"]["sms_number"], $_SESSION["register_students"]["sms_text"] );
				$_SESSION["register_students"][ "student_id" ] = $student_id;
				unset($_SESSION["register_students"]["err"]);
				$_SESSION["register_students"]["msg"]='We have sent you an sms with a verification code, Please enter the code in the field';//.$text;
				header('Location: register.php');
				die;
			}
			else{
				$_SESSION["register_students"]["err"]='The Mobile Number or ID is not correct. Make sure Student ID is correct and your mobile number is in this format. 923331111111 ';
			}
		}
		else{
			$_SESSION["register_students"]["err"]='Enter your mobile number.';
		}
	}
	else if( !isset( $_SESSION["register_students"][ "mobile_number_confirmed" ] ) ) {
		$verification_code=slash($_POST["verification_code"]);
		$_SESSION["register_students"]["verification_code"]=$verification_code;
		if( $verification_code == $_SESSION["register_students"]["code"] ) {
			unset( $_SESSION["register_students"]["code"] );
			$_SESSION["register_students"]["msg"]='Verification Code is verified successfully. Setup password to get registered.';
			$_SESSION["register_students"][ "mobile_number_confirmed" ] = true;
			header('Location: register.php');
			die;
		}
		else{
			$_SESSION["register_students"]["err"]='Verification code is incorrect.';
		}
	}
	else{
		extract($_POST);
		$msg = '';
		if( strlen($password) < 6 ) {
			$msg .= 'Password must be atleast 6 characters long.<br>';
		}
		if( !empty($password) && $password != $cpassword ) {
			$msg .= 'Confirm password did not match.<br>';
		}
		if( $msg == '' ) {
			set_student_meta($student_id, "password", $password);
			
			$_SESSION["register_students"]=array(
				"id" => $student_id,
				"student_id" => $student_id,
				"password" => slash($password),
				//"phone" => slash($mobile_number),
			);
			header('Location: index.php');
			die;
		}
		else{
			$_SESSION["register_students"]["err"]=$msg;
		}
	}
	header('Location: register.php');
	die;
}
$step = 1;
if( isset( $_SESSION["register_students"][ "student_id" ] ) ) {
	$step = 2;
}
if( isset( $_SESSION["register_students"][ "mobile_number_confirmed" ] ) ) {
	$step = 3;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page - Students</title>
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/fonts.googleapis.com.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.min.css" />
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/style.css">
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
							</div>
							<div class="space-6"></div>
                            <div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
                                        	<h1 style="font-size:28px;">Registration - Students Portal</h1>
											<h4 class="header gray lighter bigger">
												If you have a children enrolled in our school. You can register to our students portal. Just enter your Mobile Number registered with us. We will send you a message to with a confirmation code. Enter the code in next step and you are done.
											</h4>
											<div class="space-6"></div>
                                            <?php
											if(isset($_SESSION["register_students"]["err"])){
												?>
												<div class="alert alert-danger"><?php echo $_SESSION["register_students"]["err"];?></div>
												<?php
												
											}
											?>
                                            <?php
											if(isset($_SESSION["register_students"]["msg"])){
												
												?>
												<div class="alert alert-success"><?php echo $_SESSION["register_students"]["msg"];?></div>
												<?php
											}
											?>
											<form name="loginfrm" method="post">
												<fieldset>
                                                	<label class="block clearfix">
                                                        <span>Student ID</span>
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" name="student_id" placeholder="Enter your ID" value="<?php if(isset($_SESSION["register_students"]["student_id"])) echo $_SESSION["register_students"]["student_id"]?>" />
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>
													<label class="block clearfix">
														<span>Mobile Number <?php echo ($step>1)?' <a href="register.php?step=1" style="float: right;text-decoration: underline;font-size: 11px;">Change</a>':""?></span>
                                                        <span class="block input-icon input-icon-right">
															<input type="number" class="form-control" name="phone" placeholder="e.g. 923331111111" value="<?php if(isset($_SESSION["register_students"]["phone"])) echo $_SESSION["register_students"]["phone"]?>"<?php echo ($step>1)?' disabled':""?> />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>
                                                    <?php
                                                    if( $step > 1 ){
														?>
														<label class="block clearfix">
                                                            <span>Verification Code</span>
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="text" class="form-control" name="verification_code" placeholder="" value="<?php if(isset($_SESSION["register_students"]["verification_code"])) echo $_SESSION["register_students"]["verification_code"]?>"<?php echo ($step>2)?' disabled':""?> />
                                                                <i class="ace-icon fa fa-lock"></i>
                                                            </span>
                                                        </label>
														<?php
													}
													?>
                                                    <?php
                                                    if( $step > 2 ){
														?>
														
                                                        <label class="block clearfix">
                                                            <span>Password</span>
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="password" class="form-control" name="password" placeholder="Enter Password" value="<?php if(isset($_SESSION["register_students"]["password"])) echo $_SESSION["register_students"]["password"]?>" />
                                                                <i class="ace-icon fa fa-lock"></i>
                                                            </span>
                                                        </label>
                                                        <label class="block clearfix">
                                                            <span>Confirm Password</span>
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="password" class="form-control" name="cpassword" placeholder="Enter Confirm Password" value="<?php if(isset($_SESSION["register_students"]["cpassword"])) echo $_SESSION["register_students"]["cpassword"]?>" />
                                                                <i class="ace-icon fa fa-lock"></i>
                                                            </span>
                                                        </label>
														<?php
													}
													?>
													<div class="space"></div>
													<div class="clearfix">
														<button type="submit" name="register_submit" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Submit</span>
														</button>
                                                        <?php
                                                        if( $step == 2 ) {
															?>
                                                            <a style="clear:both; display:block; text-align:right; cursor:pointer" href="register.php?resend">
                                                                <i class="ace-icon fa fa-key"></i>
                                                                <span class="bigger-110">Resend Verification Code</span>
                                                            </a>
                                                            <?php
														}
														?>
													</div>
													<div class="space-4"></div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->
										<div class="toolbar clearfix" style="padding: 9px 5px 11px 5px;">
											
												<a href="login.php" class="pull-left forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													Back to Login
												</a>
											
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->	
							</div><!-- /.position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
		<!-- basic scripts -->
		<!--[if !IE]> -->
		<script src="../assets/js/jquery-2.1.4.min.js"></script>
		<!-- <![endif]-->
		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			});
		</script>
	</body>
</html>
