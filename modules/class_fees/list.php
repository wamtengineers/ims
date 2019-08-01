<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Class Fees
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Bulk Update Fees of All Classes
            </small>
        </h1>
    </div>
    <?php
    $classes = array();
	$rs = doquery( "select id, class_name from class where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by id", $dblink );
	if( numrows( $rs ) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			$classes[] = array(
				"id" => $r[ "id" ],
				"title" =>  unslash( $r[ "class_name" ] )
			);
		}
	}
	?>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                            <form action="class_fees_manage.php?tab=list" method="post">
                                <table id="dynamic-table" class="table list table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">S.No</th>
                                            <th width="15%">Fees / Class</th>
                                            <?php foreach($classes as $class){
                                                ?>
                                                <th><?php echo $class[ "title" ]?></th>
                                                <?php
                                            }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql="select * from fees where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by sortorder";
                                        $rs=doquery($sql, $dblink);
                                        if(numrows($rs)>0){
                                            $sn=1;
                                            while($r=dofetch($rs)){
                                                ?>
                                                <tr>
                                                    <td><?php echo $sn;?></td>
                                                    <td><?php echo unslash( $r[ "title" ] );?></td>
                                                    <?php foreach($classes as $class){
                                                        $fees = doquery( "select fees from class_fees where class_id='".$class[ "id" ]."' and fees_id='".$r[ "id" ]."'", $dblink );  
                                                        if( numrows( $fees ) > 0 ) {
                                                            $fees = dofetch( $fees );
                                                            $fees = round($fees[ "fees" ]);
                                                        }
                                                        else {
                                                            $fees = 0;
                                                        }
                                                        ?>
                                                        <td style="padding-left:2px; padding-right:2px;"><input type="text" name="class_fee[class_<?php echo $class[ "id" ]?>][fees_<?php echo $r[ "id" ]?>]" value="<?php echo $fees?>" style="width: 100%; padding:2px; margin:0;" /></td>
                                                        <?php
                                                    }?>
                                                </tr>  
                                                <?php 
                                                $sn++;
                                            }
                                            ?>
                                            <tr>
                                                <td colspan="20">
                                                    <button class="btn btn-info" type="submit" name="class_fees_edit" title="Update Record">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Save Fees
                                                    </button>
                                                    <button class="btn btn-success" type="submit" name="class_fees_edit_update_student" title="Update Record">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Apply to All Students
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        else{	
                                            ?>
                                            <tr>
                                                <td colspan="5"  class="no-record">No Result Found</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>