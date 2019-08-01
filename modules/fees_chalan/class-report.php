<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$total_amount = $total_receiving_amount = 0;
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  11px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 5px 5px;
    font-size: 11px;
	vertical-align:top;
}
table table th, table table td{
	padding:3px;
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
            <h2>Fees Chalan List</h2>
            <p>
                <?php
                echo "List of";
				if( $is_received=="0" ){
					echo " Pending";
				}
				if( $is_received==1 ){
					echo " Received";
				}
				if( $is_received == '' ) {
					echo " All";
				}
				echo " Chalans<br>";
                if( !empty( $issue_date_from ) || !empty( $issue_date_to ) ){
					echo "<br />Issue Date";
				}
				if( !empty( $issue_date_from ) ){
					echo " from ".$issue_date_from;
				}
				if( !empty( $issue_date_to ) ){
					echo " to ".$issue_date_to;
				}
				if( !empty( $payment_date_from ) || !empty( $payment_date_to ) ){
					echo "<br />Payment Date";
				}
				if( !empty( $payment_date_from ) ){
					echo " from ".$payment_date_from;
				}
				if( !empty( $payment_date_to ) ){
					echo " to ".$payment_date_to;
				}
                if( !empty( $class_section_id ) ){
                    echo " Class: ".get_field( get_field( $class_section_id, "class_section", "class_id" ), "class", "class_name" ).'-'.get_field( $class_section_id, "class_section", "title" );
                }
				if( !empty( $student_2_class_id ) ){
                    echo " Student: ".get_field( get_field( $student_2_class_id, "student_2_class", "student_id" ), "student", "student_name" ).'-'.get_field( get_field( $student_2_class_id, "student_2_class", "class_section_id" ), "class", "class_name" ).'-'.get_field( get_field( $student_2_class_id, "student_2_class", "class_section_id" ), "class_section", "title" );
                }
                ?>
            </p>
        </th>
    </tr>
    <tr>
        <th width="5%" style="text-align:center">S#</th>
        <th>Class</th>
        <th width="10%">Amount</th>
        <th width="10%">Rec. Amount</th>
        <th width="10%">Balance</th>
    </tr>
    <?php
	$classes = array();
    if(numrows($rs)>0){
        $sn=1;
        while($r=dofetch($rs)){
			if( !is_null( $r[ "payment_date" ] ) ) {
				$receiving_amount = $r[ "amount" ];
			}
			else {
				$receiving_amount = "0";
			}
			$amount = 0;
			$chalan_details = get_chalan_details( $r["id"] );
			foreach( $chalan_details[ "details" ] as $chalan_detail ) {
				$amount += $chalan_detail[ "amount" ];
			}
			$class_section_id = get_field( $r[ "student_2_class_id" ], 'student_2_class', 'class_section_id' );
            if( !isset( $classes[ $class_section_id ] ) ) {
				$title = get_field( get_field( $class_section_id, 'class_section', 'class_id' ), 'class', 'class_name' )." ".get_field( $class_section_id, 'class_section' );
				$classes[ $class_section_id ] = array(
					'title' => $title,
					"amount" => 0,
					"receiving_amount" => 0
				);
			}
			$classes[ $class_section_id ][ "amount" ] += $amount;
			$classes[ $class_section_id ][ "receiving_amount" ] += $receiving_amount;
			$total_amount += $amount;
            $total_receiving_amount += $receiving_amount;
        }
    }
	if( count( $classes ) > 0 ) {
		$sn = 1;
		foreach( $classes as $class ) {
			?>
			<tr>
            	<td><?php echo $sn++?></td>
                <td><?php echo $class[ "title" ]?></td>
                <td style="text-align:right;"><?php echo curr_format( $class[ "amount" ] )?></td>
                <td style="text-align:right;"><?php echo curr_format( $class[ "receiving_amount" ] )?></td>
                <td style="text-align:right;"><?php echo curr_format( $class[ "amount" ]-$class[ "receiving_amount" ] )?></td>
            </tr>
			<?php
		}
		?>
		<tr>
            <th colspan="2" style="text-align:right;">Total</th>
            <th style="text-align:right;"><?php echo curr_format( $total_amount );?></th>
            <th style="text-align:right;"><?php echo curr_format( $total_receiving_amount );?></th>
            <th style="text-align:right;"><?php echo curr_format( $total_amount - $total_receiving_amount );?></th>
        </tr>
		<?php
	}
    ?>
</table>
<?php
die;
//}