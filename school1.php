<?php 
include("inc/db.php");
include("inc/utility.php");
if(!isset($_SESSION["logged_in_admin"])){
	if(!check_admin_cookie()){
		header("Location: login.php");
		die;
	}	
}
if(isset($_GET["id"]) && $_SESSION["logged_in_admin"]["school_id"]==0){
	$_SESSION["current_school_id"]=slash($_GET["id"]);
	header("Location: index.php");
	die;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $site_title?> - Admin Panel</title>
<link type="text/css" rel="stylesheet" href="css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet" href="css/font-awesome.css" />
<link type="text/css" rel="stylesheet"  href="css/bootstrap.css" />
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<link type="text/css" rel="stylesheet"  href="css/awesome-bootstrap-checkbox.css" />
<link href="css/general.css" type="text/css" rel="stylesheet" />
<link type="text/css" rel="stylesheet"  href="css/style.css" />
<link rel="stylesheet" href="css/style.min.css">
<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src='js/tinymce/tinymce.js'></script>
<?php include("js/initialize.php");?>
<script type="text/javascript" src="js/popup.js"></script>
</head>
<body>
	<div id="wrapper" class="round_corners">		
    	<div id="top" class="clearfix">
            <!--<div class="applogo">
                <a href="index.php" class="logo"><?php $admin_logo=get_config("admin_logo"); if(empty($admin_logo)) echo $site_title; else { ?><img src="<?php echo $file_upload_root;?>config/<?php echo $admin_logo?>" /><?php }?></a>
            </div>-->
            
            <ul class="top-right">
            	<li class="dropdown link">
                	<a class="dropdown-toggle profilebox" data-toggle="dropdown" href="#">
                    	<img alt="img" src="images/profileimg-default.png">
                        <b><?php echo ucfirst((isset($_SESSION["logged_in_admin"]) && $_SESSION["logged_in_admin"]!="")?$_SESSION["logged_in_admin"]["name"]:"Guest");?></b>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-list dropdown-menu-right">
                    	<!--<li class="dropdown-header" role="presentation">Profile</li>
                        <li><a href="admin_manage.php?tab=edit&id=<?php echo $_SESSION["logged_in_admin"]["id"]?>"><i class="fa falist fa-file-o"></i>Edit Profile</a></li>
                        <li><a href="config_manage.php"><i class="fa falist fa-wrench"></i>Settings</a></li>
                        <li class="divider"></li>-->
                        <li><a href="logout.php"><i class="fa falist fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
           </ul>
           <div class="clr"></div>
        </div>
		<div class="content">
        	<div class="page-header">
                <h1 class="title">Branches</h1>
                <ol class="breadcrumb">
                    <li class="active">Welcome to <?php echo $site_title?> Dashboard.</li>
                </ol>
                <?php
                $school=doquery("Select * from school where status = 1 order by sortorder",$dblink);
                if( numrows( $school ) > 0 ){
                    ?>
                    <ul class="menu-boxes clearfix">
                        <?php
                        while( $sch = dofetch( $school ) ){
                            ?>
                            <li class="col-xs-6 col-md-2 col-sm-3">
                                <a href="school.php?id=<?php echo $$sch["id"]?>">
                                    <span class="project-icon"><img width="40px" height="40px" alt="Menu Icon" src="images/pdf.png"></span>
                                    <span><?php echo unslash( $$sch[ "title" ] );?></span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
		</div>
<style>
.menu-boxes a {
    display:  block;
    position: relative;
    max-width: 150px;
    padding: 0;
}

.menu-boxes a:before {
    content:  "";
    padding-top: 100%;
    display: block;
}

span.project-icon {
    position:  absolute;
    top: 0;
    left: 0;
    height:  60%;
    width:  100%;
    display:  block;
}

span.project-icon img {
    position:  absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    margin: 0;
}

.menu-boxes span:last-child {
    position:  absolute;
    bottom: 0;
    width:  100%;
    text-align:  center;
}
</style>
<?php include("inc/footer.php");?>
	