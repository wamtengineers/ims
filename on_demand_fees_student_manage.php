<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
if(isset($_GET["parent_id"])){
	$_SESSION["on_demand_fees_student"]["on_demand_fees_id"]=$_GET["parent_id"];
}
if( isset( $_SESSION["on_demand_fees_student"]["on_demand_fees_id"] ) ) {
	$parent_on_demand_fees_id = $_SESSION["on_demand_fees_student"]["on_demand_fees_id"];
}
else {
	die( 'Select Parent Student' );
}
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/on_demand_fees_student/add_do.php");
	break;
	case 'edit':
		include("modules/on_demand_fees_student/edit_do.php");
	break;
	case 'delete':
		include("modules/on_demand_fees_student/delete_do.php");
	break;
	case 'status':
		include("modules/on_demand_fees_student/status_do.php");
	break;
	case 'bulk_action':
		include("modules/on_demand_fees_student/bulkactions.php");
	break;
}
?>
<!DOCTYPE html>
<html ng-app="store">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?php echo $site_title?> Admin Panel</title>
    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
    <!-- page specific plugin styles -->
    <!-- text fonts -->
    <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
    <!-- ace styles -->
    <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
    <!--[if lte IE 9]>
        <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
    <![endif]-->
    <link rel="stylesheet" href="assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="assets/js/fancybox/jquery.fancybox.css" />
    <!--[if lte IE 9]>
      <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
    <![endif]-->
    <!-- inline styles related to this page -->
    <!-- ace settings handler -->
    
    <script src="assets/js/ace-extra.min.js"></script>

    
    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    
</head>
<body class="no-skin">
<?php //include("inc/header.php");?>
<div class="main-container ace-save-state" id="main-container">
	<script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>
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
        <div class="main-content-inner">
          <?php
            switch($tab){
                case 'list':
                    include("modules/on_demand_fees_student/list.php");
                break;
                case 'add':
                    include("modules/on_demand_fees_student/add.php");
                break;
                case 'edit':
                    include("modules/on_demand_fees_student/edit.php");
                break;
            }
          ?>
        </div>
  	</div>
</div>	
<?php include("inc/footer.php");?>