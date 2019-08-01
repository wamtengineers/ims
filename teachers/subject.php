<?php 
include("../inc/db.php");
include("../inc/utility.php");
include("session.php");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$res=doquery("select distinct(class_section_id), b.title as section, c.class_name from subject_teachers a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id where employee_id = '".$_SESSION[ "logged_in_teachers" ][ "id" ]."' and class_section_id='".slash($_GET[ "id" ])."'",$dblink);
	if(numrows($res)>0){
		$class = dofetch($res);	
	}
}
if( !isset( $class ) ){
	header("Location: index.php"); die;
}
?>
<?php include("header.php");?>
<div class="main-content">
	<div class="main-content-inner class-section">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <a href="<?php echo $site_url?>/teachers">Home</a>
                    </li>
                    <li class="active">Subjects</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        Class Subjects
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                            Class: <?php echo $class["class_name"]." - ".$class["section"];?>
                        </small> 
                    </h1>
                </div><!-- /.page-header -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="space-6"></div>
                            <div class="col-md-6 col-xs-12">
                            <?php
							$is_class_teacher = false;
							$res=doquery("select a.*, b.title from subject_teachers a left join subject b on a.subject_id = b.id where employee_id = '".$_SESSION[ "logged_in_teachers" ][ "id" ]."' and class_section_id='".slash($_GET[ "id" ])."'",$dblink);
								if(numrows($res)>0){
									?>
								 	<div class="clearfix bottom-30">
									 	<div class="row">
											<?php
											while($rec=dofetch($res)){
												if( $rec[ "is_class_teacher" ] ) {
													$is_class_teacher = $rec[ "id" ];
												}
											?>
											<a class="col-md-4 col-xs-6" href="results.php?id=<?php echo $rec[ "id" ]?>">
												<div class="center infobox-blue infobox-dark">
													<div class="infobox-chart">
														<span class="project-icon"><img title="" alt="Menu Icon" src="../uploads/1/menu/class.png"></span>
													</div>
													<div class="infobox-data">
														<div class="infobox-content"><?php echo unslash( $rec["title"] ); ?></div>
													</div>
												</div>
											</a>
											<?php
										}
										if( $is_class_teacher ) {
											?>
											<a class="col-md-4 col-xs-6" href="examination_result_students_manage.php?id=<?php echo $is_class_teacher?>">
												<div class="center infobox-blue infobox-dark">
													<div class="infobox-chart">
														<span class="project-icon"><img title="" alt="Menu Icon" src="../uploads/1/menu/balance-sheet.png"></span>
													</div>
													<div class="infobox-data">
														<div class="infobox-content">Class Assement</div>
													</div>
												</div>
											</a>
											<?php
										}
										?>
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
<?php include("footer.php");