<?php
if( isset( $_GET[ "academic_year_id" ] ) ) {
    $year_id = (int)$_GET[ "academic_year_id" ];
}
else{
    $academic_year = current_academic_year();
    $year_id=$academic_year["id"];
}
if(isset($_SESSION["logged_in_students"]["id"]) && !empty($_SESSION["logged_in_students"]["id"])){
    $students = doquery( "select a.*, b.class_section_id , concat( d.class_name, ' (', c.title, ')') as class_name, d.id as class_id, d.class_name as class from student a left join student_2_class b on a.id = b.student_id and academic_year_id = '".$year_id."' left join class_section c on b.class_section_id = c.id left join class d on c.class_id = d.id where a.id = '".slash($_SESSION["logged_in_students"][ "id" ] )."'", $dblink );
}
if( isset( $_GET[ "examination_type_id" ] ) ) {
    $exam =  doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.status = 1 and b.status = 1 and a.examination_type_id = '".((int)$_GET["examination_type_id"])."'", $dblink ); 
    if ( numrows($exam)>0) {
        $exam = dofetch($exam);
    }
    else{
        header("Location: student_manage.php?err='".url_encode("select Academic Year"));
        die;
    } 
}
else{
    header("Location: student_manage.php?err='".url_encode("select examination type"));
    die;
    
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Marksheet</title>
<style>
.center tr td{
    text-align:center;
    padding:2px 0 2px 0;
}
.table-left tr td{
    text-align:center;
}
.table-right tr td{
    text-align:center;
}
td{}
table {
    border-collapse: collapse;
}
table td, table th{
    font-size:14px;
}
h2 {
    margin: 0;
    padding: 4px 0;
    font-size: 22px;
    font-family: arial;
    text-transform: uppercase;
}
.center {
    margin: 3px 0;
}
.table-left tr td {
    padding: 4px 0;
    line-height: 21.3px;
}
.atten {
    line-height: 30px;
}
.logo img{
    width:110px;
}
.marksheet_header h1{ margin:0; font-size:20px; font-family:Arial, Helvetica, sans-serif}
.teacher-remarks td{  padding: 25px 0;}
.grading td {
    font-size: 12px;
}
.student_meta{ 
    display: block;border-bottom: solid 1px;
    font-weight:bold;
    font-family:arial;
}
.header{ background-color:#e5e5e5;}
.user-img{ width: 60px; height:60px; display:block; margin-right:10px; float:right; border:solid 1px #333;}
.user-img img{ object-fit: cover; width:100%; height:100%;}
.marks {
    width: 100%;
    border-collapse: collapse;
}

.marks th, .marks td {
    text-align: center;
    border: solid 1px #ccc;
    padding: 3px;
}

.marks th {
    background-color: #f7f7f7;
}
.marks .subject-name{ text-align:left;}
.bold{ font-weight:bold;}
.marks.assessment th{ background-color:#e5e5e5}
span.remarks {
    background-image: repeating-linear-gradient(180deg, transparent, transparent 19px, #000 20px);
    display: inline-block;
    line-height: 20px;
    height: 40px;
    width:100%;
}
.extra_height{ height:25px;}
.main-header{
height:60px; overflow:hidden;
width:100%;
}
.main-header td{ bprder: 0;}
.main-header h1{ font-size: 16px;
    text-transform: uppercase;
    font-weight: normal;margin:0;font-family:Arial, Helvetica, sans-serif;
}
.main-header h1 span{
    display: block;
    font-size: 28px;
    text-transform: uppercase;
    letter-spacing: 6px;
    font-weight: bold
}
.logo{ width:110px;}
.logo img{ height:54px; width: auto;}
.address{ text-align:right;}
.address p{ line-height: 21px;
    font-size: 12px;
    text-align: left;
    text-transform: uppercase;}
.info{ text-align:right; width: 180px;}
.info span {
    width: 100px;
    display: inline-block;
    border-bottom: solid 1px;
    font-weight: bold;
    font-size: 20px;
    text-align: center;
}
</style>
</head>
<body>
<?php
if( numrows( $students ) > 0 ) { while( $student = dofetch( $students ) ) {
	$class_section = dofetch( doquery("select * from class_section where id = '".$student["class_section_id"]."'",$dblink));
	
	$result = get_result( $exam, $class_section, $student[ "id" ] );
	if( is_array( $result ) ) {
		$attendance = array(
			"total" => 0,
			"present" => 0
		);
		$start_ts = strtotime(get_field( $year_id, 'academic_year', 'start_date' ));
		$end_ts = strtotime($exam[ "result_date" ]);
		for( $i = $start_ts; $i < $end_ts; $i+=24*3600 ) {
			if( !is_holiday( date("Y-m-d", $i) ) ) {
				$attendance["total"]++;
				$sql="select * from student_attendance where student_id='".$student["id"]."' and (checked_in>='".date("Y/m/d H:i:s", $i)."' and checked_in<'".date("Y/m/d H:i:s", $i+24*3600)."')"; 
				$a = doquery( $sql, $dblink );
				if( numrows( $a ) > 0 ) {
					$attendance["present"]++;
				}
			}
		}
		?>
		<div class="page-break-after: always">
			<table class="main-header">
				<tr>
					<td class="logo"><img src="<?php echo $site_url?>/uploads/config/<?php echo $school_logo?>" alt=""></td>
					<td>
						<h1><span><?php echo get_config( 'ms_heading' )?></span><?php echo get_config( 'ms_subheading' )?></h1>
					</td>
					<td class="address">
						<p><?php echo nl2br(get_config( 'ms_address' ))?></p>
					</td>
					<td class="info">Sr. No. <span>&nbsp;</span>
					<br>SID <span><?php echo $student[ "id" ]?></span></td>
				</tr>
			</table>
			<table border="0" width="100%">
				<tr>
					<td class="header" align="center" colspan="9"><h2><?php echo unslash( $exam[ "title" ] )?> Report Card <?php echo $academic_year["title"]?></h2></td>
				</tr>
				<tr>
					<td width="4%">Name:</td>
					<td width="20%"><span class="student_meta"><?php echo unslash( $student[ "student_name" ] )?></span></td>
					<td width="8%">Father's Name:</td>
					<td width="20%"><span class="student_meta"><?php echo unslash( $student[ "father_name" ] )?></span></td>
					<td width="5%">Surname:</td>
					<td width="13%"><span class="student_meta"><?php echo $student[ "surname" ]?></span></td>
					<td width="6%">Birth Date:</td>
					<td width="14%"><span class="student_meta"><?php echo date_convert( $student["birth_date"] );?></span> </td>
					<td align="right" rowspan="2">
						<?php $student_image = get_student_meta($student['id'], "student_image");?>
						<span class="user-img"><img src="<?php echo !empty($student_image)?$file_upload_url."student/".$student_image:"images/placeholder.jpg"?>" /></span>
					</td>
				</tr>
				<tr>
					<td>Age:</td>
					<td><span class="student_meta"><?php show_age( $student["birth_date"], $exam[ "result_date" ]);?></span> </td>
					<td>Average Age:</td>
					<td><span class="student_meta"><?php echo get_average_age($student["class_section_id"], $year_id, $exam[ "result_date" ])?></span></td>
					<td>Class:</td>
					<td><span class="student_meta"><?php echo $student[ "class_name" ]?></span></td>
					<td>Result Date:</td>
					<td><span class="student_meta"><?php echo date_convert( $exam[ "result_date" ] )?></span></td>
				</tr>
			</table>
			<table class="marks">
				<tr>
					<th rowspan="<?php echo $exam[ "generate_marksheet" ]!=0?3:2?>">Subject</th>
					<?php
					if( $exam[ "generate_marksheet" ]==2 ){
						?>
						<th colspan="5" rowspan="2"><?php echo $result[ "mid_term_title" ]?></th>
						<?php
					}
					?>
					<th colspan="<?php echo $exam[ "generate_marksheet" ]==0?'5':($exam[ "generate_marksheet" ]==1?13:8)?>">First Term Report</th>
					<?php
					if( $exam[ "generate_marksheet" ]==2 ){
						?>
						<th colspan="5" rowspan="2">Over All Perfomance</th>
						<?php
					}
					?>
				</tr>
				<?php
				if( $exam[ "generate_marksheet" ]!=0 ){
					?>
					<tr>
						<th colspan="4">Class Test</th>
						<th colspan="4"><?php echo unslash( $exam[ "title" ] )?></th>
						<?php
						if( $exam[ "generate_marksheet" ]==1 ){
							?>
							<th colspan="5">Over All Perfomance</th>
							<?php
						}
						?>
					</tr>
					<?php
					}
				?>
				<tr>
					<?php
					if( $exam[ "generate_marksheet" ]!=0 ){
						if( $exam[ "generate_marksheet" ]==2 ){
							?>
							<th>Max</th>
							<th>Obt</th>
							<th>Per%</th>
							<th>Grade</th>
							<th>Rank</th>
							<?php
						}
						?>
						<th>Max</th>
						<th>Obt</th>
						<th>Per%</th>
						<th>Grade</th>
						<th>Max</th>
						<th>Obt</th>
						<th>Per%</th>
						<th>Grade</th>
						<?php
					}
					?>
					<th>Max</th>
					<th>Obt</th>
					<th>Per%</th>
					<th>Grade</th>
					<th>Rank</th>
				</tr>
				<?php
				$row_count = $class_test_max_total = $class_test_obt_total = $final_max_total = $final_obt_total = $final_max_total_mid = $final_obt_total_mid = 0;
				$fail_count = $fail_count_mid = 0;
				foreach( $result["subject"] as $subject_id => $marks ){
					$row_count++;
					?>
					<tr>
						<th class="subject-name"><?php echo $marks[ "title" ]?></th>
						<?php
						if( $exam[ "generate_marksheet" ]!=0 ){
							$class_test_max_total += $marks[ "class_tests" ][ "max" ];
							$class_test_obt_total += $marks[ "class_tests" ][ "marks" ];
							$final_max_total += $marks[ "exam" ][ "max" ];
							$final_obt_total += $marks[ "exam" ][ "marks" ];
							if( $exam[ "generate_marksheet" ]==2 ){
								$marks_mid = $result["mid_term"]["subject"][$subject_id];
								$final_max_total_mid += $marks_mid[ "total" ][ "max" ];
								$final_obt_total_mid += $marks_mid[ "total" ][ "obtained" ];
								?>
								<td><?php echo $marks_mid[ "total" ][ "max" ]?></td>
								<td><?php echo $marks_mid[ "total" ][ "obtained" ];?></td>
								<td><?php $percent_grade = get_percent_grade( $marks_mid[ "total" ][ "obtained" ], $marks_mid[ "total" ][ "max" ] ); echo $percent_grade[ "percent" ]?></td>
								<td><?php echo $percent_grade[ "grade" ]?></td>
								<td><?php echo $percent_grade[ "grade" ]=='F'?'FAIL':ordinal( $marks_mid[ "rank" ] )?></td>
								<?php
								if( $percent_grade[ "grade" ]=='F' ){
									$fail_count_mid++;
								}
							}
							?>
							<td><?php echo $marks[ "class_tests" ][ "max" ]?></td>
							<td><?php echo $marks[ "class_tests" ][ "marks" ];?></td>
							<td><?php $percent_grade = get_percent_grade( $marks[ "class_tests" ][ "marks" ], $marks[ "class_tests" ][ "max" ] ); echo $percent_grade[ "percent" ]?></td>
							<td><?php echo $percent_grade[ "grade" ]?></td>
							<td><?php echo $marks[ "exam" ][ "max" ]?></td>
							<td><?php echo $marks[ "exam" ][ "marks" ]?></td>
							<td><?php $percent_grade = get_percent_grade( $marks[ "exam" ][ "marks" ], $marks[ "exam" ][ "max" ] ); echo $percent_grade[ "percent" ]?></td>
							<td><?php echo $percent_grade[ "grade" ]?></td>
							<?php
						}
						?>
						<td><?php echo $marks[ "total" ][ "max" ]?></td>
						<td><?php echo $marks[ "total" ][ "obtained" ]?></td>
						<td><?php $percent_grade = get_percent_grade( $marks[ "total" ][ "obtained" ], $marks[ "total" ][ "max" ] ); echo $percent_grade[ "percent" ]?></td>
						<td><?php echo $percent_grade[ "grade" ]?></td>
						<td><?php echo $percent_grade[ "grade" ]=='F'?'FAIL':ordinal( $marks[ "rank" ] )?></td>
					</tr>
					<?php
					if( $percent_grade[ "grade" ]=='F' ){
						$fail_count++;
					}
				}
				for( $i = $row_count; $i <10; $i++ ){
					?>
					<tr>
						<th class="subject-name">&nbsp;</th>
						<?php
						if( $exam[ "generate_marksheet" ]!=0 ){
							if( $exam[ "generate_marksheet" ]==2 ){
								?>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<?php
							}
							?>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<?php
						}
						?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<th class="subject-name">Total</th>
					<?php
					if( $exam[ "generate_marksheet" ]!=0 ){
						if( $exam[ "generate_marksheet" ]==2 ){
							?>
							<th><?php echo $final_max_total_mid?></th>
							<th><?php echo $final_obt_total_mid?></th>
							<th><?php $percent_grade = get_percent_grade( $final_obt_total_mid, $final_max_total_mid ); echo $percent_grade[ "percent" ]?></th>
							<th><?php echo $percent_grade[ "grade" ]?></th>
							<th><?php echo $fail_count_mid>1?'FAIL':($fail_count_mid==1?"PROMOTED":($result["mid_term"]["rank"]<=3?ordinal( $result["mid_term"]["rank"] ):'PASS'));?></th>
							<?php
						}
						?>
						<th><?php echo $class_test_max_total?></th>
						<th><?php echo $class_test_obt_total?></th>
						<th><?php $percent_grade = get_percent_grade( $class_test_obt_total, $class_test_max_total ); echo $percent_grade[ "percent" ]?></th>
						<th><?php echo $percent_grade[ "grade" ]?></th>
						<th><?php echo $final_max_total?></th>
						<th><?php echo $final_obt_total?></th>
						<th><?php $percent_grade = get_percent_grade( $final_obt_total, $final_max_total ); echo $percent_grade[ "percent" ]?></th>
						<th><?php echo $percent_grade[ "grade" ]?></th>
						<?php
					}
					?>
					<th><?php echo $result["max"]?></th>
					<th><?php echo $result["obtained"]?></th>
					<th><?php $percent_grade = get_percent_grade( $result["obtained"], $result["max"]); echo $percent_grade[ "percent" ]?></th>
					<th><?php echo $percent_grade[ "grade" ]?></th>
					<th><?php
						echo $fail_count>1?'FAIL':($fail_count==1?"PROMOTED":($result["rank"]<=3?ordinal( $result["rank"] ):'PASS'));
					?></th>
				</tr>
			</table>
			<?php
			$examination_result_students = doquery( "select * from examination_result_students where student_id = '".$student[ "id" ]."' and exam_id = '".$exam[ "id" ]."'", $dblink );
			if( numrows( $examination_result_students ) > 0 ) {
				$examination_result_students = dofetch( $examination_result_students );
			}
			else{
				$examination_result_students = array();
			}
			?>
			<table class="marks assessment">
				<tr>
					<th colspan="2">Home Work</th>
					<th colspan="2">Behavior</th>
					<th colspan="2">Class Representation</th>
					<th colspan="2">Sports</th>
					<th rowspan="3" width="15%">Attendance</th>
					<td width="12%">Total</td>
					<td class="bold"><?php echo $attendance["total"]?></td>
				</tr>
				<tr>
					<td width="13%">Punctual</td>
					<td width="4%"><?php echo isset($examination_result_students[ "punctual" ])?get_assessment( $examination_result_students[ "punctual" ]):"-"?></td>
					<td width="13%">Attitude</td>
					<td width="4%"><?php echo isset($examination_result_students[ "attitude" ])?get_assessment( $examination_result_students[ "attitude" ]):"-"?></td>
					<td width="13%">Presentation</td>
					<td width="4%"><?php echo isset($examination_result_students[ "presentation" ])?get_assessment( $examination_result_students[ "presentation" ]):"-"?></td>
					<td width="13%">Fitness</td>
					<td width="4%"><?php echo isset($examination_result_students[ "fitness" ])?get_assessment( $examination_result_students[ "fitness" ]):"-"?></td>
					<td>Presnt</td>
					<td class="bold"><?php echo $attendance["present"]?></td>
				</tr>
				<tr>
					<td>Neatness</td>
					<td><?php echo isset($examination_result_students[ "neatness" ])?get_assessment( $examination_result_students[ "neatness" ]):"-"?></td>
					<td>Discipline</td>
					<td><?php echo isset($examination_result_students[ "discipline" ])?get_assessment( $examination_result_students[ "discipline" ]):"-"?></td>
					<td>Language Skill</td>
					<td><?php echo isset($examination_result_students[ "language_skill" ])?get_assessment( $examination_result_students[ "language_skill" ]):"-"?></td>
					<td>Commitment</td>
					<td><?php echo isset($examination_result_students[ "commitment" ])?get_assessment( $examination_result_students[ "commitment" ]):"-"?></td>
					<td>Absent</td>
					<td class="bold"><?php echo $attendance["total"]-$attendance["present"]?></td>
				</tr>
				<tr>
					<td>Hand Writing</td>
					<td><?php echo isset($examination_result_students[ "hand_writing" ])?get_assessment( $examination_result_students[ "hand_writing" ]):"-"?></td>
					<td>Co-Opration</td>
					<td><?php echo isset($examination_result_students[ "cooperation" ])?get_assessment( $examination_result_students[ "cooperation" ]):"-"?></td>
					<td>Confidence</td>
					<td><?php echo isset($examination_result_students[ "confidence" ])?get_assessment( $examination_result_students[ "confidence" ]):"-"?></td>
					<td>Determination</td>
					<td><?php echo isset($examination_result_students[ "determination" ])?get_assessment( $examination_result_students[ "determination" ]):"-"?></td>
					<th rowspan="2">Parents Teacher Meeting</th>
					<td>1st Meeting</td>
					<td class="bold">Yes</td>
				</tr>
				<tr>
					<td>Content</td>
					<td><?php echo isset($examination_result_students[ "content" ])?get_assessment( $examination_result_students[ "content" ]):"-"?></td>
					<td>Gesture</td>
					<td><?php echo isset($examination_result_students[ "gesture" ])?get_assessment( $examination_result_students[ "gesture" ]):"-"?></td>
					<td>Understanding</td>
					<td><?php echo isset($examination_result_students[ "understanding" ])?get_assessment( $examination_result_students[ "understanding" ]):"-"?></td>
					<td>Competition</td>
					<td><?php echo isset($examination_result_students[ "competition" ])?get_assessment( $examination_result_students[ "competition" ]):"-"?></td>
					<td>2nd Meeting</td>
					<td class="bold">No</td>
				</tr>
			</table>
				
			<table border="0" width="100%">
				<tr>
					<td width="15%" class="bold" style="vertical-align:top; padding-top:5px">Class Teacher's Remarks</td>
					<td colspan="4"><span class="remarks"><?php echo isset($examination_result_students[ "remarks" ])?$examination_result_students[ "remarks" ]:""?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="extra_height">&nbsp;</td>
				</tr>
				<tr>
					<td class="bold">Class Teacher's Signature</td>
					<td width="20%"><span class="student_meta">&nbsp;</span></td>
					<td>&nbsp;</td>
					<td width="15%" class="bold" align="right">School Head's Signature</td>
					<td width="20%"><span class="student_meta">&nbsp;</span></td>
				</tr>
				
			</table>
			<table border="1" width="100%" class="center grading">
				<tr>
					<td rowspan="2"><strong>Grading Key:</strong></td>
					<td><strong>A+</strong></td>
					<td rowspan="2">&nbsp;</td>
					<td><strong>A</strong></td>
					<td rowspan="2">&nbsp;</td>
					<td><strong>B+</strong></td>
					<td rowspan="2">&nbsp;</td>
					<td><strong>B</strong></td>
					<td rowspan="2">&nbsp;</td>
					<td><strong>C</strong></td>
					<td rowspan="2">&nbsp;</td>
					<td><strong>D</strong></td>
					<td rowspan="2">&nbsp;</td>
					<td><strong>E</strong></td>
					<td rowspan="2">&nbsp;</td>
					<td><strong>F</strong></td>
				</tr>
				<tr>
					<td><strong>90-99</strong></td>
					<td><strong>80-89</strong></td>
					<td><strong>70-79</strong></td>
					<td><strong>60-69</strong></td>
					<td><strong>50-59</strong></td>
					<td><strong>40-49</strong></td>
					<td><strong>34-39</strong></td>
					<td><strong>Below 34</strong></td>
				</tr>
			</table>
			<table border="0" width="100%" style="border: solid 1px #000;" class="grading">
				<tr>
					<td><strong>Key =</strong></td>
					<td><strong>E: </strong>Excellent level of achievement</td>
					<td><strong>D: </strong>Developing Appropriatley</td>
					<td><strong>S: </strong>SatisFactory</td>
					<td><strong>A: </strong>Attention Required</td>
					<td><strong>Na: </strong>Needs Special Attention</td>
				</tr>
			</table>
		 </div>
		 <?php
		}
	}
}
 ?>
</body>
</html>
<?php
die();?>