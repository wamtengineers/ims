<?php
if(!defined("APP_START")) die("No Direct Access");
$monthly_fees_id = get_config( "monthly_fees_id" );
$rs = doquery( $sql, $dblink );
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
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="9">
    	<?php echo get_config( 'fees_chalan_header' )?>
    	<h2>Student Lists</h2>
        <p>
        	<?php
			echo "List of";
            if( !empty( $gender ) ){
				echo $gender=='male'?" male":' female';
			}
			echo " students(".($student_status=='1'?"present":"left").") of";
			if( !empty( $year_id ) ){
				$academic_year = dofetch( doquery( "select * from academic_year where id = '".$year_id."'", $dblink ));
				echo " Year: ".$academic_year[ "title" ];
			}
			else {
				echo " All years";
			}
			if( !empty( $class_section_id ) ){
				echo " Class: ".get_field( get_field( $class_section_id, "class_section", "class_id" ), "class", "class_name" ).'-'.get_field( $class_section_id, "class_section");
			}
			if( !empty( $house_id ) ){
				echo " House: ".get_field( $house_id, "houses");
			}
			if( !empty( $admission_from ) || !empty( $admission_to ) ){
				echo "<br />Admission";
			}
			if( !empty( $admission_from ) ){
				echo " from ".$admission_from;
			}
			if( !empty( $admission_to ) ){
				echo " to ".$admission_to;
			}
			?>
        </p>
    </th>
</tr>
<tr>
	<th width="3%">SN</th>
    <th width="3%">SID</th>
    <th width="5%">G.R. No.</th>
    <th width="20">Name</th>
    <th width="20%">Father Name</th>
    <th width="12%">Surname</th>
    <th width="5%">Class</th>
    <th width="10%">Gender</th>
    <th width="10%">Age<?php if( !empty( $year_id ) ){ echo " as on ".date_convert( $academic_year[ "start_date" ] ); }?></th>
    <th>Contact Number</th>
    <th width="10%">Tuition Fees</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	$monthly_fees = 0;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
        	<td><?php echo $sn++?></td>
            <td align="right"><?php echo unslash( $r[ "id" ] )?></td>
            <td align="right"><?php echo get_student_meta( $r[ "id" ], 'gr_no' )?></td>
            <td><?php echo unslash( $r[ "student_name" ] )?></td>
            <td><?php echo unslash( $r[ "father_name" ] )?></td>
            <td><?php echo unslash( $r[ "surname" ] )?></td>
            <td><?php echo get_student_class($r["id"]); ?></td>
            <td><?php echo $r[ "gender" ]=='male'?'Male':'Female'?></td>
            <td><?php
				$bd = strtotime( $r[ "birth_date" ] );
				if( !empty( $year_id ) ){
					$to = strtotime( $academic_year[ "start_date" ] );
				}
				else{
					$to = time();
				}
				$month = 12-date( "n", $bd );
				$month += date( "n", $to )-1;
				$year = date( "Y", $to ) - date( "Y", $bd ) -1 ;
				if( $month >= 12 ) {
					$month = $month-12;
					$year++;
				}
				$age = array();
				if( $year > 0 ){
					$age[] = $year.'Y';		
				}
				if( $month > 0 ) {
					$age[] = $month.'M';
				}
				echo implode( " ", $age );
			?></td>
            <td align="right"><?php echo get_student_meta( $r[ "id" ], "phone" )?></td>
            <td align="right"><?php $fees = (int)get_student_meta($r["id"], "fees_".$monthly_fees_id."_approved"); echo curr_format( $fees ); $monthly_fees += $fees;?></td>
        </tr>
		<?php
	}
	?>
		<tr>
        	<th colspan="9" align="right">Total</th>
            <th align="right"><?php echo curr_format( $monthly_fees )?></th>
        </tr>
	<?php
}
?>
</table>
<?php
die;