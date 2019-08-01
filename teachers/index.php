<?php 

include("../inc/db.php");
include("session.php");
include("../inc/utility.php");

?>
<?php include("header.php");?>
<div class="main-content">
	<div class="main-content-inner class-section">
            <div class="breadcrum`bs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <a href="<?php echo $site_url?>/teachers">Home</a>
                    </li>
                    <li class="active">Teacher Class</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        Classes
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
                            <?php
                            	$res=doquery("select distinct(class_section_id), b.title as section, c.class_name from subject_teachers a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id where employee_id = '".$_SESSION[ "logged_in_teachers" ][ "id" ]."' order by c.sortorder, b.title",$dblink);
									if(numrows($res)>0){
                                    ?>
                                     <div class="clearfix bottom-30">
                                         <div class="row">
                                         	<div class="col-sm-8">
                                            	<div class="row clearfix">
													<?php
                                                    while($rec=dofetch($res)){
                                                        ?>
                                                        <a class="col-md-3 col-xs-6" href="subject.php?id=<?php echo $rec["class_section_id"]?>">
                                                            <div class="center infobox-blue infobox-dark">
                                                                <div class="infobox-chart">
                                                                    <span class="project-icon"><img title="" alt="Menu Icon" src="../uploads/1/menu/admin-type.jpg"></span>
                                                                </div>
                                                                <div class="infobox-data">
                                                                    <div class="infobox-content"><?php echo $rec["class_name"]." - ".$rec["section"];?></div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <a class="col-md-3 col-xs-6" href="class_attendance_manage.php">
                                                        <div class="center infobox-blue infobox-dark">
                                                            <div class="infobox-chart">
                                                                <span class="project-icon"><img title="" alt="Menu Icon" src="../uploads/1/menu/admin-type.jpg"></span>
                                                            </div>
                                                            <div class="infobox-data">
                                                                <div class="infobox-content">Class Attendance</div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <a class="col-md-3 col-xs-6" href="notification_manage.php">
                                                        <div class="center infobox-blue infobox-dark">
                                                            <div class="infobox-chart">
                                                                <span class="project-icon"><img title="" alt="Menu Icon" src="../uploads/1/menu/admin-type.jpg"></span>
                                                            </div>
                                                            <div class="infobox-data">
                                                                <div class="infobox-content">Notification</div>
                                                            </div>
                                                        </div>
                                                    </a>
                                         		</div>
                                     		</div>	
                                             <div class="col-sm-4">
                                                 <div class="page-header">
                                                     <h1><!--<small class="pull-right">45 Notifications</small>--> Notifications </h1>
                                                 </div>
                                                 <?php
                                                 $notifications = doquery("select a.* from notification a left join notification_2_employee b on a.id = b.notification_id where b.employee_id = '".$_SESSION["logged_in_teachers"]["id"]."'",$dblink);
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
                                    <?php
                    				}
            				?>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
</div>
<?php include("footer.php");?>