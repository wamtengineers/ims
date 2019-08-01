<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Fees Chalan
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?php
                if( !empty( $student_2_class_id ) ) {
					$res=doquery("Select * from student_2_class a left join student b on a.student_id = b.id left join class_section c on a.class_section_id = c.id left join class d on c.class_id = d.id where a.id = '".$student_2_class_id."'",$dblink);
					if(numrows($res)>0){
						$rec=dofetch($res);
						$student_class = unslash( $rec[ "student_name" ] )." (Class: ".unslash( $rec[ "class_name" ] )."-".unslash( $rec[ "title" ] ).")";
					}
				}
				if( isset( $student_class ) ) {
					echo $student_class;
				}
				else {
					echo "Select Student from the list";
				}
				?>
            </small>
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="dataTables_length col-md-4" id="dynamic-table_length">
                                        <div class=""><a href="student_manage.php?tab=fees_chalan<?php echo isset($student_2_class)?"&id=".$student_2_class[ "student_id" ]:"";?>" target="_blank" class="fees_chalan_generate btn btn-sm btn-info">Generate Chalan</a>
                                        	<a href="fees_chalan_manage.php?tab=receiving" class="btn btn-sm btn-success">Chalan Receiving</a>
                                            <a class="btn btn-sm btn-info" href="fees_chalan_manage.php?tab=print_report"><i class="fa fa-print" aria-hidden="true"></i></a>
                                            <a class="btn btn-sm btn-info" href="fees_chalan_manage.php?tab=print_report_class_wise"><i class="fa fa-print" aria-hidden="true"></i> Class wise</a>
                                        </div>
                                    </div>
                                    <div class="col-md-8 align-right search">
                                    	<form action="" method="get">
                                        	<select name="is_received" id="is_received" class="custom_select">
                                                <option value="">Pending/Received</option>
                                                <option value="1"<?php echo $is_received=="1"?" selected":""?>>Received</option>
                                                <option value="0"<?php echo $is_received=="0"?" selected":""?>>Pending</option>
                                            </select>
                                            <select name="academic_year_id" id="academic_year_id" class="custom_select">
                                                <?php
                                                    $res=doquery("select * from academic_year where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                		?>
                                                		<option value="<?php echo $rec["id"]?>" <?php echo($academic_year_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                		<?php
                                                    	}
                                                    }	
                                                ?>
                                            </select>
                                            <!--<select name="status" id="status" class="custom_select">
                                                <option value="1"<?php echo ($status=="1")? " selected":"";?>>Recieved</option>
                                                <option value="0"<?php echo ($status=="0")? " selected":"";?>>Cancelled</option>
                                            </select>-->
                                            <?php
                                            if( !empty( $academic_year_id ) ) {
												?>
												<select name="fee_month">
                                                	<option value="">All Months</option>
                                                    <?php
                                                    foreach( get_months( $academic_year_id ) as $k => $v ) {
														?>
														<option value="<?php echo $k?>"<?php echo $k == $fee_month?' selected':''?>><?php echo $v?></option>
														<?php
													}
													?>
                                                </select>
                                                <select name="class_section_id" id="class_section_id" class="custom_select">
                                                    <option value="">Select Class Section</option>
                                                    <?php
                                                        $res=doquery("select a.*, b.class_name from class_section a inner join class b on a.class_id=b.id where a.school_id = '".$_SESSION["current_school_id"]."' order by sortorder",$dblink);
                                                        if(numrows($res)>=0){
                                                            while($rec=dofetch($res)){
                                                            ?>
                                                            <option value="<?php echo $rec["id"]?>" <?php echo($class_section_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["class_name"])." - ".unslash($rec["title"])?></option>
                                                            <?php
                                                            }
                                                        }	
                                                    ?>
                                                </select>
                                                <select name="student_2_class_id" id="student_2_class_id" class="custom_select">
                                                    <option value=""<?php echo ($student_2_class_id=="")? " selected":"";?>>Select Student</option>
                                                    <?php
                                                        $res=doquery("select a.*, b.student_name, c.title, d.class_name from student_2_class a left join student b on a.student_id = b.id left join class_section c on a.class_section_id = c.id left join class d on c.class_id = d.id where a.academic_year_id = '".$academic_year_id."' order by d.sortorder, c.title, b.student_name",$dblink);
                                                        if(numrows($res)>=0){
                                                            $class_id = 0;
															$optgroup_started = false;
															while($rec=dofetch($res)){
                                                            	if( $rec[ "class_section_id" ] != $class_id ) {
																	if( $optgroup_started ){
																		echo '</optgroup>';
																	}
																	echo '<optgroup label="Class: '.unslash($rec["class_name"])."-".unslash($rec["title"]).'">';
																	$optgroup_started = true;
																	$class_id = $rec[ "class_section_id" ];
																}
																?>
                                                            <option value="<?php echo $rec["id"]?>" <?php echo($student_2_class_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["student_name"])." (Class: ".unslash($rec["class_name"])."-".unslash($rec["title"]).")"?></option>
                                                            <?php
                                                            }
															if( $optgroup_started ){
																echo '</optgroup>';
															}
                                                        }	
                                                    ?>
                                                </select>
												<?php
											}
											?>
                                            <div class="input-group">
                                            	<input type="text" name="issue_date_from" id="issue_date_from" value="<?php echo $issue_date_from?>" class="date-picker" placeholder="Issued after" />
                                          	</div>
                                            <div class="input-group">
                                            	<input type="text" name="issue_date_to" id="issue_date_to" value="<?php echo $issue_date_to?>" class="date-picker" placeholder="Issued Before" />
                                          	</div>
                                            <div class="input-group">
                                            	<input type="text" name="payment_date_from" id="payment_date_from" value="<?php echo $payment_date_from?>" class="date-picker" placeholder="Paid after" />
                                          	</div>
                                            <div class="input-group">
                                            	<input type="text" name="payment_date_to" id="payment_date_to" value="<?php echo $payment_date_to?>" class="date-picker" placeholder="Paid Before" />
                                          	</div>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                        Search
                                                    </button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="3%">S.No</th>
                                        <th width="2%" class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" id="select_all" value="0" title="Select All Records" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th width="15%">Student</th>
                                        <th width="8%">
                                        	<a href="fees_chalan_manage.php?order_by=month&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                                        		Month
                                                <?php
												if( $order_by == "month" ) {
													?>
													<span class="sort-icon">
														<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
													</span>
													<?php
												}
												?>
                                            </a>
                                        </th>
                                        <th width="8%">Issue Date</th>
                                        <th width="20%">Details</th>
                                        <th width="8%">Amount</th>
                                        <th width="8%">Receiving Date</th>
                                        <th width="8%">Rec. Amount</th>
                                        <th width="8%">Balance</th>
                                        <th width="4%" class="center">Status</th>
                                        <th width="8%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									if( !empty( $extra ) ) { 
										
										$rs=show_page($rows, $pageNum, $sql);
										if(numrows($rs)>0){
											$sn=1;
											while($r=dofetch($rs)){           
												if( !is_null( $r[ "payment_date" ] ) ) {
													$receiving_date = date_convert( $r[ "payment_date" ] ).'<br><a href="fees_chalan_manage.php?tab=edit_receiving&id='.$r[ "id" ].'">Edit</a> - <a href="fees_chalan_manage.php?tab=edit_receiving&id='.$r[ "id" ].'&delete">Delete</a>';
													$receiving_amount = $r[ "amount" ];
												}
												else {
													$receiving_date = '<a href="fees_chalan_manage.php?tab=edit_receiving&id='.$r[ "id" ].'">Add</a>';
													$receiving_amount = "0";
												}
												?>
												<tr>
													<td><?php echo $sn;?></td>
													<td class="center">
														<label class="pos-rel">
															<input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" class="ace" />
															<span class="lbl"></span>
														</label>
													</td>
													<td><?php 
														$res=doquery("Select * from student_2_class a left join student b on a.student_id = b.id left join class_section c on a.class_section_id = c.id left join class d on c.class_id = d.id where a.id = '".$r[ "student_2_class_id" ]."'",$dblink);
														if(numrows($res)>0){
															$rec=dofetch($res);
															echo unslash( $rec[ "student_name" ] )." (Class: ".unslash( $rec[ "class_name" ] )."-".unslash( $rec[ "title" ] ).")";
														}
														else{
															echo "Unknown";
														}
													 ?></td>
                                                    <td><?php echo show_month($r["month"]); ?></td>
													<td><?php echo date_convert($r["issue_date"]); ?></td>
                                                    <td><?php
                                                    	$amount = 0;
														$chalan_details = get_chalan_details( $r["id"] );
														foreach( $chalan_details[ "details" ] as $chalan_detail ) {
															echo $chalan_detail[ "title" ].": ".curr_format( $chalan_detail[ "amount" ] )."<br />";
															$amount += $chalan_detail[ "amount" ];
														}
													?></td>
                                                    <td><?php echo curr_format( $amount );?></td>
                                                    <td><?php echo $receiving_date;?></td>
                                                    <td><?php echo curr_format( $receiving_amount );?></td>
                                                    <td><?php echo curr_format( $amount - $receiving_amount );?></td>
													<td class="center">
														<a href="fees_chalan_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
															<?php
															if($r["status"]==0){
																?>
																<img src="images/offstatus.png" alt="Off" title="Set Status On">
																<?php
															}
															else{
																?>
																<img src="images/onstatus.png" alt="On" title="Set Status Off">
																<?php
															}
															?>
														</a>
													</td>
													<td class="center">
														<div class="hidden-sm hidden-xs action-buttons">
															<a class="green" title="Edit Record" href="fees_chalan_manage.php?tab=edit&id=<?php echo $r['id'];?>">
																<i class="ace-icon fa fa-pencil bigger-130"></i>
															</a>
				
															<a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="fees_chalan_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
																<i class="ace-icon fa fa-trash-o bigger-130"></i>
															</a>
														</div>
														<div class="hidden-md hidden-lg">
															<div class="inline pos-rel">
																<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																	<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																</button>
																<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																	<li>
																		<a title="Edit Record" href="fees_chalan_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
																			<span class="green">
																				<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																			</span>
																		</a>
																	</li>
																	<li>
																		<a onclick="return confirm('Are you sure you want to delete')" href="fees_chalan_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
																			<span class="red">
																				<i class="ace-icon fa fa-trash-o bigger-120"></i>
																			</span>
																		</a>
																	</li>
																</ul>
															</div>
														</div>
													</td>
												</tr>  
												<?php 
												$sn++;
											}
											?>
											<tr class="modal-footer no-margin-top">
												<td colspan="5" class="actions">
													<select name="bulk_action" class="" id="bulk_action" title="Choose Action">
														<option value="null">Bulk Action</option>
														<option value="delete">Delete</option>
														<option value="statuson">Set Status On</option>
														<option value="statusof">Set Status Off</option>
													</select>
													<input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
												</td>
												<td colspan="7" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "fees_chalan", $sql, $pageNum)?></td>
											</tr>
											<?php	
										}
										else{	
											?>
											<tr>
												<td colspan="12"  class="no-record">No Result Found</td>
											</tr>
											<?php
										}
									}
									else{
										?>
                                        <tr>
                                            <td colspan="12"  class="no-record">Select Student from the list</td>
                                        </tr>
                                        <?php
									}
            						?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>