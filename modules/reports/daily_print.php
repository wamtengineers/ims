<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["datetime_added"])){
	$datetime_added=slash($_GET["datetime_added"]);
	$_SESSION["reports"]["daily"]["datetime_added"]=$datetime_added;
}
if(isset($_SESSION["reports"]["daily"]["datetime_added"]))
	$datetime_added=$_SESSION["reports"]["daily"]["datetime_added"];
else
	$datetime_added=date("d/m/Y");

if($datetime_added != ""){
	$extra.=" and datetime_added BETWEEN '".date('Y-m-d',strtotime(date_dbconvert($datetime_added)))." 00:00:00' AND '".date('Y-m-d',strtotime(date_dbconvert($datetime_added)))." 23:59:59'";
}

$order_by = "datetime_added";
$order = "asc";
$orderby = $order_by." ".$order;
$sql="select * from sales where 1 $extra order by $orderby";
$rs = doquery( $sql, $dblink );
$total_items = $total_price = $discount = $net_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Daily Report</title>
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
	text-align:left
}
</style>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr class="head">
        <th colspan="7" align="center">
            <?php echo get_config( 'reports_header' )?>
            <h2>Daily Report List</h2>
        </th>
    </tr>
    <tr>
        <th width="5%" align="center">S#</th>
        <th width="15%">Date</th>
        <th width="20%">Cash/Customer Name</th>
        <th width="15%" align="right">Total Items</th>
        <th width="15%" align="right">Total Price</th>
        <th width="10%" align="right">Discount</th>
        <th width="10%" align="right">Net Price</th>
    </tr>
    <?php
    if(numrows($rs)>0){
        $sn=1;
        while($r=dofetch($rs)){
            $total_items += $r["total_items"];
            $total_price += $r["total_price"];
            $discount += $r["discount"];
            $net_price += $r["net_price"];
            ?>
            <tr>
                <td style="text-align:center"><?php echo $sn++?></td>
                <td style="text-align:left;"><?php echo datetime_convert($r["datetime_added"]); ?></td>
                <td style="text-align:left;"><?php echo empty(get_field($r["customer_id"], "customer","customer_name"))?"Cash": get_field($r["customer_id"], "customer","customer_name"); ?></td>
                <td style="text-align:right;"><?php echo unslash($r["total_items"]); ?></td>
                <td style="text-align:right;"><?php echo curr_format(unslash($r["total_price"])); ?></td>
                <td style="text-align:right;"><?php echo curr_format(unslash($r["discount"])); ?></td>
                <td style="text-align:right;"><?php echo curr_format(unslash($r["net_price"])); ?></td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <td colspan="3" style="text-align:right;">Total</td>
        <td style="text-align:right;"><?php echo $total_items;?></td>
        <td style="text-align:right;"><?php echo $total_price;?></td>
        <td style="text-align:right;"><?php echo $discount;?></td>
        <td style="text-align:right;"><?php echo $net_price;?></td>
    </tr>
</table>
</body>
</html>
<?php
die;
//}