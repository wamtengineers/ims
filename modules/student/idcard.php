<?php
 if(!defined("APP_START")) die("No Direct Access");
 if(isset($_GET["id"]) && !empty($_GET["id"])){
	$students=doquery("select * from student where id='".slash($_GET["id"])."'", $dblink);
 }
 else{
	$students=doquery($sql, $dblink);
 }
 if( numrows( $students ) > 0 ){
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Student iD Card</title>
</head>
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
</style>
<body>
<?php
while( $student = dofetch( $students ) ) {
	$id = $student[ "id" ];
	$barcode = str_repeat('0', 7-strlen($id)).$id;
	$student_image = get_student_meta($student['id'], "student_image");
	?>
    <div class="wrapper <?php echo get_config( 'idcard_color' )?>">
        <div class="card_heading">
            <div class="heading"><h1>Student ID Card</h1></div>
            <div class="logo_area">
                <div class="card_logo" style="margin-bottom:5px;">
                    <img src="<?php echo $file_upload_url;?>config/<?php echo $school_logo?>" alt="image" />
                </div>
                <p><?php echo nl2br($school_address);?></p>
            </div>
        </div>
        <div class="card_details">
            <div class="detail-box">
                <div class="detail name"><span>Name: </span><?php echo unslash($student["student_name"]); ?></div>
                <div class="detail name"><span>Father Name: </span><?php echo unslash($student["father_name"]); ?></div>
                <div class="detail"><span>Date of Birth: </span><?php echo date_convert($student["birth_date"]); ?> </div>
                <div class="detail"><span>Student Class: </span><?php echo get_student_class($student["id"]); ?></div>
                <div class="detail addr"><span>Address: </span><?php echo empty($student["address"])?"--":unslash($student["address"]); ?></div>
            </div>
        </div>
        <div class="barcode"><img src="barcode.php?text=<?php echo $barcode?>" /><span style="
        text-align: center;
        letter-spacing: 11px;
        margin-left: 8px;
    "><?php echo $barcode = str_repeat('0', 7-strlen($id)).$id;?></span></div>
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
?>
</body>
</html>
<?php
die();
}


