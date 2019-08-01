<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
           Balance Sheet
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Balance Sheet
            </small>
        </h1>
    </div>
    <div class="panel-body table-responsive">
        <table id="dynamic-table" class="table list table-bordered table-hover">
            <thead>
                <tr>
                    <th width="50%">Assets</th>
                    <th>Liabilities</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <table id="dynamic-table" class="table list table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th colspan="2">Current Assets</th>
                                </tr>
                            </thead>
                            <?php 
                            $sql="select * from account where status=1 and school_id = '".$_SESSION["current_school_id"]."'";
                            $rs=doquery($sql, $dblink);
                            
                            $total = 0;
                            $account_payable = array();
                            if( numrows($rs) > 0){
                                $sn=1;
                                while($r=dofetch($rs)){             
                                    $balance = get_account_balance( $r[ "id" ] );
                                    if( $balance >= 0 ) {
                                        $total += $balance;
                                        ?>
                                        <tr>
                                            <td><?php echo unslash($r["title"] ); ?></td>
                                            <td class="text-right"><?php echo curr_format( $balance ) ?></td>
                                        </tr>
                                        <?php 
                                        $sn++;
                                    }
                                    else {
                                        $account_payable[] = array(
                                            "name" => unslash($r["title"] ),
                                            "balance" => $balance
                                        );
                                    }
                                }
                                ?>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right"><?php echo curr_format($total);?></th>
                                </tr>
                                <?php	
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table id="dynamic-table" class="table list table-bordered table-hover">
                            <?php 
                            if( count($account_payable) > 0){
                                ?>
                                <thead>
                                    <tr>
                                        <th colspan="2">Accounts</th>
                                    </tr>
                                </thead>
                                <?php
                                $account_total=0;
                                $sn=1;
                                foreach( $account_payable as $account ){
                                    $account_total += $account[ "balance" ];
                                    ?>
                                    <tr>
                                        <td><?php echo $account["name"]; ?></td>
                                        <td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
                                    </tr>
                                    <?php 
                                    $sn++;
                                }
                                ?>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right"><?php echo curr_format($account_total);?></th>
                                </tr>
                                <?php	
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
