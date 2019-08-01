<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
           Reports
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                General Journal Report
            </small>
        </h1>
    </div>
    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
        <div class="row">
            <div class="col-xs-12 search">
                <form action="" method="get">
                    <input type="hidden" name="tab" value="general_journal" />
                    <span class="col-sm-1 margin-top">Account</span>
                    <div class="col-sm-3 no-padding">
                        <select name="account_id" id="account_id" class="form-control" style="width:100%">
                            <?php
                            $rs=doquery( "select * from account where status=1 and school_id = '".$_SESSION["current_school_id"]."' order by title", $dblink );
                            if( numrows( $rs ) > 0 ) {
                                while( $r = dofetch( $rs ) ) {
                                    ?>
                                    <option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "id" ]==$account_id?' selected':''?>><?php echo unslash( $r[ "title" ] )?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
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
                        <a class="btn btn-primary btn-sm" href="report_manage.php?tab=general_journal_print"><i class="fa fa-print" aria-hidden="true"></i></a>
                    </div>
                </form>
            </div>
        </div>
        <table id="dynamic-table" class="table list table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%" class="text-center">S.no</th>
                    <th>
                        <a href="report_manage.php?tab=general_journal&order_by=datetime_added&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                            Date
                            <?php
                            if( $order_by == "datetime_added" ) {
                                ?>
                                <span class="sort-icon">
                                    <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                                </span>
                                <?php
                            }
                            ?>
                        </a>
                    </th>
                    <th>Details</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right" >Credit</th>
                    <th class="text-right" >Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rs=doquery($sql, $dblink);
				$debit = 0;
				$credit = 0;
                if(numrows($rs)>0){
                    $sn=1;
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><?php echo $order == 'desc'?'Closing':'Opening'?> Balance</td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><?php echo curr_format( $balance )?></td>
                    </tr>
                    <?php
                    while($r=dofetch($rs)){             
						$debit += $r["debit"];
						$credit += $r["credit"];
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $sn;?></td>
                            <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
                            <td><?php echo unslash($r["details"]); ?></td>
                            <td class="text-right"><?php echo curr_format($r["debit"]); ?></td>
                            <td class="text-right"><?php echo curr_format($r["credit"]); ?></td>
                            <td class="text-right"><?php if($order == 'asc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} echo curr_format( $balance ); if($order == 'desc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} ?></td>
                        </tr>
                        <?php 
                        $sn++;
                    }
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><?php echo $order != 'desc'?'Closing':'Opening'?> Balance</td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><?php echo curr_format( $balance )?></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Total Income</td>
                        <td class="text-right"><?php echo curr_format( $debit )?></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Total Payment</td>
                        <td class="text-right"><?php echo curr_format( $credit )?></td>
                    </tr>
                    <?php	
                }
                else{	
                    ?>
                    <tr>
                        <td colspan="6"  class="no-record">No Result Found</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div> 
</div>
