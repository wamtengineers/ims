<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo $site_title?> Admin Panel</title>
		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
        <link href="css/general.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
        <link rel="stylesheet" href="assets/css/jquery-ui.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.min.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link type="text/css" rel="stylesheet" href="assets/js/fancybox/jquery.fancybox.css" />
		<script src="assets/js/ace-extra.min.js"></script>
		<script src="assets/js/jquery-2.1.4.min.js"></script>
        <script src="js/jquery.scannerdetection.js"></script>
        <script type="text/javascript" src='js/tinymce/tinymce.js'></script>
        <?php include("js/initialize.php");?>
		<script type="text/javascript" src="js/popup.js"></script>
	</head>
	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default navbar-fixed-top ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="navbar-header pull-left">
					<a href="index.php" class="navbar-brand">
						<small><i class="fa fa-leaf"></i> <?php $admin_logo=get_config("admin_logo"); if(empty($admin_logo) || 1) echo $site_title; else { ?><img src="<?php echo $file_upload_root;?>config/<?php echo $admin_logo?>" /><?php }?></small>
					</a>
				</div>
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="assets/images/avatars/profileimg-default.png" alt="Jason's Photo" />
								<span class="user-info"> <small>Welcome,</small> <?php echo ucfirst((isset($_SESSION["logged_in_admin"]) && $_SESSION["logged_in_admin"]!="")?$_SESSION["logged_in_admin"]["name"]:"Guest");?></span>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>
							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="config_manage.php">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>
								<li>
									<a href="admin_manage.php?tab=edit&id=<?php echo $_SESSION["logged_in_admin"]["id"]?>">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>
                                <?php
                                if( $_SESSION[ "logged_in_admin" ][ "school_id" ] == 0 ) {
									?>
									<li class="divider"></li>
                                    <?php
									$school=doquery("Select * from school where status = 1 order by sortorder",$dblink);
									if( numrows( $school ) > 0 ){
										while( $sch = dofetch( $school ) ){
										?>
										<li>
                                            <a href="school.php?id=<?php echo $sch["id"]?>">
                                                <i class="ace-icon fa fa-university"></i>
                                                <?php echo unslash( $sch[ "title" ] );?>
                                            </a>
                                        </li>
										<?php
										}
									}
								}
								?>
                                <li class="divider"></li>
								<li>
									<a href="logout.php">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>
				<ul class="nav nav-list">
                	<?php
					$parents=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id where parent_id=0 and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' order by sortorder",$dblink);
					if(numrows($parents)>0){
						while($parent=dofetch($parents)){
							?>
                            <li>
                                <a class="dropdown-toggle" href="<?php echo unslash($parent["url"])?>">
                                <i class="menu-icon fa fa-<?php echo unslash($parent["small_icon"])?>"></i>
                                <span class="menu-text"> <?php echo unslash($parent["title"])?> </span>
                                <?php
								$submenus=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id where parent_id='".$parent["id"]."' and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' order by sortorder",$dblink);
								if(numrows($submenus)>0){
									?>
                                    <b class="arrow fa fa-angle-down"></b></a>
                                    <ul class="submenu">
                                        <?php
                                        while($submenu=dofetch($submenus)){
                                            ?>
                                            <li class="">
                                                <a href="<?php echo unslash($submenu["url"])?>">
                                                    <i class="menu-icon fa fa-<?php echo unslash($submenu["small_icon"])?>"></i>
                                                    <?php echo unslash($submenu["title"])?>
                                                </a>
                                                <b class="arrow"></b>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                	<?php
                            	}
								else{
									echo "</a>";
								}
								?>
                            </li>
                            <?php
						}
					}
					?>
				</ul><!-- /.nav-list -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
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