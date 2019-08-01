<?php
if(!defined("APP_START")) die("No Direct Access");
$exam = dofetch( doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.id = '".slash( $_GET[ "id" ] )."'", $dblink ) );
if(isset($_GET["class_section_id"])){
	$class_section_id=slash($_GET["class_section_id"]);
	$_SESSION["examination_manage"]["result"]["class_section_id"]=$class_section_id;
}
if(isset($_SESSION["examination_manage"]["result"]["class_section_id"])){
	$class_section_id=$_SESSION["examination_manage"]["result"]["class_section_id"];
}
else{
	$class_section_id = "";
}
if(isset($_GET["subject_id"])){
	$subject_id=slash($_GET["subject_id"]);
	$_SESSION["examination_manage"]["result"]["subject_id"]=$subject_id;
}
if(isset($_SESSION["examination_manage"]["result"]["subject_id"])){
	$subject_id=$_SESSION["examination_manage"]["result"]["subject_id"];
}
else{
	$subject_id = "";
}
if(isset($_GET["group_id"])){
	$group_id=slash($_GET["group_id"]);
	$_SESSION["examination_manage"]["result"]["group_id"]=$group_id;
}
if(isset($_SESSION["examination_manage"]["result"]["group_id"])){
	$group_id=$_SESSION["examination_manage"]["result"]["group_id"];
}
else{
	$group_id = "";
}
if( !empty( $class_section_id ) ) {
	$class_section = dofetch( doquery("select * from class_section where id = '".$class_section_id."'",$dblink));
	$rs = get_result( $exam, $class_section, 0, $subject_id );
	$groups = array();
	$empty = array(
		"id" => "",
		"title" => "No Group"
	);
	foreach( $rs as $k => $r ){
		if( !isset( $groups[$r[ "group" ]] ) ){
			if( !empty( $r[ "group" ] ) ){
				$g = doquery( "select title from `group` where id = '".$r[ "group" ]."'", $dblink );
				if( numrows( $g ) > 0 ){
					$g = dofetch($g);
					$groups[] = array(
						"id" => $r[ "group" ],
						"title" => unslash( $g[ "title" ] )
					);
				}
				else{
					$groups[$r[ "group" ]] = $empty;
				}
			}
			else{
				$groups[$r[ "group" ]] = $empty;
			}
		}
		$r[ "group" ] = empty($r[ "group" ])?"":$r[ "group" ];
		if( $r[ "group" ] != $group_id ){
			unset( $rs[ $k ] );
		}
	}
	ksort( $groups );
}
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  12px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px;
    padding: 5px 7px;
    font-size: 12px;
}

table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
</style>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr class="head">
        <th colspan="9">
            <?php echo get_config( 'fees_chalan_header' )?>
            <h2>Result Sheets of <?php echo $exam[ "title" ]?></h2>
            <p>
                <?php
                echo "List of";
                if( !empty( $class_section_id ) ){
                    echo " Class: ".get_field( get_field( $class_section_id, "class_section", "class_id" ), "class", "class_name" ).'-'.get_field( $class_section_id, "class_section");
                }
                if( !empty( $group_id ) ){
                    echo " Group: ".get_field( $group_id, "`group`");
                }
                if( !empty( $subject_id ) ){
                    echo " Subject: ".get_field( $subject_id, "subject");
                }
                ?>
            </p>
        </th>
    </tr>
    <tr>
        <th width="5%" class="center">S.No</th>
        <th width="20%" class="center">Student</th>
        <th width="15%" align="right">Total Marks</th>
        <th width="15%" align="right">Obtained Marks</th>
        <th width="15%" align="right">Percentage</th>
        <th width="15%">Grade</th>
        <th width="10%" class="center">Position</th>
    </tr>
	<?php
    if( !empty( $class_section_id ) ) {
        if(count($rs)>0){
            $sn=1;
            foreach( $rs as $r ){
                $student = dofetch( doquery( "select * from student where id = '".$r[ "id" ]."'", $dblink ) );
                $percent_grade = get_percent_grade( $r[ "obtained" ], $r[ "max" ]);
                ?>
                <tr>
                    <td class="center"><?php echo $sn;?></td>
                    <td><?php echo unslash( $student[ "student_name" ] )?></td>
                    <td align="right"><?php echo $r[ "max" ]; ?></td>
                    <td align="right"><?php echo $r[ "obtained" ]; ?></td>
                    <td align="right"><?php echo $percent_grade[ "percent" ]; ?></td>
                    <td><?php echo $percent_grade[ "grade" ]; ?></td>
                    <td><?php echo $percent_grade[ "grade" ]!='F'?($r[ "rank" ]<=3?ordinal( $r[ "rank" ] ):'Pass'):'Fail'; ?></td>
                </tr>  
                <?php 
                $sn++;
            }
        }
        else{	
            ?>
            <tr>
                <td colspan="9"  class="no-record">No Result Found</td>
            </tr>
            <?php
        }
    }else{	
        ?>
        <tr>
            <td colspan="9"  class="no-record">Select Class from dropdown</td>
        </tr>
        <?php
    }
    ?>
</table>
<?php
die;