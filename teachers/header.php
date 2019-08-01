<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo $site_title?> Admin Panel</title>
		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<!-- page specific plugin styles -->
		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/fonts.googleapis.com.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="../assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="../assets/css/bootstrap-datepicker3.min.css" />
        <link rel="stylesheet" href="../assets/css/bootstrap-datetimepicker.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" href="../assets/css/jquery-ui.min.css" />
        <link type="text/css" rel="stylesheet" href="../assets/js/fancybox/jquery.fancybox.css" />
      	<!--  <link rel='stylesheet' type='text/css' href='css/invoicestyle.css' />
		<link rel='stylesheet' type='text/css' href='css/print.css' media="print" />-->
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
		<!-- inline styles related to this page -->
		<!-- ace settings handler -->
        <script src="../assets/js/jquery-2.1.4.min.js"></script>
        <script src="../assets/js/ace-extra.min.js"></script>
        <script type="text/javascript" src="../js/js.js"></script>
        <script type="text/javascript" src='../js/tinymce/tinymce.js'></script>
		<?php include("../js/initialize.php");?>
        <script type="text/javascript" src="../js/popup.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../js/cropit.js"></script>
        <script src="../assets/js/moment.min.js"></script>
        <script src="../assets/js/jquery-ui.min.js"></script>
        <script src="../assets/js/ace-elements.min.js"></script>
		<script src="../assets/js/ace.min.js"></script>
		<script src="../assets/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="../assets/js/fancybox/jquery.fancybox.pack.js"></script>
        <link href="../js/chosen/chosen.css" type="text/css" rel="stylesheet" />
		<script src="../js/chosen/chosen.jquery.js"></script>
        <script type="text/javascript" src="../js/angular.min.js"></script>
		<script type="text/javascript" src="../js/angular-animate.js"></script>
		<script src="../assets/js/moment.min.js"></script>
		<script src="../js/angular-moment.min.js"></script>
		<script type="text/javascript" src="../js/attendance.angular.js"></script>
		<script type="text/javascript" src="../js/notification.angular.js"></script>
        <script type="text/javascript">
			$(function() {
				$( ".datepicker" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
					//isRTL:true,
					changeMonth: true,
					changeYear: true,
					dateFormat: 'dd/mm/yy'
				});
				$('.date-timepicker').datetimepicker({
					"format": 'DD/MM/YYYY hh:mm A'
				})
				$('.date-picker').datetimepicker({
					"format": 'DD/MM/YYYY'
				})
			$(".fancybox.iframe").fancybox({
				type: "iframe",
			});
			$(".fancybox.inline").fancybox({
				type: "inline",
			});
			if($(".select_multiple").length>0) $(".select_multiple").chosen();
        	$('.image-editor').each(function(){
				var image_editor = $(this);
				image_editor.cropit({
					imageState: {
						src: image_editor.data( 'src' ),
					},
				});
			});
			$('.rotate-cw').click(function() {
				var image_editor = $(this).parents( '.image-editor' );
				image_editor.cropit('rotateCW');
			});
			$('.rotate-ccw').click(function() {
				var image_editor = $(this).parents( '.image-editor' );
				image_editor.cropit('rotateCCW');
			});
			$('.image-editor-done').click(function() {
				var image_editor = $(this).parents( '.image-editor' );
				image_editor.find( 'input[type=file]' ).val( '' );
				var imageData = image_editor.cropit('export');
				var data = image_editor.data( "extra_fields" );
				data.img = imageData;
				$.post( image_editor.data( "url" ), data, function(){
					
				});
				$( "#"+image_editor.data( "img" ) ).attr( "src", imageData );
				$.fancybox.close();
			});
			});
        </script>
        <style>
			a.image-editor-src {
				position: relative;
				display: block;
				width:100%;
				max-width: 120px;
				margin:0 auto;
			}
			
			a.image-editor-src:before {
				display: block;
				content: "";
				padding-top: 100%;
			}
			
			.image-editor-src > img {
				width: 100%;
				height: 100%;
				position: absolute;
				top: 0;
				left: 0;
				object-fit: cover;
				display: block;
			}
			.cropit-preview {
				background-color: #f8f8f8;
				background-size: cover;
				border: 1px solid #ccc;
				border-radius: 3px;
				margin-top: 7px;
				width: 250px;
				height: 250px;
			  }
			  .cropit-preview-image-container {
				cursor: move;
			  }
			  .image-size-label {
				margin-top: 10px;
			  }
			  .image-editor button {
				margin-top: 10px;
			  }
		</style>
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default navbar-fixed-top ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="index.php" class="navbar-brand">
						<?php $admin_logo=get_config("admin_logo"); if(empty($admin_logo) || 1) echo $site_title; else { ?><img src="<?php echo $file_upload_url;?>config/<?php echo $admin_logo?>" /><?php }?>
					</a>
				</div>
                <div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="../assets/images/avatars/profileimg-default.png" />
								<span class="user-info"> <small>Welcome,</small> <?php echo ucfirst((isset($_SESSION["logged_in_teachers"]) && $_SESSION["logged_in_teachers"]!="")?$_SESSION["logged_in_teachers"]["name"]:"Guest");?></span>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>
							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="profile.php?id=<?php echo $_SESSION["logged_in_teachers"]["id"]?>">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>
                                <li class="divider"></li>
								<?php if(isset($_SESSION["logged_in_teachers"])){?>
                                <li>
                                    <a href="logout.php">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        Logout
                                    </a>
                                </li>
                                <?php }?>
                                <?php if(!isset($_SESSION["logged_in_teachers"])){?>
                                <div class="pull-right">
                                    <ul class="login-menu">
                                        <li>
                                            <a href="login.php">
                                                Login
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <?php }?>
							</ul>
						</li>
					</ul>
				</div>
                
			</div><!-- /.navbar-container -->
		</div>
        <div class="main-container ace-save-state" id="main-container">
        	<div class="main-content">
                <div class="page-header page-header-hidden" style="padding:0px;">
                    <?php
                    if(isset($_REQUEST["msg"])){
                        ?>
                        <div align="center" class="alert-success"><?php echo url_decode($_REQUEST["msg"]);?></div>	
                        <?php
                    }
                    if(isset($_REQUEST["err"])){
                        ?>
                        <div align="center" class="alert-danger"><?php echo url_decode($_REQUEST["err"])?></div>	
                        <?php
                    }
                    ?>
                </div>