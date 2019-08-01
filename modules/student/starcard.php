<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$students=doquery("select * from student where id='".slash($_GET["id"])."'", $dblink);
}
else{
	$students=doquery($sql, $dblink);
}
$rs = doquery( "select * from subject where status = 1 and school_id = '".$_SESSION["current_school_id"]."'", $dblink );
while( $r = dofetch( $rs ) ) {
	$subjects_colors[ $r[ "id" ] ] = $r[ "color" ];
}
if( numrows( $students ) > 0 ){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Student iD Card</title>
<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet" href="css/idcard.css" />
<style>
.detail{ margin-bottom:2px;}
.wrapper {
    display: inline-block;
    margin: 20px 10px;
    text-align: left;
}

body {
    text-align: center;
    max-width: 8.5in;
	padding:20px 0;
}
.addr{
	height: 34px;
    overflow: hidden;
    text-overflow: ellipsis;
}
.card_details {
    margin-top: 38px;
}

.heading {
    margin-bottom: 0;
}

.detail.name  span {
    display: block;
	font-weight: normal;
}
.detail.name {
    font-weight: bold;
}

.barcode {
    bottom: 4px;
}
.blue .card_logo {
    padding-left: 4px;
    padding-right: 11px;
}

.blue .logo_area p {line-height: 12px;margin-top: 10px;}

.logo_area {}

.blue .heading h1 {
    background: #3882e7;
}

.blue .heading h1::after {
    border-color: transparent transparent transparent #3882e7;
}

.heading h1::after {
    border-width: 26px 0 0 18px;
}

.blue .card_image {
    background-color: #3882e7;
}
.img_box{ padding:0; padding-left:5px;}
.logo_area {
    position: absolute;
    width: 100%;
    bottom: 5px;
}

.card_heading {
    position: relative;
    height: 100%;
    width: 30%;
	padding-top:26px;
	box-sizing:border-box;
}
.card_heading .stars-container{
	padding-left: 10px;
}

.card_heading p {
    margin-top: 0 !important;
}

.wrapper {
    background: url('https://image.shutterstock.com/image-illustration/white-blur-abstract-background-bokeh-260nw-646789303.jpg');
    background-size: cover;
}

.card_image {
    background-color: rgba(56,130,231,0.6) !important;
}
.card_image.green {
    background-color: rgba(1,137,135,0.6) !important;
}
.card_image.red {
    background-color: rgba(1,137,135,0.6) !important;
}

.heading img {
    width: 116px;
}

.card_logo img {
    width: 50%;
    display: block;
}

.exam_title {
    text-transform: uppercase;
    text-align: center;
    font-size: 16px;
    font-weight: bolder;
    position: absolute;
    padding-top: 5px;
    /* padding-left: 10px; */
    color: #000;
    -webkit-text-fill-color: #3882e7;
    -webkit-text-stroke-width: 1px;
    -webkit-text-stroke-color: #000;
    text-shadow: 1px -1px #000, -1px 1px #000;
    text-align: center;
    width: 78%;
}
.exam_title.blue{
	-webkit-text-fill-color: #3882e7;
}
.exam_title.red{
	-webkit-text-fill-color: #3882e7;
}
.exam_title.green{
	-webkit-text-fill-color: #018987;
}
.detail.name span {font-size: 14px;font-weight: bold;}

.stars-container {
    position: absolute;
    bottom: 10px;
	width:60px;
	text-align:center;
}
.position img {
    width: 100%;
}

.position {
    position: absolute;
    width: 75px;
    right: -15px;
    top: 83px;
    z-index: 999;
}

.card_details {
    position: relative;
    height: 100%;
    margin-top: 0;
    padding-top: 38px;
}
.stars {
    font-size: 11px;
    border-radius: 3px;
    padding: 0 10px;
    color: #fff;
    margin-top: 3px;
    height: 12px;
    line-height: 12px;
    background-color: #3882e7;
}
</style>
</head>
<body>
<?php
while( $student = dofetch( $students ) ) {
	$student = dofetch( doquery( "select a.*, b.class_section_id, c.title as section, d.id as class_id, d.class_name as class from student a left join student_2_class b on a.id = b.student_id and academic_year_id = '".$year_id."' left join class_section c on b.class_section_id = c.id left join class d on c.class_id = d.id where a.id = '".slash( $student[ "id" ] )."'", $dblink ) );
	if( isset( $student[ "examination_id" ] ) ) {
		$exam = dofetch( doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.status = 1 and b.status = 1 and a.id = '".((int)$_GET["examination_id"])."'", $dblink ));	
	}
	else{
		$exam = dofetch( doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.status = 1 and b.status = 1 and generate_marksheet=1 and academic_year_id = '".$year_id."'", $dblink ));
	}
	$class_section = dofetch( doquery("select * from class_section where id = '".$student["class_section_id"]."'",$dblink));
	$result = get_result( $exam, $class_section, $student[ "id" ] );
	$id = $student[ "id" ];
	$student_image = get_student_meta($student['id'], "student_image");
	$subject_stars = array();
	foreach( $result[ "subject" ] as $subject_id => $marks ) {
		if( $marks[ "rank" ] <= 3  ) {
			$percent_grade = get_percent_grade( $marks[ "exam" ][ "marks" ], $marks[ "exam" ][ "max" ] );
			if( $percent_grade[ "grade" ] != 'F' ) {
				$subject_stars[] = array(
					'subject_id' => $subject_id,
					'rank' => $marks[ "rank" ]
				);
			}
		}
	}
	$percent_grade = get_percent_grade( $result["obtained"], $result["max"]);
	if( $percent_grade[ "grade" ] != 'F' && $result[ "rank" ] <= 3 || count( $subject_stars ) > 0 ) {
		?>
        <div class="wrapper <?php echo get_config( 'idcard_color' )?>">
        	<div class="exam_title"><?php echo unslash( $exam[ "title" ] )." ".get_title($year_id, 'academic_year' )?> Class: <?php echo $student[ "class" ]."-".$student[ "section" ]?></div>        
            <div class="card_heading">
                <div class="heading"><img src="<?php echo $site_url?>/images/star-img.png"></div>
                <?php
                if( count( $subject_stars ) > 0 ) {
					?>
					<div class="stars-container">
                    	<?php
						$cnt = 0;
                        foreach( $subject_stars as $stars ) {
							if( $cnt == 5 ) {
								break;
							}
							$cnt++;
							?>
							<div class="stars" style="background-color: <?php echo $subjects_colors[ $stars[ "subject_id" ] ]?>;">
                            	<?php
                                for( $i = 0; $i <= 3-$stars[ "rank" ]; $i++ ) {
									?>
									<i class="fa fa-star"></i>
									<?php
								}
								?>
                            </div>
							<?php	
						}
						?>
                    </div>
					<?php
				}
				?>
            </div>
            <div class="card_details">
                <?php
                if( $result[ "rank" ] <= 3 && $percent_grade[ "grade" ] != 'F' ) {
					?>
                    <div class="position"><img src="<?php echo $site_url?>/images/position-<?php echo $result[ "rank" ]?>.png"></div>
                    <?php
				}
				?>
                <div class="detail-box">
                    <div class="detail name">
                        <span><?php echo unslash($student["student_name"]); ?></span> s/o <?php echo unslash($student["father_name"]); ?>
                    </div>
                </div>
    			<?php
                if( count( $subject_stars ) > 5 ) {
					?>
					<div class="stars-container">
						<?php
                        $subject_stars = array_slice( $subject_stars, 5 );
                        $cnt = 0;
                        foreach( $subject_stars as $stars ) {
                            if( $cnt == 5 ) {
                                break;
                            }
                            $cnt++;
                            ?>
                            <div class="stars" style="background-color: #f90;">
                                <?php
                                for( $i = 0; $i <= 3-$stars[ "rank" ]; $i++ ) {
                                    ?>
                                    <i class="fa fa-star"></i>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php	
                        }
						?>
					</div>	
					<?php
				}
				?>
            </div>
            
            <div class="card_image">
                <div class="image"><img src="<?php echo !empty($student_image)?$file_upload_url."student/".$student_image:"images/placeholder.jpg"?>" /></div>
                <div class="img_box">
                    <div class="student_id"><span>Student ID: </span><?php echo $student["id"]?></div>
                    <div class="phone">Ph: <?php $val=get_student_meta($student["id"], "phone"); echo !empty($val)?$val:"--" ?></div>
                    <div class="phone">Issue: <?php echo date( "d/m/y", strtotime( $academic_year[ "start_date" ] ))?></div>
                    <div class="phone">Expiry: <?php echo date( "d/m/y", strtotime( $academic_year[ "end_date" ] ))?></div>
                </div>
            </div>
        </div>
    	<?php
	}
}
?>
</body>
</html>
<?php
die();
}


