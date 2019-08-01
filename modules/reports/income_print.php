<?php
if(!defined("APP_START")) die("No Direct Access");
$extra=$extra1='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["income"]["date_from"]=$date_from;
}

if(isset($_SESSION["reports"]["income"]["date_from"]))
	$date_from=$_SESSION["reports"]["income"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
	$extra1.=" and payment_date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["income"]["date_to"]=$date_to;
}

if(isset($_SESSION["reports"]["income"]["date_to"]))
	$date_to=$_SESSION["reports"]["income"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and datetime_added<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
	$extra1.=" and payment_date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."'";
}
if( empty( $extra ) ) {
	$extra = ' and 1=0 ';
}
$order_by = "date";
$order = "asc";
$orderby = $order_by." ".$order;
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
            <th colspan="2">
                <h1><?php echo get_config( 'site_title' )?></h1>
                <h2>Income Report</h2>
                <p>
                    <?php
                    if( !empty( $date_from ) || !empty( $date_to ) ){
                        echo "<br />Date";
                    }
                    if( !empty( $date_from ) ){
                        echo " from ".$date_from;
                    }
                    if( !empty( $date_to ) ){
                        echo " to ".$date_to;
                    }
                    ?>
                </p>
            </th>
        </tr>
    	<tr>
            <th align="right">Chalan Receiving</th>
            <th align="right">Rs. <?php
                $rs = dofetch( doquery( "select sum(amount) from fees_chalan_receiving a left join fees_chalan b on a.fees_chalan_id = b.id where a.status=1 and b.school_id = '".$_SESSION["current_school_id"]."' ".$extra1, $dblink ) );
                $revenue = $rs[ "sum(amount)" ];
                echo curr_format( $revenue );
            ?></th>
        </tr>
        <?php
            $total = 0;
            $rs = doquery( "select title, sum(amount) as total from expense a left join expense_category b on a.expense_category_id = b.id where a.status=1 and a.school_id = '".$_SESSION["current_school_id"]."' $extra group by expense_category_id", $dblink );
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    if( $r[ "total" ] > 0 ){
                        $total += $r[ "total" ];
                        ?>
                        <tr>
                            <td align="right"><?php echo unslash( $r[ "title" ] )?></td>
                            <td align="right">Rs. <?php echo curr_format($r[ "total" ])?></td>
                        </tr>	
                        <?php
                    }
                }
            }
            ?>
            <tr>
                <td align="right">Salary</td>
                <td align="right">Rs. <?php
                	$rs = dofetch( doquery( "select sum(amount) from salary_payment where status=1 and school_id = '".$_SESSION["current_school_id"]."'".$extra, $dblink ) );
					echo curr_format( $rs[ "sum(amount)" ] );
					$total += $rs[ "sum(amount)" ];
				?></td>
            </tr>
            <tr>
                <th align="right">Total Expense</th>
                <th align="right">Rs. <?php echo curr_format($total)?></th>
            </tr>
            <?php
            $diff = $revenue - $total;
			?>
            <tr>
                <th align="right">Total <?php echo $diff<0?"Loss":"Income"?></th>
                <th align="right">Rs. <?php echo curr_format(abs($diff))?></th>
            </tr>
        </tr>	
  	</table>
<?php
die;
