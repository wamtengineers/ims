<?php
if(!defined("APP_START")) die("No Direct Access");
include("report_do.php");
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
				if( !empty( $from ) ){
					echo " from ".$from;
				}
				if( !empty( $to ) ){
					echo " to ".$to;
				}
                ?>
            </p>
        </th>
    </tr>
    <tr>
        <th width="5%" align="center">S.No</th>
        <th width="40%">Student</th>
        <th width="20%" align="right">Total Days</th>
        <th width="20%" align="right">Present Days</th>
        <th width="15%" align="right">Absent Days</th>
    </tr>
    <?php
	$start_ts = strtotime( date_dbconvert( $from ) );
	$end_ts = strtotime( date_dbconvert( $to ) );
	$one_day = 24*3600;
	$total_days = 0;
	$total_present = 0;
	$sn=1;
	for( $i = $start_ts; $i <= $end_ts; $i += $one_day ) {
		if( !is_holiday( date("Y-m-d", $i) ) ) {
			$total_days++;
		}
	}
	if(numrows($rs)>0){
		$sn=1;
		$total_present = 0;
		while($r=dofetch($rs)){             
			?>
			<tr>
				<td align="center"><?php echo $sn;?></td>
				<td><?php echo unslash($r["student_name"]); ?></td>
				<td align="right"><?php echo $total_days?></td>
				<td align="right">
					<?php
					$count = dofetch( doquery( "select count(1) from student_attendance where student_id = '".$r[ "id" ]."' and date >= '".date_dbconvert( $from )."' and date <= '".date_dbconvert( $to )."'", $dblink ) );
					echo $count[ "count(1)" ];
					?>
				</td>
				<td align="right">
					<?php
						echo $total_days-$count[ "count(1)" ];
					?>
				</td>
			</tr>  
			<?php 
			$sn++;
		}
	}
	else{	
		?>
		<tr>
			<td colspan="5"  class="no-record">No Result Found</td>
		</tr>
		<?php
	}
	?>
</table>
<?php
die;
//}