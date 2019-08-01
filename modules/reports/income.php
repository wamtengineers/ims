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
$order_by = "datetime_added";
$order = "asc";
$orderby = $order_by." ".$order;
?>
<div class="page-content">
    <div class="page-header">
        <h1>
           Reports
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Income Report
            </small>
        </h1>
    </div>
    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
    	<div class="row">
            <div class="col-xs-12 search">
                <form action="" method="get">
                    <input type="hidden" name="tab" value="income" />
                    <span class="col-sm-1 align-right margin-top">From</span>
                    <div class="col-sm-2 no-padding">
                        <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control datepicker"  value="<?php echo $date_from?>" >
                    </div>
                    <span class="col-sm-1 align-right margin-top">To</span>
                    <div class="col-sm-2 no-padding">
                        <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control datepicker"  value="<?php echo $date_to?>" >
                    </div>                
                    <div class="col-sm-2 text-left">
                        <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                            Search
                        </button>
                        <a class="btn btn-primary btn-sm" href="report_manage.php?tab=income_print"><i class="fa fa-print" aria-hidden="true"></i></a>
                    </div>
                </form>
            </div>
        </div>
        <table id="dynamic-table" class="table list table-bordered table-hover">
        	<tr class="head">
                <th class="text-right">Chalan Receiving</th>
                <th class="text-right" >Rs. <?php
                	$rs = dofetch( doquery( "select sum(amount) from fees_chalan_receiving a left join fees_chalan b on a.fees_chalan_id = b.id where a.status=1 and b.school_id = '".$_SESSION["current_school_id"]."'".$extra1, $dblink ) );
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
                            <td class="text-right"><?php echo unslash( $r[ "title" ] )?></td>
                            <td class="text-right" >Rs. <?php echo curr_format($r[ "total" ])?></td>
                        </tr>	
                        <?php
                    }
                }
            }
            ?>
            <tr>
                <td class="text-right">Salary</td>
                <td class="text-right" >Rs. <?php
                	$rs = dofetch( doquery( "select sum(amount) from salary_payment where status=1 and school_id = '".$_SESSION["current_school_id"]."' ".$extra, $dblink ) );
					echo curr_format( $rs[ "sum(amount)" ] );
					$total += $rs[ "sum(amount)" ];
				?></td>
            </tr>
            <tr class="head">
                <th class="text-right">Total Expense</th>
                <th class="text-right" >Rs. <?php echo curr_format($total)?></th>
            </tr>
            <?php
            $diff = $revenue - $total;
			?>
            <tr class="head">
                <th class="text-right">Total <?php echo $diff<0?"Loss":"Income"?></th>
                <th class="text-right" >Rs. <?php echo curr_format(abs($diff))?></th>
            </tr>
        </table>
	</div>
</div>
