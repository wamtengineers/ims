<?php
include("../../inc/view_do.php");
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  12px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px;
    padding: 5px 7px;
    font-size: 12px;
}

table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
</style>
<div id="for-print">
	<table width="100%" cellspacing="0" cellpadding="0">
        <tr class="head">
            <th colspan="9">
                <?php echo get_config( 'fees_chalan_header' )?>
                <h2>Student Lists</h2>
                <p>
                   <?php
                   	if( !empty( $class_section_id ) ){
						echo " Class: ".get_field( get_field( $class_section_id, "class_section", "class_id" ), "class", "class_name" ).'-'.get_field( $class_section_id, "class_section");
					}
				   ?>
                </p>
            </th>
        </tr>
        <tr>
            <th width="3%" class="center">S.No</th>
            <th width="17%">Student Name</th>
            <?php
            $fees = array();
            foreach( $fields as $field ) {
                if( substr( $field, 0, 5 ) === 'fees_' ) {
                    $fee_id = str_replace( "fees_", "", $field );
                    $fees[ $field ] = dofetch( doquery( "select * from fees where id = '".$fee_id."'", $dblink ));
                    $title = unslash( $fees[ $field ][ "title" ] );
                }
                else {
                    $title = ucwords(str_replace( "_", " ", $field));
                }
                ?>
                <th width="<?php echo 80/count($fields)?>%"><?php echo $title?></th>
                <?php
            }
            ?>
        </tr>
		<?php
        $sn=1;
        ?>
        <?php
        $rs=doquery($sql, $dblink);
        if(numrows($rs)>0){
            while($r=dofetch($rs)){          
                ?>
                <tr>
                    <td align="center"><?php echo $sn;?></td>
                    <td><?php echo unslash($r["student_name"]); echo " - ".unslash($r["father_name"]); ?></td>
                    <?php
                    foreach( $fields as $field ) {
                        if( substr( $field, 0, 5 ) === 'fees_' ) {
                            $field = "fees_".$fees[ $field ][ "id" ].($fees[ $field ][ "has_discount" ]?"_approved":"");
                            $field_value = get_student_meta( $r[ "id" ], $field );
                        }
                        else if( $field == 'balance' ) {
                            $balance = 0;
                            $check = doquery( "select * from student_academic_year_balance where academic_year_id='".$academic_year_id."' and student_id='".$r[ "id" ]."'", $dblink );
                            if( numrows( $check ) > 0 ) {
                                $check = dofetch( $check );
                                $balance = $check[ "balance" ];
                            }
                            $field_value = $balance;
                        }
                        else if( in_array( $field, $student_main_fields ) ) {
                            $field_value = unslash( $r[ $field ] );
                        }
                        else {
                            $field_value = get_student_meta( $r[ "id" ], $field );
                        }
                        ?>
                        <td>
                            <span style="width: 100%" class="<?php echo strpos( $field,"date" ) !== false?"datepicker":""?>"><?php echo strpos( $field,"date" )!== false?date_convert($field_value):$field_value?></span>
                        </td>
                        <?php
                    }
                    ?>
                </tr>  
                <?php 
                $sn++;
            }
        }
        ?>
	</table>
</div>
<?php
die;
                               
                       