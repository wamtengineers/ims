<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$amount = 0;
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
    	<h2>Salary Payment List</h2>
        <p>
        	<?php
			echo "List of";
			if( !empty( $date_from ) || !empty( $date_to ) ){
				echo "<br />Date";
			}
			if( !empty( $date_from ) ){
				echo " from ".$date_from;
			}
			if( !empty( $date_to ) ){
				echo " to ".$date_to;
			}
			if( !empty( $employee_id ) ){
				echo " Employee: ".get_field($employee_id, "employee","name");
			}
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th width="20%">Employee Name</th>
    <th width="20%">Datetime</th>
    <th align="right" width="10%">Amount</th>
    <th width="20%">Paid By</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
		$amount += $r["amount"];
		?>
		<tr>
        	<td align="center"><?php echo $sn++?></td>
            <td><?php echo get_field($r["employee_id"], "employee","name");?></td>
            <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
            <td align="right"><?php echo curr_format(unslash($r["amount"])); ?></td>
            <td><?php echo get_field( unslash($r["account_id"]), "account", "title" ); ?></td>
        </tr>
		<?php
	}
}
?>
<tr>
    <th colspan="3" style="text-align:right;">Total</th>
    <th style="text-align:right;"><?php echo curr_format($amount);?></th>
    <th></th>
</tr>
</table>
<?php
die;