<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<style>
.infobox-chart .project-icon > img{
	top:5%;
}
</style>
<div class="main-content">
	<div class="main-content-inner class-section">
            <div class="breadcrum`bs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <a href="<?php echo $site_url?>/Parents">Home</a>
                    </li>
                    <li class="active">Parents Portal</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        Student
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
                            	$res=doquery("select a.id, gender,student_name,father_name from student a inner join student_meta b on a.id = b.student_id where meta_value='".$_SESSION["logged_in_parents"]["mobile_number"]."' and meta_key='phone' order by a.id",$dblink);
									if(numrows($res)>0){
                                    ?>
                                     <div class="clearfix bottom-30">
                                         <div class="row">
                                            <?php
                                            while($rec=dofetch($res)){
												$student_image = get_student_meta($rec['id'], "student_image");
                                                ?>
                                                <a class="col-md-2 col-xs-6" href="index.php?tab=student_profile&id=<?php echo $rec["id"]?>">
                                                    <div class="center infobox-blue infobox-dark">
                                                    	<div class="infobox-chart">
                                                            <span class="project-icon"><img title="" alt="Student" src="<?php echo !empty($student_image)?$file_upload_url."student/".$student_image:"placeholder.jpg"?>" style="width:50px;"></span>
                                                        </div>
                                                        <div class="infobox-data">
                                                            <div class="infobox-content"><?php echo $rec["student_name"]." - ".$rec["father_name"];?></div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <a class="col-md-2 col-xs-6" href="feedback_manage.php">
                                                <div class="center infobox-blue infobox-dark">
                                                    <div class="infobox-chart">
                                                        <span class="project-icon"><i class="ace-icon fa fa-comment" style="font-size:36px"></i></span>
                                                    </div>
                                                    <div class="infobox-data">
                                                        <div class="infobox-content">Feedback</div>
                                                    </div>
                                                </div>
                                            </a>
                                         </div>
                                     </div>	
                                    <?php
                    				}
            				?>
                           
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
</div>