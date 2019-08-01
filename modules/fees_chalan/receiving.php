<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Fees Chalan Receiving
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Manage Fees Chalan
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
        
        	<div class="align-right">
                <div class="btn-group bottom-20"> <a href="fees_chalan_manage.php" class="btn btn-sm btn-primary">Back to List</a> </div>
            </div>
            <p><form><input type="hidden" name="tab" value="receiving" />Receiving Date <input type="text" class="date-picker" name="date" style="width: 200px !important;" value="<?php echo $date?>" /><input type="submit" value="Change Date"  style="width: auto;border: 0;line-height: 22px;margin-top: -3px;" class="btn"/></form></p>
            <!-- PAGE CONTENT BEGINS -->
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12" id="chalanReceiving" ng-app="chalan" ng-controller="chalanReceiving">
                        <p>Scan Chalan from barcode scanner. Result will appear below or enter chalan id <input type="text" placeholder="Chalan ID" style="width: 100px;" ng-model="chalan_id" ng-keypress="addChalanID( $event )" /></p>
                        <p><label ng-class="{'checked': confirm_amount}">Confirm amount first? <input type="checkbox" ng-model="confirm_amount" /></label></p>
                        <div class="receiving_results">
                            <div ng-repeat="chalan in chalans" class="receiving_result" ng-class="[{'chalan_received': chalan.status==1}, {'chalan_not_found': chalan.status==0}, {'chalan_already_received': chalan.status==2}, {'chalan_removed': chalan.is_deleted}]">
                                <span ng-if="chalan.status==1" >Chalan #{{chalan.id}} {{chalan.student_name}} - {{chalan.class}} recevied. Receiving Amount: {{chalan.amount}} <a ng-click="deleteReceiving( chalan.receiving_id, $index )" href="" class="btn btn-sm btn-danger">Undo</a> <a href="student_manage.php?tab=edit&id={{chalan.student_id}}" target="_blank" class="btn btn-sm btn-primary">View Record</a></span>
                                <span ng-if="chalan.status==2" >Chalan #{{chalan.id}} {{chalan.student_name}} - {{chalan.class}} already recevied. <a ng-click="deleteReceiving( chalan.receiving_id, $index )" href="" class="btn btn-sm btn-danger">Delete</a> <a href="student_manage.php?tab=edit&id={{chalan.student_id}}" target="_blank" class="btn btn-sm btn-primary">View Record</a></span>
                                <span ng-if="chalan.status==0" >No Chalan found.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>
<style>
div#chalanReceiving >
 p {
    font-size:  14px;
    font-weight:  bold;
}

div#chalanReceiving > p input {
    margin-left: 20px;
    text-align:  center;
    width: 75px !important;
}

div#chalanReceiving > p label input {
    float:  left;
    margin-left: 0;
    margin-right: 10px !important;
    width: auto !important;
}

div#chalanReceiving > p label {
    border: solid 1px #eee;
    padding: 5px 15px;
    cursor:  pointer;
}

div#chalanReceiving > p label.checked, div#chalanReceiving > p label:hover {
    background-color:  #eee;
}

.receiving_result {
    padding:  5px 15px;
    font-size:  14px;
    line-height: 25px;
    font-weight:  bold;
}

.receiving_result.chalan_removed {
    text-decoration: line-through;
}

.receiving_result.chalan_not_found {
    color: #f00;
}

.receiving_result.chalan_received {
    color: #00f;
}

.receiving_result.chalan_already_received {
    color: #f80;
}

.receiving_result a {
    margin-left: 17px;
    padding-top: 0px;
    padding-bottom: 0px;
}
</style>