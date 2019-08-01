<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="main-content">
	<div class="main-content-inner class-section">
            <div class="breadcrum`bs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <a href="<?php echo $site_url?>/students">Home</a>
                    </li>
                    <li class="active">Students Portal</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        <?php echo $_SESSION["logged_in_students"]["student_name"]?>
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                            Welcome to <?php echo $site_title?>.
                        </small> 
                    </h1>
                </div><!-- /.page-header -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="space-6"></div>
                            <div class="col-md-12 col-xs-12">
                                 <div class="clearfix bottom-30">
                                     <div class="row">
                                            <a class="col-md-2 col-xs-6 marksheet_generate" href="index.php?tab=result" target="_blank">
                                                <div class="center infobox-blue infobox-dark">
                                                    <div class="infobox-chart">
                                                        <span class="project-icon"><img title="" alt="Student" src="placeholder.jpg" style="width:50px;"></span>
                                                    </div>
                                                    <div class="infobox-data">
                                                        <div class="infobox-content">Result</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="col-md-2 col-xs-6" href="index.php?tab=attendance&id=<?php echo $_SESSION["logged_in_students"]["id"]?>">
                                                <div class="center infobox-blue infobox-dark">
                                                    <div class="infobox-chart">
                                                        <span class="project-icon"><img title="" alt="Student" src="placeholder.jpg" style="width:50px;"></span>
                                                    </div>
                                                    <div class="infobox-data">
                                                        <div class="infobox-content">Attendance</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="col-md-2 col-xs-6" href="index.php?tab=student_record&id=<?php echo $_SESSION["logged_in_students"]["id"]?>">
                                                <div class="center infobox-blue infobox-dark">
                                                    <div class="infobox-chart">
                                                        <span class="project-icon"><img title="" alt="Student" src="placeholder.jpg" style="width:50px;"></span>
                                                    </div>
                                                    <div class="infobox-data">
                                                        <div class="infobox-content">Student Record</div>
                                                    </div>
                                                </div>
                                            </a>                                            
                                            <div class="col-md-6">
                                                <div class="page-header">
                                                    <h1><!--<small class="pull-right">45 Notifications</small>--> Notifications </h1>
                                                </div>
                                                <?php
                                        		$notifications = doquery("select a.* from notification a left join notification_2_student b on a.id = b.notification_id where b.student_id = '".$_SESSION["logged_in_students"]["id"]."'",$dblink);
                                        		if( numrows( $notifications ) > 0 ) {
                                            		?>
                                                    <div class="comments-list">
                                                    <?php 
                                                    while( $notification = dofetch( $notifications ) ) {
                                                        ?>
                                                       <div class="media">
                                                           <p class="pull-right"><small><?php echo date_convert($notification["date"])?></small></p>
                                                            <div class="media-body">
                                                              <h4 class="media-heading user_name"><?php echo unslash($notification["title"])?></h4>
                                                              <?php echo unslash($notification["text"])?>
                                                            </div>
                                                       </div>
                                                       <?php 
                                                    }
                                                    ?>
                                                    </div>
                                                	<?php 
                                        		}
												else{
													echo "No Notifications";
												}
                                        		?>
                                            </div>
                                            	
                                 </div>	
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
</div>