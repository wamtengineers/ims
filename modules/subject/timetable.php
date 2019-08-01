<style type="text/css">
.color1{ background:#fe5621;}
.color2{ background:#fe9700;}
.color3{ background:#4bae4f;}
.color4{ background:#3e50b4;}
.color5{ background:#e81d62;}
.color6{ background:#009587;}
.color7{ background:#6639b6;}
.color8{ background:#8ac249;}
.subject{ color:#fff;}

.name_button em {
    font-style: normal;
    display: inline-block;
    background: #fff;
    color: #000;
    border-radius: 100%;
    width: 18px;
    height: 18px;
    text-align: center;
    box-sizing: border-box;
    line-height: 18px;
    font-size: 10px;
    margin: 0px 0px 0px 12px;
}
.name_button {
    display: inline-block;
    padding: 8px 11px;
    color: #fff;
    font-family: arial;
    border-radius: 5px;
	margin-bottom:3px;
}
</style>
<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            Manage Class Section
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All Classes Section
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
                                    <div class="col-md-6 col-sm-6 col-xs-12 search">
                                    	<form action="" method="get">
                                        	<select name="class_id" id="class_id" class="custom_select">
                                                <option value=""<?php echo ($class_id=="")? " selected":"";?>>All Classes</option>
                                                <?php
                                                    $res=doquery("select * from class order by sortorder",$dblink);
                                                    if(numrows($res)>=0){
                                                        while($rec=dofetch($res)){
                                                		?>
                                                		<option value="<?php echo $rec["id"]?>" <?php echo($class_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["class_name"])?></option>
                                                		<?php
                                                    	}
                                                    }	
                                                ?>
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
                                        </form>
                                    </div>
                                </div>
                            </div>
                                <table id="dynamic-table" class="table list table-bordered table-hover">
                                    <thead>
                                        <tr align="center">
                                            <th class="center">&nbsp;</th>
                                            <th class="center">Monday</th>
                                            <th class="center">Tuesday</th>
                                            <th class="center">Wednesday</th>
                                            <th class="center">Thursday</th>
                                            <th class="center">Friday</th>
                                            <th class="center">Saturday</th>
                                            <th class="center">Sunday</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr align="center">
                                        	<td>5am</td>
                                            <td class="color1 subject">English<br/>Teacher Name</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Close</td>
                                            <td>Close</td>
                                        </tr>
                                        <tr align="center">
                                            <td>6am</td>
                                            <td class="color2 subject">Pak-Study<br/>Teacher Name</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>Close</td>
                                            <td>close</td>
                                        </tr>
                                        <tr align="center">
                                            <td>6:30am</td>
                                            <td class="color3 subject">Sindhi<br/>Teacher Name</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>Close</td>
                                            <td>close</td>
                                        </tr>
                                        <tr align="center">
                                            <td>7:30am</td>
                                            <td class="color4 subject">Scienes<br/>Teacher Name</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>Close</td>
                                            <td>close</td>
                                        </tr>
                                        <tr align="center">
                                            <td>9:30am</td>
                                            <td class="color5 subject">Company Scienes<br/>Teacher Name</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>Close</td>
                                            <td>close</td>
                                        </tr>
                                        <tr align="center">
                                            <td>5pm</td>
                                            <td class="color6 subject">Urdu<br/>Teacher Name</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>Close</td>
                                            <td>close</td>
                                        </tr>
                                        <tr align="center">
                                            <td>6pm</td>
                                            <td class="color7 subject">Mathematic<br/>Teacher Name</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>Close</td>
                                            <td>close</td>
                                        </tr>
                                        <tr align="center">
                                            <td>6:30pm</td>
                                            <td class="color8 subject">drawing<br/>Teacher Name</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>Close</td>
                                            <td>close</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="button_wrapper">
                                    <div class="color1 name_button">Teacher Name<em>X</em></div>
                                    <div class="color2 name_button">Teacher Name<em>X</em></div>
                                    <div class="color3 name_button">Teacher Name<em>X</em></div>
                                    <div class="color4 name_button">Teacher Name<em>X</em></div>
                                    <div class="color5 name_button">Teacher Name<em>X</em></div>
                                    <div class="color6 name_button">Teacher Name<em>X</em></div>
                                    <div class="color7 name_button">Teacher Name<em>X</em></div>
                                    <div class="color8 name_button">Teacher Name<em>X</em></div>
                                </div>
                        </div>
                    </div>
                </div>
             </div>
          </div>
     </div>
</div>