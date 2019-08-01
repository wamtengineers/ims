<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Promote All Students
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Academic Year
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="academic_year_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <?php if( isset( $msg ) ) {?>
				<div class="alert alert-success"><?php echo $msg?></div>
			<?php }?>
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="academic_year_manage.php?tab=promote" method="post" enctype="multipart/form-data" name="frmAdd">
            	<input type="hidden" name="id" value="<?php echo $id;?>">
                <div class="form-group promote_classes">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="col-sm-6 control-label no-padding-right"><label class="" for="last_academic_year_id">From Academic Year </label></div>
                            <div class="col-sm-6">
                                <select name="last_academic_year_id" title="Choose Option">
                                	<?php
                                    $rs = doquery( "select * from academic_year where id <>'".$id."' and status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by start_date desc", $dblink );
									if( numrows( $rs ) > 0 ) {
										while( $r = dofetch( $rs ) ) {
											?>
											<option value="<?php echo $r[ "id" ]?>"><?php echo unslash( $r[ "title" ] )?></option>
											<?php
										}
									}
									?>
                          		</select>
                          	</div>
                    	</div>
                  	</div>
                </div>
                <div class="form-group promote_classes">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="col-sm-6 control-label no-padding-right"><label class="" for="last_academic_year_id">To Academic Year </label></div>
                            <div class="col-sm-6"><?php echo get_field( $id, "academic_year" )?></div>
                    	</div>
                  	</div>
                </div>                       
                <?php
				$classes = array();
				$rs=doquery("select a.*, b.class_name from class_section a inner join class b on a.class_id=b.id where a.school_id = '".$_SESSION["current_school_id"]."' order by sortorder, title",$dblink);
				if(numrows($rs)>0){
					while( $r = dofetch( $rs ) ){
						$classes[] = $r;
					}			
				}
				foreach( $classes as $class_loop ) {
				if( !isset($_POST["academic_year_promote_do"]) || in_array($class_loop[ "id" ], $class_section_id_from )  ) {
				?>
                <div class="form-group promote_classes">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="col-sm-3 control-label no-padding-right"><label class="" for="class_section_id_from">From Class </label></div>
                            <div class="col-sm-9">
                                <select name="class_section_id_from[]" title="Choose Option">
                                    <option value="">Select Class Section</option>
                                    <?php
                                    if( count( $classes ) > 0 ){
                                        foreach( $classes as $class ) {
                                        	?>
                                        	<option value="<?php echo $class["id"]?>"<?php echo $class_loop[ "id" ] == $class[ "id" ]?' selected':''?>><?php echo unslash($class["class_name"])." - ".unslash($class["title"])?></option>
                                        	<?php			
                                        }			
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="col-sm-3 control-label no-padding-right"><label class="" for="class_section_id_to">To Class </label></div>
                            <div class="col-sm-9">
                                <select name="class_section_id_to[]" title="Choose Option">
                                    <option value="">Select Class Section</option>
                                    <?php
                                    if( count( $classes ) > 0 ){
                                        foreach( $classes as $class ) {
                                        	?>
                                        	<option value="<?php echo $class["id"]?>"<?php echo $class_loop[ "id" ] == $class[ "id" ]?' selected':''?>><?php echo unslash($class["class_name"])." - ".unslash($class["title"])?></option>
                                        	<?php			
                                        }			
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <a href="#" data-id="<?php echo $sn?>" class="add_list_item" data-container_class="promote_classes">Add</a> - <a href="#" data-id="<?php echo $sn?>" class="delete_list_item" data-container_class="promote_classes">Delete</a>
                        </div>
                    </div>
                </div>
                <?php
				}}
			?>
                <div class="clearfix form-actions">
                	<div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit" name="academic_year_promote_do" title="Update Record">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Promote
                            </button>
                        </div>
                    </div>
                </div>
           	</form>
            
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>
<script>
$(document).ready(function(){
	$(".add_list_item").click(function(e){
		e.preventDefault();
		$this = $(this);
		$container=$this.parents(".promote_classes");
		$new_container = $container.clone(true);
		$new_container.find( 'select' ).val('');
		$new_container.insertAfter($container);
	});
	$(".delete_list_item").click(function(e){
		e.preventDefault();
		$this = $(this);
		if($(".delete_list_item").length>1){
			$container = $this.parents(".promote_classes" );
			$container.remove();
		}
		else{
			alert("There must an item in a list.");
		}
	});
});
</script>