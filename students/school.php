<?php 
include("../inc/db.php");
include("../inc/utility.php");
unset($_SESSION[ "session_student_school_id" ]);
if( isset( $_GET[ "sid" ] ) ) {
	$_SESSION[ "session_student_school_id" ] = $_GET[ "sid" ];
	header( "Location: login.php" );
	die;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page - Student</title>
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
    <style>
    	.school-block{
			width:20%;
		}
		@media (max-width :768px) {
			.school-block{
				width:50%;
			}
		}
    </style>
	<body class="login-layout">
    	<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container" style="width:auto">
							<div class="center"></div>
							<div class="space-6"></div>
							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main clearfix">
                                        	<h1 style="font-size:28px;">Select School</h1>
											<h4 class="header gray lighter bigger">
												Please Enter Your Username And Password 
											</h4>
											<div class="space-6"></div>
                                            <div class="row">
											<?php
                                            foreach( $school_instances as $school_instance_id => $school_instance ) {
												?>
												<a class="col-md-2 col-xs-6 school-block" href="school.php?sid=<?php echo $school_instance_id?>">
													<div class="center infobox-blue infobox-dark">
														<div class="infobox-chart">
															<span class="project-icon"><img title="" alt="Menu Icon" src="../uploads/1/menu/department.png"></span>
														</div>
														<div class="infobox-data">
															<div class="infobox-content" style="font-size:14px;"><?php echo $school_instance?></div>
														</div>
													</div>
												</a>
												<?php
											}
											?>
                                            </div>
										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
							</div><!-- /.position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div>
        
    </body>
</html>