<?php
if(!defined("APP_START")) die("No Direct Access");
$monthly_fees_id = get_config( "monthly_fees_id" );
?>
<div class="page-content">
    <div class="row">
        <div class="col-sm-5">
            <div class="page-header">
                <h1>
                    Manage Student
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        Administrators Student
                    </small>
                </h1>
            </div>
     	</div>
        <div class="col-sm-7">
        	<div class="student-link clearfix">
                <ul>
                
                	<li><a href="student_manage.php?tab=mail_envelop">Mail Envelope</a></li>
                    <li><a href="student_manage.php?tab=result_envelop">Result Envelope</a></li>
                    <li><a href="student_manage.php?tab=idcard">All ID Card</a></li>
                    <li><a href="student_manage.php?tab=fees_chalan" class="fees_chalan_generate" target="_blank">Generate Chalan</a></li>
                    <li><a href="student_manage.php?tab=marksheet" class="marksheet_generate" target="_blank">Merksheet</a></li>
                    <li><a href="student_manage.php?tab=month_star" class="marksheet_generate" target="_blank">Star Cards</a></li>
                    <li><a href="student_manage.php?tab=print" target="_blank">Print</a></li>
              	</ul>
          	</div>
        </div>
   	</div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                            <div class="row">
                            	<form action="" method="get">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="dataTables_length col-md-2 col-sm-4 col-xs-4" id="dynamic-table_length">
                                            <div class=""><a href="student_manage.php?tab=edit&add" class="btn btn-sm btn-primary">Add New Record</a></div>
                                        </div>
                                        <div class="pull-right filter-btn"><i class="fa fa-filter" aria-hidden="true"></i></div>
                                        <div class="col-md-10 col-sm-8 col-xs-12 align-right search open-filter">
                                            <a href="#" id="advanced_search_trigger">Advanced Search</a>
                                            <input type="hidden" name="is_advanced_search" id="is_advanced_search" value="<?php echo $is_advanced_search?>"  />
                                            <select name="year_id" id="year_id" class="custom_select">
                                                <option value="">All Students</option>
                                                <?php
                                                    $res=doquery("select * from academic_year where school_id = '".$_SESSION["current_school_id"]."' order by title desc",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                        ?>
                                                        <option value="<?php echo $rec["id"]?>" <?php echo($year_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                        <?php
                                                        }
                                                    }	
                                                ?>
                                            </select>
                                            <select name="class_section_id" id="class_section_id" class="custom_select">
                                                <option value=""<?php echo ($class_section_id=="")? " selected":"";?>>All Classes</option>
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
                                            <select name="student_status" id="student_status" class="custom_select">
                                                <option value="1"<?php echo ($student_status=="1")? " selected":"";?>>Present</option>
                                                <option value="0"<?php echo ($student_status=="0")? " selected":"";?>>Left</option>
                                            </select>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-check"></i>
                                                </span>
                                                <input class="form-control search-query" value="<?php echo $q;?>" name="q" id="search" type="text">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary btn-sm" alt="Search Record" title="Search Record">
                                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                        Search
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12"<?php echo $is_advanced_search==0?' style="display: none"':''?> id="advanced_search_container">
                                        <div class="col-md-12 col-xs-12" style="margin-top: 10px;">
                                            <select name="gender" id="gender" class="custom_select">
                                                <option value="">All Gender</option>
                                                <option value="male"<?php echo ($gender=="male")? " selected":"";?>>Male</option>
                                                <option value="female"<?php echo ($gender=="female")? " selected":"";?>>Female</option>
                                            </select>
                                            <select name="house_id" id="house_id" class="custom_select">
                                                <option value=""<?php echo ($house_id=="")? " selected":"";?>>All Houses</option>
                                                <?php
                                                    $res=doquery("select * from houses order by title",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                        ?>
                                                        <option value="<?php echo $rec["id"]?>" <?php echo($house_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                        <?php
                                                        }
                                                    }	
                                                ?>
                                            </select>
                                            <select name="taekwondo_fees_id" id="taekwondo_fees_id" class="custom_select">
                                                <option value=""<?php echo ($taekwondo_fees_id=="")? " selected":"";?>>All Fees</option>
                                                <?php
                                                    $res=doquery("select * from fees where school_id = '".$_SESSION["current_school_id"]."' order by title",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                        ?>
                                                        <option value="<?php echo $rec["id"]?>" <?php echo($taekwondo_fees_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                        <?php
                                                        }
                                                    }	
                                                ?>
                                            </select>
                                            <!--<select name="group_id" id="group_id" class="custom_select">
                                                <option value=""<?php echo ($group_id=="")? " selected":"";?>>All Group</option>
                                                <?php
                                                    $res=doquery("select * from `group` order by title",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                        ?>
                                                        <option value="<?php echo $rec["id"]?>" <?php echo($group_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                                        <?php
                                                        }
                                                    }	
                                                ?>
                                            </select>
-->                                            <input type="text" name="admission_from" value="<?php echo $admission_from?>" class="datepicker" placeholder="Admission From" />
                                            <input type="text" name="admission_to" value="<?php echo $admission_to?>" class="datepicker" placeholder="Admission To" />
                                            <input type="text" name="fees_from" value="<?php echo $fees_from?>" placeholder="Fees From" />
                                            <input type="text" name="fees_to" value="<?php echo $fees_to?>" placeholder="Fees to" />
                                       	</div>
                                    </div>
                                </form>
                            </div>
                            <table ng-controller="StoreController as store" id="dynamic-table" class="table list table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">S.No</th>
                                        <th width="5%" class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" id="select_all" value="0" title="Select All Records" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th width="15%">
                                        	<a href="student_manage.php?order_by=id&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                                        		Student Registration No.
                                                <?php
												if( $order_by == "id" ) {
													?>
													<span class="sort-icon">
														<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
													</span>
													<?php
												}
												?>
                                            </a>
                                        </th>
                                        <th width="15%"><a href="student_manage.php?order_by=class&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                                        	Class
                                            <?php
											if( $order_by == "class" ) {
												?>
												<span class="sort-icon">
													<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
												</span>
												<?php
											}
											?></a>
                                       	</th>
                                        <th width="15%"><a href="student_manage.php?order_by=student_name&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                                        	Student name
                                        	<?php
											if( $order_by == "student_name" ) {
												?>
												<span class="sort-icon">
													<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
												</span>
												<?php
											}
											?></a>
                                        </th>
                                        <th width="15%"><a href="student_manage.php?order_by=father_name&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                                        	Father Name
                                            <?php
											if( $order_by == "father_name" ) {
												?>
												<span class="sort-icon">
													<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
												</span>
												<?php
											}
											?></a>
                                       	</th>
                                        <th width="10%"><a href="student_manage.php?order_by=surname&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                                        	Surname
                                       		<?php
											if( $order_by == "surname" ) {
												?>
												<span class="sort-icon">
													<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
												</span>
												<?php
											}
											?></a>
                                        </th>
                                        <th width="10%">Monthly Fees</th>
                                        <th width="10%" class="center">Status</th>
                                        <th width="10%" class="center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$rs=show_page($rows, $pageNum, $sql);
									if(numrows($rs)>0){
										$sn=1;
										while($r=dofetch($rs)){             
											?>
                                            <tr>
                                                <td><?php echo $sn;?></td>
                                                <td class="center">
                                                    <label class="pos-rel">
                                                        <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td><?php echo unslash($r["id"]); ?></td>
                                                <td><?php echo get_student_class($r["id"]); ?></td>
                                                <td><?php echo unslash($r["student_name"]); ?></td>
                                                <td><?php echo unslash($r["father_name"]); ?></td>
                                                <td><?php echo unslash($r["surname"]); ?></td>
                                                <td><?php echo get_student_meta($r["id"], "fees_".$monthly_fees_id."_approved"); ?></td>
                                                <td class="center">
                                                	<a href="student_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                                                        <a class="green" title="Edit Record" href="student_manage.php?tab=edit&id=<?php echo $r['id'];?>">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
                                                        <a class="green fancybox iframe" title="Edit Record" href="student_2_class_manage.php?parent_id=<?php echo $r["id"]?>">
                                                            <i class="fa fa-flask" aria-hidden="true"></i>
                                                        </a>
                                                        <a class="red"  title="Delete Record" onclick="return confirm('Are you sure you want to delete')" href="student_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete">
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
                                                                    <a title="Edit Record" href="student_manage.php?tab=edit&id=<?php echo $r['id'];?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="Edit Record" href="student_2_class_manage.php?parent_id=<?php echo $r["id"]?>" class="tooltip-success" data-rel="tooltip">
                                                                        <span class="green">
                                                                           <i class="fa fa-flask bigger-120 ace-icon" aria-hidden="true"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="return confirm('Are you sure you want to delete')" href="student_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete" class="tooltip-error" data-rel="tooltip">
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
                                            <td colspan="4" class="actions">
                                                <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                                                    <option value="null">Bulk Action</option>
                                                    <option value="delete">Delete</option>
                                                    <option value="statuson">Set Status On</option>
                                                    <option value="statusof">Set Status Off</option>
                                                </select>
                                                <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-primary" title="Apply Action"  />
                                            </td>
                    						<td colspan="5" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "student", $sql, $pageNum)?></td>
                						</tr>
                						<?php	
            						}
            						else{	
                						?>
                                        <tr>
                                            <td colspan="9"  class="no-record">No Result Found</td>
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