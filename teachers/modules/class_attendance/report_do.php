<?php
if(!defined("APP_START")) die("No Direct Access");
$current_academic_year = current_academic_year();
$extra = '';
if(isset($_GET["from"])){
	$_SESSION["class_attendance_manage"]["report"]["from"]=slash($_GET["from"]);
}
if(isset($_SESSION["class_attendance_manage"]["report"]["from"])){
	$from=$_SESSION["class_attendance_manage"]["report"]["from"];
} else {
	$from=date("01/m/Y");
}
if(isset($_GET["to"])){
	$_SESSION["class_attendance_manage"]["report"]["to"]=slash($_GET["to"]);
}
if(isset($_SESSION["class_attendance_manage"]["report"]["to"])){
	$to=$_SESSION["class_attendance_manage"]["report"]["to"];
} else {
	$to=date("d/m/Y");
}

$rs = doquery( "select b.* from student_2_class a inner join student b on a.student_id = b.id where class_section_id = '".$record["id"]."' and academic_year_id='".$current_academic_year[ "id" ]."' and a.status=1 order by b.student_name, b.father_name", $dblink );
if( isset( $_GET[ "print" ]  ) ){
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
@media print{
	.no-print, .footer, .page-header{ display:none;}
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
}