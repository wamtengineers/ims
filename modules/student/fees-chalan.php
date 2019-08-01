<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$students = doquery("select * from student where id='".slash($_GET["id"])."'", $dblink);
}
else{
	$students = doquery( $sql, $dblink );
}
if( isset( $_GET[ "academic_year_id" ] ) ){
	$academic_year_id = slash( $_GET[ "academic_year_id" ] );
}
else {
	$academic_year = current_academic_year();
	$academic_year_id = $academic_year[ "id" ];
}
if( numrows( $students ) > 0 ){
	$month_from = '';
	if( isset( $_GET[ "fee_chalan_month" ] ) && !empty( $_GET[ "fee_chalan_month" ] ) ) {
		$month = $_GET[ "fee_chalan_month" ];
		if( isset( $_GET[ "fee_chalan_year" ] ) && !empty( $_GET[ "fee_chalan_year" ] ) ) {
			$year = $_GET[ "fee_chalan_year" ];
		}
		else {
			$year = date( "Y" );
		}
		$month = $year.( strlen($month)==1?"0".$month:$month );
		if( isset( $_GET[ "fee_chalan_month_from" ] ) && !empty( $_GET[ "fee_chalan_month_from" ] ) ) {
			$month_from = $_GET[ "fee_chalan_month_from" ];
			if( isset( $_GET[ "fee_chalan_year_from" ] ) && !empty( $_GET[ "fee_chalan_year_from" ] ) ) {
				$year_from = $_GET[ "fee_chalan_year_from" ];
			}
			else {
				$year_from = date( "Y" );
			}
			$month_from = $year_from.( strlen($month_from)==1?"0".$month_from:$month_from );
		}
	}
	else {
		$month = date( "Ym" );
	}
	if( isset( $_GET[ "due_date" ] ) && !empty( $_GET[ "due_date" ] ) ) {
		$due_date = date_dbconvert( $_GET[ "due_date" ] );
	}
	else{
		$due_date = '';
	}
	$issue_date = date( "Y-m-d" );
	$fees_chalan_header = get_config( 'fees_chalan_header' );
	$fees_chalan_payment_details = nl2br( get_config( 'fees_chalan_payment_details' ) );
	?>
	<!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Property Chalan</title>
    <link type="text/css" rel="stylesheet" href="css/chalan.css">
    </head>
    <body>
    	<?php
        while( $student = dofetch( $students ) ) {
			if( isset( $_GET[ "chalan_id" ] ) ) {
				$chalan = dofetch( doquery( "select * from fees_chalan where id='".$_GET[ "chalan_id" ]."'", $dblink ) );
				$student_2_class = dofetch( doquery( "select a.*, b.title as section, c.class_name as class from student_2_class a inner join class_section b on a.class_section_id = b.id inner join class c on b.class_id = c.id where id='".$chalan[ "student_2_class_id" ]."'", $dblink ));
			}
			else {
				$student_2_class = doquery( "select a.*, b.title as section, c.class_name as class from student_2_class a inner join class_section b on a.class_section_id = b.id inner join class c on b.class_id = c.id where academic_year_id='".$academic_year_id."' and student_id='".$student[ "id" ]."'", $dblink );
				if( numrows( $student_2_class ) > 0 && $student[ "status" ] == 1 ) {
					$student_2_class = dofetch( $student_2_class );
					$rs = doquery( "select * from fees_chalan where month >= '".$month."' and student_2_class_id = '".$student_2_class[ "id" ]."'", $dblink );
					if( numrows( $rs ) > 0 ) {
						$chalan = dofetch( $rs );
					}
					else {
						if( !isset( $academic_year_start_ts ) ) {
							$academic_year = dofetch( doquery( "select * from academic_year where id='".$academic_year_id."'", $dblink ) );
							$academic_year_start_ts = strtotime( $academic_year[ "start_date" ] );
						}
						$chalan = generate_chalan( $student[ "id" ], $student_2_class[ "id" ], $academic_year_id, $month, $issue_date, $academic_year_start_ts, $due_date, $month_from );
					}
				}
			}
			if( isset( $chalan ) ) {
				$chalan_details = get_chalan_details( $chalan );
				if( $chalan_details[ "total" ] > 0 ) {
					?>
                    <div id="wrapper">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="head">
                            <th width="33.3%" align="center">School Copy</th>
                            <th width="33.3%" align="center">Bank Copy</th>
                            <th width="33.3%" align="center">Student Copy</th>
                        </tr>
                        <tr>
                            <?php
                            for( $i = 0; $i < 3; $i++ ) {
                                ?>
                                <td width="33.3%" class="chalan first-chalan">
                                <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                                    <tr>
                                        <td class="header">
                                            <span class="logo"><img src="<?php echo $file_upload_url;?>config/<?php echo $school_logo?>" alt="image" /></span>
                                            <span class="date"><?php echo strtoupper(date( "M", strtotime( $chalan[ "month" ]."01" ) ))?><br> <?php echo date( "Y", strtotime( $chalan[ "month" ]."01" ) )?></span>
                                            <?php echo $fees_chalan_header;?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center"><h2><span>FEE Challan</span><br /></h2></td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="chalan_number">
                                              <tr>
                                                <td width="10%">Challan No.&nbsp;</td>
                                                <td width="43%" id="chalan_number"><?php echo $chalan[ "id" ]?></td>
                                                <td width="17%" align="right" style="padding-right:10px">SID</td>
                                                <td width="30%" id="chalan_number"><?php echo $student[ "id" ]?></td>
                                              </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="chalan_particulars">
                                                <tr>
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td width="">Student's Name:&nbsp;</td>
                                                                <td id="chalan_name" width="70%"><?php echo unslash( $student[ "student_name" ] )?></td>
                                                            </tr>
                                                        </table>
                                                        <table>
                                                            <tr>
                                                                <td width="">Father's Name:</td>
                                                                <td width="70%" id="chalan_name"><?php echo unslash( $student[ "father_name" ] )?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-bottom:5px;">
                                              <tr>
                                                <td width="8%"><strong>Grade:</strong>&nbsp;</td>
                                                <td width="42%" id="chalan_number"><?php echo unslash( $student_2_class[ "class" ] )?></td>
                                                <td width="20%" align="right" style="padding-right:10px"><strong>Sec:</strong></td>
                                                <td width="30%" id="chalan_number"><?php echo unslash( $student_2_class[ "section" ] )?></td>
                                              </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="chalan_details">
                                                <tr>
                                                    <td width="75%"><strong>Fee Title</strong></td>
                                                    <td width="25%"><strong>Amount</strong></td>
                                                </tr>
                                                <?php
                                                foreach( $chalan_details[ "details" ] as $chalan_detail ) {
                                                    ?>
                                                    <tr class="print">
                                                        <td><?php echo $chalan_detail[ "title" ]?></td>
                                                        <td align="right"><?php echo curr_format( $chalan_detail[ "amount" ] )?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                for( $j = 0; $j < 6-count( $chalan_details[ "details" ] ); $j++) {
                                                    ?>
                                                    <tr class="print">
                                                        <td>&nbsp;</td>
                                                        <td align="right">&nbsp;</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td align="right"><strong>Total:</strong></td>
                                                    <td align="right"><strong class="print" id="chalan_total"><?php echo curr_format( $chalan_details[ "total" ] )?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="left" style="height:30px; vertical-align: top;"><span id="in_reppees" class="print"><strong>In Ruppes:</strong> <?php echo convert_number_to_words( $chalan_details[ "total" ] )?> only</span></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><strong>Payable within Due Date:</strong></td>
                                                    <td align="right"><strong class="print" id="chalan_total"><?php echo curr_format( $chalan_details[ "total" ] )?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><strong>Due Date:</strong></td>
                                                    <td align="right"><strong class="print" id="chalan_total"><?php echo isset($_GET["due_date"])?$_GET["due_date"]:date_convert( $chalan_details[ "due_date" ] )?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><strong>Late Fees:</strong></td>
                                                    <td align="right"><strong><?php echo curr_format( $chalan_details[ "late_fees" ] )?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><strong>Payable by after Due Date:</strong></td>
                                                    <td align="right"><strong class="print" id="chalan_total"><?php echo curr_format( $chalan_details[ "total_after_due_date" ] )?></strong></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="signature">&nbsp;</td>
                                                <td class="signature">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="center" width="70%" colspan="2"><strong class="print" style="font-size:14px"><?php echo $fees_chalan_payment_details?></strong></td>
                                                <td align="center" valign="top"><strong>Authorised Official</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align="center">
                                                    <?php $barcode=$chalan[ "month" ].str_repeat('0', 4-strlen($student[ "id" ])).$student[ "id" ].str_repeat('0', 7-strlen($chalan[ "id" ])).$chalan[ "id" ];?>
                                                    <p style="margin:10px 0"><img src="barcode.php?text=<?php echo $barcode?>" /><span style="text-align: center;letter-spacing: 5px;margin-left: 8px; display:block;"><?php echo $barcode;?></span></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="text-align:center; padding:0px 0 0; font-size:11px;">Software Developed by WamtSol - 03032251852</td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>                
                                <?php
                            }
                            ?>
                        </tr>
                    </table>
                    </div>
					<?php
				}
			}
		}
		?>
        <?php if( !isset( $_GET[ "print_blank" ] ) ) {?>
        <a href="student_manage.php?<?php $g = array(); foreach( $_GET as $k => $v ){ if( $k != 'print_blank' ){ $g[] = $k.'='.$v; } } echo implode( "&", $g );?>&print_blank" class="print_blank">Print On Blank Page</a>
        <?php } else { ?>
        <a href="student_manage.php?<?php $g = array(); foreach( $_GET as $k => $v ){ if( $k != 'print_blank' ){ $g[] = $k.'='.$v; } } echo implode( "&", $g );?>" class="print_blank">Print On Printed Page</a>
        <?php } ?>
        <style>
		#wrapper table{ border-collapse:collapse;}
		#wrapper{ box-sizing: border-box;}
		.head th{ padding-top:5px; text-transform:uppercase;}
		@media print{
			.print_blank{ display:none;}
		}
		<?php if( !isset( $_GET[ "print_blank" ] ) ) {?>
        @media print{
			.header{ height:95px;}
			#wrapper{ padding-right:25px; padding-left:10px;}
			.header h1, .header h2 {
				display: none;
			}
			
			span.logo {
				display:  none;
			}
			
			.chalan {
				border: 0;
			}
		}
		<?php } ?>
        </style>
    </body>
    </html>
    <?php
}
die();