<?php 
include("../inc/db.php");
include("../inc/utility.php");
if( !isset( $_SESSION[ "session_teacher_school_id" ] ) ){
	header( "Location: school.php" );
	die;
}
if(isset($_POST["login_submit"])){
	$loginname=slash($_POST["username"]);
	$password=slash($_POST["password"]);
	$msg="";
	if(strlen($loginname)<1) $msg.="Enter Your Username <br />";
	if(strlen($password)<1) $msg.="Enter the Password <br />";
	if($msg==""){
		$qr="select * from employee where username='".$loginname."' and password='".$password."' and status=1";
		$res=doquery($qr, $dblink);
		if(numrows($res)>0){
	 		$row=dofetch($res);
	 		$_SESSION["logged_in_teachers"]=$row;
			if(isset($_POST["remmeber_me"])){
				setcookie('_teachers_logged_in', $row["id"], strtotime('+14 days'));
			}
			header('Location: index.php');
			die;
	 	}
		$_SESSION["logged_in_teachers"]["err"]='Invalid Username / Password';
	}
	else{
		$_SESSION["logged_in_teachers"]["err"]=$msg;
	}	
	if(isset($_POST["remmeber_me"])) $_SESSION["logged_in_teachers"]["remmeber_me"]=$_POST["remmeber_me"]; else unset($_SESSION["logged_in_teachers"]["remmeber_me"]);
	header('Location: login.php');
	die;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page - Teachers</title>
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
                            <?php
							if(isset($_SESSION["logged_in_teachers"]["err"])){
								?>
								<div class="inputError"><?php echo $_SESSION["logged_in_teachers"]["err"];?></div>
								<?php
								
							}
							?>
							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
                                        	<h1 style="font-size:28px;">Teachers</h1>
											<h4 class="header gray lighter bigger">
												Please Enter Your Username And Password 
											</h4>
											<div class="space-6"></div>
											<form name="loginfrm" method="post">
												<fieldset>
                                                	
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="username" placeholder="Username" value="<?php if(isset($_SESSION["logged_in_teachers"]["username"])) echo $_SESSION["logged_in_teachers"]["username"]?>" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" name="password" placeholder="Password" value="<?php if(isset($_SESSION["logged_in_teachers"]["password"])) echo $_SESSION["logged_in_teachers"]["password"]?>" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>
													<div class="space"></div>
													<div class="clearfix">
														<label class="inline">
															<input type="checkbox" class="ace" name="remmeber_me" <?php if(isset($_SESSION["logged_in_teachers"]["remmeber_me"])) echo ' checked';?> />
															<span class="lbl"> Remember Me</span>
														</label>
														<button type="submit" name="login_submit" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>
														</button>
													</div>
													<div class="space-4"></div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->
										<div class="toolbar clearfix" style="padding: 9px 5px 11px 5px;">
											
												<a href="#" data-target="#forgot-box" class="pull-left forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
                                                <a href="school.php" class="pull-right forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													Select School
												</a>
											
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
                                        	<h1 style="font-size:28px;">Teachers</h1>
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>
											<div class="space-6"></div>
											<p>
												Enter your email and to receive instructions
											</p>
											<form name="loginfrm" action="login.php" method="post">
												<fieldset>
                                                	<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="name" class="form-control" placeholder="Name" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" name="username" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>
													<div class="clearfix">
														<button type="submit" name="login_submit" class="pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">RESET PASSWORD</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->
										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->
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
