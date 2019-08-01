<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Employee Bulk Update
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
                                    <div class="dataTables_length col-md-6 col-sm-6 col-xs-12" id="dynamic-table_length">
                                        <div class=""><a href="employee_manage.php?tab=list" class="btn btn-sm btn-primary">Back to List</a></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 align-right search">
                                    	<form action="" method="get">
                                        	<input type="hidden" name="tab" value="bulk_update" />
                                            <input type="hidden" name="id" value="<?php echo $employee_status?>" />
                                            <style>
                                            .chosen-container{ max-width: 200px !important; text-align:left;}
                                            </style>
                                            <select name="fields_array[]" multiple="multiple" class="custom_select select_multiple">
                                            	<?php
                                                foreach( $employee_meta_fields as $field ) {
													?>
													<option<?php echo in_array( $field, $fields ) ? " selected" : ""?> value="<?php echo $field?>"><?php echo ucwords(str_replace( "_", " ", $field))?></option>
													<?php
												}
												?>
                                            </select>
                                            <select name="employee_status" id="employee_status" class="custom_select">
                                            	<option value=""<?php echo ($employee_status=="")? " selected":"";?>>All</option>
                                                <option value="1"<?php echo ($employee_status=="1")? " selected":"";?>>Present</option>
                                                <option value="0"<?php echo ($employee_status=="0")? " selected":"";?>>Left</option>
                                            </select>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="ace-icon fa fa-check"></i>
                                                </span>
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
                            <form method="post">
                                <table id="dynamic-table" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="3%" class="center">S.No</th>
                                            <th width="17%">Employee Name</th>
                                            <?php
											$fees = array();
                                            foreach( $fields as $field ) {
												
													$title = ucwords(str_replace( "_", " ", $field));
												
												?>
												<th width="<?php echo 80/count($fields)?>%"><?php echo $title?></th>
												<?php
											}
											?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sn=1;
                                        ?>
                                        <?php
                                        $rs=doquery($sql, $dblink);
                                        if(numrows($rs)>0){
                                            while($r=dofetch($rs)){          
                                                ?>
                                                <tr>
                                                    <td align="center"><?php echo $sn;?></td>
                                                    <td><?php echo unslash($r["name"]); echo " - ".unslash($r["father_name"]); ?></td>
                                                    <?php
													foreach( $fields as $field ) {
														if( in_array( $field, $employee_main_fields ) ) {
															$field_value = unslash( $r[ $field ] );
														}
														else {
															$field_value = get_employee_meta( $r[ "id" ], $field );
														}
														?>
														<td>
                                                            <input style="width: 100%" type="text" name="field_value[<?php echo $field?>][<?php echo $r["id"]?>]" value="<?php echo strpos( $field,"date" )!== false?date_convert($field_value):$field_value?>" class="<?php echo strpos( $field,"date" ) !== false?"datepicker":""?>" />
                                                        </td>
														<?php
													}
													?>
                                                </tr>  
                                                <?php 
                                                $sn++;
                                           	}
                                       	}
                                       	?>
                                    </tbody>
                                </table>
                                <input type="submit" name="save_employee" value="Save Employee" />
                            </form>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>