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
        <th width="15%">Student</th>
        <th width="8%">Month</th>
        <th width="8%">Issue Date</th>
        <th width="20%">Details</th>
        <th width="8%">Amount</th>
        <th width="8%">Receiving Date</th>
        <th width="8%">Rec. Amount</th>
        <th width="8%">Balance</th>
    </tr>
    <?php
	
    if(numrows($rs)>0){
        $sn=1;
        while($r=dofetch($rs)){
			if( !is_null( $r[ "payment_date" ] ) ) {
				$receiving_date = date_convert( $r[ "payment_date" ] );
				$receiving_amount = $r[ "amount" ];
			}
			else {
				$receiving_date = "--";
				$receiving_amount = "0";
			}
			$amount = 0;
			$chalan_details = get_chalan_details( $r["id"] );
			foreach( $chalan_details[ "details" ] as $chalan_detail ) {
				$amount += $chalan_detail[ "amount" ];
			}
            $total_amount += $amount;
            $total_receiving_amount += $receiving_amount;
            ?>
            <tr>
                <td style="text-align:center"><?php echo $sn++?></td>
                <td>
					<?php 
                        $res=doquery("Select * from student_2_class a left join student b on a.student_id = b.id left join class_section c on a.class_section_id = c.id left join class d on c.class_id = d.id where a.id = '".$r[ "student_2_class_id" ]."'",$dblink);
                        if(numrows($res)>0){
                            $rec=dofetch($res);
                            echo unslash( $rec[ "student_name" ] )." (Class: ".unslash( $rec[ "class_name" ] )."-".unslash( $rec[ "title" ] ).")";
                        }
                        else{
                            echo "Unknown";
                        }
                     ?>
                </td>
				<td><?php echo show_month($r["month"]); ?></td>
				<td><?php echo date_convert($r["issue_date"]); ?></td>
				<td>
					<?php
                        $amount = 0;
                        $chalan_details = get_chalan_details( $r["id"] );
                        foreach( $chalan_details[ "details" ] as $chalan_detail ) {
                            echo $chalan_detail[ "title" ].": ".curr_format( $chalan_detail[ "amount" ] )."<br />";
                            $amount += $chalan_detail[ "amount" ];
                        }
                    ?>
                </td>
				<td style="text-align:right"><?php echo curr_format( $amount );?></td>
				<td><?php echo $receiving_date;?></td>
				<td style="text-align:right"><?php echo curr_format( $receiving_amount );?></td>
				<td style="text-align:right"><?php echo curr_format( $amount - $receiving_amount );?></td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <th colspan="5" style="text-align:right;">Total</th>
        <th style="text-align:right;"><?php echo $total_amount;?></th>
        <th></th>
        <th style="text-align:right;"><?php echo $total_receiving_amount;?></th>
        <th style="text-align:right;"><?php echo curr_format( $total_amount - $total_receiving_amount );?></th>
    </tr>
</table>
<?php
die;
//}